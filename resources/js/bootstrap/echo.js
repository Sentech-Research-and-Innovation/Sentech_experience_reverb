import Peer from "simple-peer";
import Echo from "laravel-echo";
import Pusher from "pusher-js";
import BaseApi from "@/api/axios";

window.Pusher = Pusher;

const echo = new Echo({
  broadcaster: "pusher",
  key: "app-key",   // from your .env PUSHER_APP_KEY
  wsHost: "13.247.190.223", // or domain
  wsPort: 6001,
  forceTLS: false,
  disableStats: true,
});

// Assume we know current userId & targetUserId
let peer;

async function startCall(isInitiator, targetUserId) {
  peer = new Peer({
    initiator: isInitiator,
    trickle: true,
    config: {
      iceServers: [
        { urls: "stun:13.247.190.223:3478" },
        { 
          urls: "turn:13.247.190.223:3478", 
          username: "user",
          credential: "yourSuperSecretKey"
        },
      ],
    },
  });

  // local stream
  const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
  peer.addStream(stream);

  peer.on("signal", async (data) => {
    if (data.type === "offer") {
      await BaseApi.post("/api/call/offer", {
        from: window.userId,
        to: targetUserId,
        offer: data,
      });
    } else if (data.type === "answer") {
      await BaseApi.post("/api/call/answer", {
        from: window.userId,
        to: targetUserId,
        answer: data,
      });
    } else {
      // ICE candidate
      await BaseApi.post("/api/call/candidate", {
        from: window.userId,
        to: targetUserId,
        candidate: data,
      });
    }
  });

  peer.on("stream", (remoteStream) => {
    document.querySelector("#remoteVideo").srcObject = remoteStream;
  });

  // listen for signals from Laravel Echo
  echo.private(`calls.${window.userId}`)
    .listen("CallOfferEvent", (e) => {
      peer.signal(e.data.offer);
    })
    .listen("CallAnswerEvent", (e) => {
      peer.signal(e.data.answer);
    })
    .listen("CallCandidateEvent", (e) => {
      peer.signal(e.data.candidate);
    });
}
