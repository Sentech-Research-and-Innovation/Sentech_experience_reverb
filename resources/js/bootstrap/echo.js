import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

const useTLS = (import.meta.env.VITE_REVERB_SCHEME || "https") === "https";
const wsPort = Number(import.meta.env.VITE_REVERB_PORT) || 6001;

const echo = new Echo({
  broadcaster: "reverb",
  key: import.meta.env.VITE_REVERB_APP_KEY || "somekey",
  wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
  wsPort: wsPort,
  wssPort: wsPort,
  forceTLS: useTLS,
  enabledTransports: useTLS ? ["ws", "wss"] : ["ws"],
  authEndpoint: "/broadcasting/auth",
  auth: {
    headers: {
      "X-CSRF-TOKEN": window.Laravel?.csrfToken,
    },
  },
});

export default echo;
