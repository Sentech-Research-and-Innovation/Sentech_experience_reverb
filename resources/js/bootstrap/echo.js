import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

const echo = new Echo({
  broadcaster: "pusher",
  key: import.meta.env.VITE_PUSHER_APP_KEY || "somekey",
  wsHost: window.location.hostname, // e.g. www.sentechxperience.co.za
  wsPort: 6001, // Laravel WebSockets default port
  wssPort: 6001, // secure WebSocket
  forceTLS: true,
  disableStats: true,
  enabledTransports: ["ws", "wss"],
  cluster: "mt1",
  authEndpoint: "/broadcasting/auth",
  auth: {
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content"),
    },
  },
});

export default echo;
