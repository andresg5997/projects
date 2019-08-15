const express = require('express');
const bodyParser = require('body-parser');

const dishRouter = express.Router();

dishRouter.route('/')
.all((req, res, next) => {
	res.statusCode = 200;
	res.setHeader('Content-Type', 'text/plain');
	next();
})
.get((req, res, next) => {
	res.end('Returning all the dishes!');
})
.post((req, res, next) => {
	res.end('Creating dish: ' + req.body.name + ' with details: ' + req.body.description);
})
.put((req, res, next) => {
	res.statusCode = 403;
	res.end('PUT not supported in /dishes');
})
.delete((req, res, next) => {
	res.end('Deleting all dishes');
});

dishRouter.route('/:dishId')
.get((req, res, next) => {
	res.end('Returning the dish: ' + req.params.dishId + '!');
})
.post((req, res, next) => {
	res.statusCode = 403;
	res.end('POST not supported in /dishes/' + req.params.dishId);
})
.put((req, res, next) => {
	res.write('Updating dish: ' + req.params.dishId + '\n');
	res.end('Name: ' + req.body.name + ' with details: ' + req.body.description);
})
.delete((req, res, next) => {
	res.end('Deleting the dish: ' + req.params.dishId);
});

module.exports = dishRouter;