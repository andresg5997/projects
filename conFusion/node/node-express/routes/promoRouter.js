const express = require('express');
const bodyParser = require('body-parser');

const promoRouter = express.Router();

promoRouter.route('/')
.all((req, res, next) => {
	res.statusCode = 200;
	res.setHeader('Content-Type', 'text/plain');
	next();
})
.get((req, res, next) => {
	res.end('Returning all the promotions!');
})
.post((req, res, next) => {
	res.end('Creating promotion: ' + req.body.name + ' with details: ' + req.body.description);
})
.put((req, res, next) => {
	res.statusCode = 403;
	res.end('PUT not supported in /promotions');
})
.delete((req, res, next) => {
	res.end('Deleting all promotions');
});

promoRouter.route('/:promoId')
.get((req, res, next) => {
	res.end('Returning the promotion: ' + req.params.promoId + '!');
})
.post((req, res, next) => {
	res.statusCode = 403;
	res.end('POST not supported in /promotions/' + req.params.promoId);
})
.put((req, res, next) => {
	res.write('Updating promotion: ' + req.params.promoId + '\n');
	res.end('Name: ' + req.body.name + ' with details: ' + req.body.description);
})
.delete((req, res, next) => {
	res.end('Deleting the promotion: ' + req.params.promoId);
});

module.exports = promoRouter;