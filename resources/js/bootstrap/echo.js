import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY || process.env.MIX_PUSHER_APP_KEY,
    wsHost: window.location.hostname, // This will now be 'www.sentechxperience.co.za'
    wsPort: 6001,
    wssPort: 6001,
    forceTLS: true, // Changed to true since you're using HTTPS
    enabledTransports: ['ws', 'wss'],
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
    disableStats: true,
});

export default echo;
