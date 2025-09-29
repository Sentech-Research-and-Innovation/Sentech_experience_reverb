import Peer from 'simple-peer';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import axios from 'axios'

window.Pusher = Pusher;

const echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
  wsHost: import.meta.env.VITE_PUSHER_HOST ?? window.location.hostname,
  wsPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
  wssPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
  forceTLS: false,
  disableStats: true,
  enabledTransports: ['ws', 'wss'],
});

let peer;

async function startCall(isInitiator, targetUserId, onRemoteStream) {
  peer = new Peer({
    initiator: isInitiator,
    trickle: true,
    config: {
      iceServers: [
        { urls: 'stun:13.247.190.223:3478' },
        {
          urls: 'turn:13.247.190.223:3478',
          username: 'user',
          credential: 'yourSuperSecretKey',
        },
      ],
    },
  });

  // Local audio only
  const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
  stream.getTracks().forEach((track) => peer.addTrack(track, stream));

  // Emit signal to server
  peer.on('signal', async (data) => {
    if (data.type === 'offer') {
      await BaseApi.post('/api/call/offer', {
        from: window.userId,
        to: targetUserId,
        offer: data,
      });
    } else if (data.type === 'answer') {
      await BaseApi.post('/api/call/answer', {
        from: window.userId,
        to: targetUserId,
        answer: data,
      });
    } else {
      await BaseApi.post('/api/call/candidate', {
        from: window.userId,
        to: targetUserId,
        candidate: data,
      });
    }
  });

  peer.on('stream', (remoteStream) => {
    if (onRemoteStream) {
      onRemoteStream(remoteStream);
    }
  });

  peer.on('error', console.error);

  // Listen to incoming signals
  echo.private(`calls.${window.userId}`)
    .listen('CallOfferEvent', (e) => {
      peer.signal(e.offer);
    })
    .listen('CallAnswerEvent', (e) => {
      peer.signal(e.answer);
    })
    .listen('CallCandidateEvent', (e) => {
      peer.signal(e.candidate);
    });

  return peer;
}

export { startCall, echo };
