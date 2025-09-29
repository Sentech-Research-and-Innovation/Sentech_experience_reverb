// echo.js
import Echo from "laravel-echo";
import Pusher from "pusher-js";
import axios from "axios";

window.Pusher = Pusher;

const echo = new Echo({
  broadcaster: "pusher",
  key: process.env.VUE_APP_PUSHER_KEY || "somekey",
  wsHost: "13.247.190.223", // your EC2 domain/IP
  wsPort: 6001,
  forceTLS: false,
  encrypted: false,
  disableStats: true,
  auth: {
    headers: {
      Authorization: window.axios.defaults.headers.common["Authorization"] || "",
    },
  },
});

export default echo;
