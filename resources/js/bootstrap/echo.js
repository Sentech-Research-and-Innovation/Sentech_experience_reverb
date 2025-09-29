// echo.js
import Echo from "laravel-echo";
import Pusher from "pusher-js";
import axios from "axios";

window.Pusher = Pusher;

const echo = new Echo({
  broadcaster: "pusher",
  key: process.env.VUE_APP_PUSHER_KEY || "somekey", // must match your websockets.php config
  wsHost: "13.247.190.223",  // your EC2 public IP or domain
  wsPort: 6001,
  wssPort: 6001,
  forceTLS: false,            // true only if you set up SSL certs on :6001
  disableStats: true,
  enabledTransports: ["ws", "wss"], // don’t let it try xhr/polling
  cluster: "mt1",             // 👈 ADD this dummy cluster so Pusher stops complaining
  authEndpoint: "/broadcasting/auth", // Laravel default
  auth: {
    headers: {
      Authorization: window.axios.defaults.headers.common["Authorization"] || "",
    },
  },
});

export default echo;
