var express = require('express');
var http = require('http');
var bodyParser = require('body-parser');

const hostname = 'localhost';
const port = 3000;

const morgan = require('morgan');

const app = express();

app.use(morgan('dev'));

app.use(bodyParser.json());

app.use(express.static(__dirname + '/public'));

const dishRouter = require('./routes/dishRouter');
app.use('/dishes', dishRouter);

const promoRouter = require('./routes/promoRouter');
app.use('/promotions', promoRouter);

const leaderRouter = require('./routes/leaderRouter');
app.use('/leaders', leaderRouter);

app.use((req, res, next) => {
	console.log('There\'s a new connection to ' + req.url);
	res.statusCode = 200;
	res.setHeader('Content-Type', 'text/html');
	res.end('<html><body><h1>WRONG</h1></body></html>');
});

const server = http.createServer(app);

server.listen(port, hostname, () => {
	console.log(`Running server in http://${hostname}:${port}`)
});