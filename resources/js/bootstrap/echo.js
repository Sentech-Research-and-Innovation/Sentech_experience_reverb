import Echo from "laravel-echo";
import Pusher from "pusher-js";
import axios from "axios";

window.Pusher = Pusher;

const echo = new Echo({
  broadcaster: "pusher",
  key: process.env.VUE_APP_PUSHER_KEY || "somekey",
  wsHost: window.location.hostname, // This will be 'www.sentechxperience.co.za'
  wsPort: 443,
  wssPort: 443,
  forceTLS: true, // Important: true for HTTPS domain
  disableStats: true,
  enabledTransports: ["ws", "wss"],
  cluster: "mt1",
  authEndpoint: "/broadcasting/auth",
  auth: {
    headers: {
      Authorization: window.axios?.defaults?.headers?.common?.["Authorization"] || "",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "",
    },
  },
});

// Add connection event listeners for debugging
echo.connector.socket?.on('connect', () => {
  console.log('✅ Echo connected successfully to:', window.location.hostname);
});

echo.connector.socket?.on('disconnect', () => {
  console.log('❌ Echo disconnected from:', window.location.hostname);
});

echo.connector.socket?.on('error', (error) => {
  console.error('Echo connection error:', error);
});

export default echo;
