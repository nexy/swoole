ws1 = new WebSocket('ws://localhost');
ws1.onmessage = function (e) { console.log('(1)', e.data); };

ws2 = new WebSocket('ws://localhost');
ws2.onmessage = function (e) { console.log('(2)', e.data); };

ws3 = new WebSocket('ws://localhost');
ws3.onmessage = function (e) { console.log('(3)', e.data); };

ws1.send('test message');
ws1.send('hi');
ws3.send('hello');