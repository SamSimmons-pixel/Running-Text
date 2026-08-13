import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

const host = import.meta.env.VITE_REVERB_HOST || window.location.hostname;
const key = import.meta.env.VITE_REVERB_APP_KEY || 'jcwiojksa5pky1r6tnch';
const port = import.meta.env.VITE_REVERB_PORT || 8080;
const scheme = import.meta.env.VITE_REVERB_SCHEME || 'http';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: key,
    wsHost: host,
    wsPort: port,
    wssPort: port,
    forceTLS: scheme === 'https',
    enabledTransports: ['ws', 'wss'],
});

console.log('[ECHO DEBUG] Initialized Echo on host:', host, 'port:', port, window.Echo);
