const express = require('express');
const bodyParser = require('body-parser');

const leaderRouter = express.Router();

leaderRouter.route('/')
.all((req, res, next) => {
	res.statusCode = 200;
	res.setHeader('Content-Type', 'text/plain');
	next();
})
.get((req, res, next) => {
	res.end('Returning all the leaders!');
})
.post((req, res, next) => {
	res.end('Creating leader: ' + req.body.name + ' with details: ' + req.body.description);
})
.put((req, res, next) => {
	res.statusCode = 403;
	res.end('PUT not supported in /leaders');
})
.delete((req, res, next) => {
	res.end('Deleting all leaders');
});

leaderRouter.route('/:leaderId')
.get((req, res, next) => {
	res.end('Returning the leader: ' + req.params.leaderId + '!');
})
.post((req, res, next) => {
	res.statusCode = 403;
	res.end('POST not supported in /leaders/' + req.params.leaderId);
})
.put((req, res, next) => {
	res.write('Updating leader: ' + req.params.leaderId + '\n');
	res.end('Name: ' + req.body.name + ' with details: ' + req.body.description);
})
.delete((req, res, next) => {
	res.end('Deleting the leader: ' + req.params.leaderId);
});

module.exports = leaderRouter;