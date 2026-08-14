# Fix: Live chat between users (WebSocket wiring)

## Summary

Chat messages were being saved to the database but never delivered live to the other
participant. There were six separate, stacked bugs across the backend broadcast, the
frontend Echo client, and the deployment config. This document lists each one, the
fix applied, and what still needs doing outside the codebase (running the actual
websocket process in production).

---

## 1. The broadcast call was commented out

**File:** `app/Http/Controllers/Profile/ChatController.php`

The single most direct cause: the message was saved to the DB, but the line that would
push it to the websocket channel was disabled, and the response never returned the
created message (needed by the frontend to reconcile its optimistic bubble).

```diff
-        // Broadcast the event (sending message to others on the chat channel)
-        // broadcast(new MessageSent($message));
+        // Broadcast the event (sending message to others on the chat channel)
+        broadcast(new MessageSent($message))->toOthers();

         // Log notification
         $receiverCompanyId = optional($message->receiver)->company_id ?? null;
         if ($receiverCompanyId) {
             $this->StoreNotification($sender->company_id ?? 0, 2, $sender);
         }


-        return response()->json(['status' => 'Message Sent!']);
+        return response()->json(['status' => 'Message Sent!', 'message' => $message]);
```

---

## 2. Broadcast payload didn't match the model or the frontend listener

**File:** `app/Events/MessageSent.php`

- `broadcastWith()` sent a `content` key, but the `Message` model only has a `message`
  column — the field would always have been `null`.
- The frontend listener expects a nested `event.message` object (`ChatBox.vue`:
  `if (event?.message) { this.messages.push(event.message) }`), but the event emitted
  flat top-level keys.
- The channel was a public `Channel`, even though `routes/channels.php` already defines
  a private-channel authorization rule for this exact channel name pattern — meaning the
  auth rule was dead code and any client holding the (effectively public) app key could
  subscribe to `chat.{id}-{id}` and read someone else's messages.

```diff
 use App\Models\Message;
-use Illuminate\Broadcasting\Channel;
 use Illuminate\Broadcasting\InteractsWithSockets;
+use Illuminate\Broadcasting\PrivateChannel;
 ...
     public function broadcastOn()
     {
         $senderId = $this->message->user_id;
         $receiverId = $this->message->receiver_id;

         $channelName = 'chat.' . collect([$senderId, $receiverId])->sort()->join('-');

-        return new Channel($channelName);
+        return new PrivateChannel($channelName);
     }

     public function broadcastWith()
     {
-        // Customize what gets sent to the frontend
         return [
-            'id' => $this->message->id,
-            'sender_id' => $this->message->user_id,
-            'receiver_id' => $this->message->receiver_id,
-            'content' => $this->message->content,
-            'created_at' => $this->message->created_at->toDateTimeString(),
+            'message' => [
+                'id' => $this->message->id,
+                'user_id' => $this->message->user_id,
+                'sender_id' => $this->message->user_id,
+                'receiver_id' => $this->message->receiver_id,
+                'message' => $this->message->message,
+                'created_at' => $this->message->created_at->toDateTimeString(),
+            ],
         ];
     }
```

---

## 3. Frontend Echo client: wrong CSRF source, hardcoded TLS/port

**File:** `resources/js/bootstrap/echo.js`

- The auth header read from a `<meta name="csrf-token">` tag that doesn't exist anywhere
  in `resources/views/app.blade.php` — the token is instead exposed as
  `window.Laravel.csrfToken`. The header was always `undefined`, which would have broken
  private-channel authorization even once channels were switched to private.
- `forceTLS: true` and port `6001` were hardcoded. In local/dev (`laravel-websockets`
  serving plain `ws://`, not `wss://`), this would make every connection attempt fail
  before it even reached the message logic. Host/port/scheme are now read from the
  existing (already-defined-but-unused) `VITE_PUSHER_*` env vars, matching how the rest
  of the app already configures Pusher.

```diff
+const useTLS = (import.meta.env.VITE_PUSHER_SCHEME || "https") === "https";
+const wsPort = Number(import.meta.env.VITE_PUSHER_PORT) || 6001;
+
 const echo = new Echo({
   broadcaster: "pusher",
   key: import.meta.env.VITE_PUSHER_APP_KEY || "somekey",
-  wsHost: window.location.hostname, // e.g. www.sentechxperience.co.za
-  wsPort: 6001, // Laravel WebSockets default port
-  wssPort: 6001, // secure WebSocket
-  forceTLS: true,
+  wsHost: import.meta.env.VITE_PUSHER_HOST || window.location.hostname,
+  wsPort: wsPort,
+  wssPort: wsPort,
+  forceTLS: useTLS,
   disableStats: true,
-  enabledTransports: ["ws", "wss"],
+  enabledTransports: useTLS ? ["ws", "wss"] : ["ws"],
   authEndpoint: "/broadcasting/auth",
-cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
+  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
   auth: {
     headers: {
-      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content"),
+      "X-CSRF-TOKEN": window.Laravel?.csrfToken,
     },
   },
 });
```

---

## 4. `ChatBox.vue` subscribed to a public channel and had no dedupe

**File:** `resources/js/Pages/Profile/ChatBox.vue`

Switched to `Echo.private()` to match the now-private channel, and added a dedupe check
so a sender who is also subscribed to their own chat channel doesn't see their message
appear twice (once from the optimistic HTTP-response reconciliation, once from the
broadcast echo).

```diff
-      // Listen for new messages
-      Echo.channel(channelName).listen("MessageSent", (event) => {
-        if (event?.message) {
-          this.messages.push(event.message);
-          this.scrollToBottom();
-        }
-      });
+      // Listen for new messages (private channel: only the two participants can subscribe)
+      Echo.private(channelName).listen("MessageSent", (event) => {
+        if (event?.message) {
+          const incoming = event.message;
+          const alreadyHave = this.messages.some(
+            (m) => m.id === incoming.id && !String(m.id).startsWith("tmp-")
+          );
+          if (alreadyHave) return;
+
+          const tempIdx = this.messages.findIndex(
+            (m) => String(m.id).startsWith("tmp-") && m.message === incoming.message
+          );
+          if (tempIdx !== -1) {
+            this.messages.splice(tempIdx, 1, incoming);
+          } else {
+            this.messages.push(incoming);
+          }
+          this.scrollToBottom();
+        }
+      });
```

---

## 5. `@` import alias didn't exist

**File:** `vite.config.js`

`ChatBox.vue` imports `Echo` via `import Echo from "@/bootstrap/echo"`, but no `@` alias
was registered anywhere in the Vite config — this import could not resolve.

```diff
     resolve: {
         alias: {
+            '@': path.resolve(__dirname, 'resources/js'),
             '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
             '~sweetalert2': path.resolve(__dirname, 'node_modules/sweetalert2'),
         }
     },
```

---

## 6. Broadcast driver / Pusher config was blank or wrong for every environment

**Files:** `.env.example`, `.env.pipelines`

- `.env.example` had `BROADCAST_DRIVER=log` (broadcasting silently discarded) and blank
  `PUSHER_APP_ID/KEY/SECRET/HOST`. Filled these with values for the self-hosted
  `laravel-websockets` server (this app does not use pusher.com — see
  `config/websockets.php`, which reuses the same `PUSHER_*` env vars for its own app
  credentials).
- `.env.pipelines` (used only to run `phpunit` in CI, see `devops/run-tests.sh`) had no
  `BROADCAST_DRIVER` at all, silently falling back to `null`. Set explicitly to `log` so
  CI never attempts a real websocket connection.

```diff
 # .env.example
-BROADCAST_DRIVER=log
+BROADCAST_DRIVER=pusher
 ...
-PUSHER_APP_ID=
-PUSHER_APP_KEY=
-PUSHER_APP_SECRET=
-PUSHER_HOST=
-PUSHER_PORT=443
-PUSHER_SCHEME=https
+# Self-hosted via beyondcode/laravel-websockets (config/websockets.php), not pusher.com.
+# `php artisan websockets:serve` must be running and reachable at PUSHER_HOST:LARAVEL_WEBSOCKETS_PORT.
+LARAVEL_WEBSOCKETS_PORT=6001
+PUSHER_APP_ID=1
+PUSHER_APP_KEY=websockets-local-key
+PUSHER_APP_SECRET=websockets-local-secret
+PUSHER_HOST=127.0.0.1
+PUSHER_PORT=6001
+PUSHER_SCHEME=http
```

```diff
 # .env.pipelines
 APP_ENV=local
 APP_KEY=base64:UJJMY31WTwkoaRt7S8NmO9+cRmtsyvZKKKN6NNu0tvY=
+BROADCAST_DRIVER=log
 DB_CONNECTION=mysql
```

**Production note:** the real deployed environment's `.env` (on the sentechxperience.co.za
host) needs its own `PUSHER_HOST`/`PUSHER_SCHEME`/`PUSHER_PORT` pointed at wherever the
websocket server is actually reachable from the browser (typically `https`/`443` through a
reverse proxy in front of `websockets:serve`, e.g. nginx forwarding `wss://` traffic to
`127.0.0.1:6001`). This wasn't guessed/changed here since it depends on real infra that
isn't in this repo.

---

## 7. No websocket server process was ever run

**File:** `docker-compose.yml`

`beyondcode/laravel-websockets` was installed and auto-registered, but nothing started
`php artisan websockets:serve` anywhere — not in `docker-compose.yml`, not in any
`devops/*.sh` script, not in `bitbucket-pipelines.yml`. Added a `websockets` service to
`docker-compose.yml` (Sail/local dev) that runs the server and exposes port 6001:

```diff
         volumes:
             - '.:/var/www/html'
         networks:
             - sail
         depends_on:
             - mysql
+    websockets:
+        build:
+            context: ./vendor/laravel/sail/runtimes/8.2
+            dockerfile: Dockerfile
+            args:
+                WWWGROUP: '${WWWGROUP}'
+        image: sail-8.2/app
+        extra_hosts:
+            - 'host.docker.internal:host-gateway'
+        ports:
+            - '${LARAVEL_WEBSOCKETS_PORT:-6001}:6001'
+        environment:
+            WWWUSER: '${WWWUSER}'
+            LARAVEL_SAIL: 1
+        volumes:
+            - '.:/var/www/html'
+        networks:
+            - sail
+        depends_on:
+            - mysql
+        command: php artisan websockets:serve
     mysql:
```

**Production note:** this repo has no deploy step (`bitbucket-pipelines.yml` only builds
and tests — there's no CD stage to a live server). On the actual production host, someone
needs to keep `php artisan websockets:serve` running persistently (a `supervisor` or
`systemd` unit is the standard approach) and put a reverse proxy in front of it for
`wss://`. That's an infrastructure change outside this repo and wasn't done here.

---

## Files changed

- `app/Http/Controllers/Profile/ChatController.php`
- `app/Events/MessageSent.php`
- `resources/js/bootstrap/echo.js`
- `resources/js/Pages/Profile/ChatBox.vue`
- `vite.config.js`
- `.env.example`
- `.env.pipelines`
- `docker-compose.yml`

## Not fixed here (separate, pre-existing issue found during investigation)

`.env.example` has real-looking committed secrets unrelated to broadcasting — a live
AWS access key/secret, a production DB password, and an SMTP password. These predate this
fix and are a separate security issue (credentials committed to git); flagged to the user
separately, not touched as part of this change.
