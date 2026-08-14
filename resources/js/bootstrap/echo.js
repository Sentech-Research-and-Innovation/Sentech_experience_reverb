import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

const useTLS = (import.meta.env.VITE_PUSHER_SCHEME || "https") === "https";
const wsPort = Number(import.meta.env.VITE_PUSHER_PORT) || 6001;

const echo = new Echo({
  broadcaster: "pusher",
  key: import.meta.env.VITE_PUSHER_APP_KEY || "somekey",
  wsHost: import.meta.env.VITE_PUSHER_HOST || window.location.hostname,
  wsPort: wsPort, // Laravel WebSockets default port
  wssPort: wsPort,
  forceTLS: useTLS,
  disableStats: true,
  enabledTransports: useTLS ? ["ws", "wss"] : ["ws"],
  authEndpoint: "/broadcasting/auth",
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
  auth: {
    headers: {
      "X-CSRF-TOKEN": window.Laravel?.csrfToken,
    },
  },
});

export default echo;
