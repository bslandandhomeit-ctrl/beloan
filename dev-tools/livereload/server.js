const path = require('path');
const chokidar = require('chokidar');
const { WebSocketServer } = require('ws');

const PORT = 3030;
const APP_ROOT = path.resolve(__dirname, '..', '..', 'html');

const wss = new WebSocketServer({ port: PORT });
const clients = new Set();

wss.on('connection', (ws) => {
    clients.add(ws);
    ws.on('close', () => clients.delete(ws));
});

function notifyReload(changedPath) {
    console.log(`[livereload] changed: ${changedPath} -> reloading ${clients.size} client(s)`);
    for (const ws of clients) {
        if (ws.readyState === ws.OPEN) ws.send('reload');
    }
}

const watcher = chokidar.watch(
    [
        path.join(APP_ROOT, 'app'),
        path.join(APP_ROOT, 'resources'),
        path.join(APP_ROOT, 'public'),
    ],
    { ignoreInitial: true }
);

watcher.on('all', (event, changedPath) => notifyReload(changedPath));

console.log(`[livereload] watching ${APP_ROOT}\\{app,resources,public}`);
console.log(`[livereload] websocket listening on ws://localhost:${PORT}`);
