const express = require('express');
const bodyParser = require('body-parser');
const authenticate = require('../authenticate');
const cors = require('./cors');

const Leaders = require('../models/leaders');

const leaderRouter = express.Router();

leaderRouter.route('/')
.get(cors.cors, (req, res, next) => {
	Leaders.find(req.query)
	.then((leaders) => {
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(leaders);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.post(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	Leaders.create(req.body)
	.then((leader) => {
		console.log('Leader created!');
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(leader);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.put(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	res.statusCode = 403;
	res.end('PUT not supported in /leaders');
})
.delete(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	Leaders.remove({})
	.then((resp) => {
		console.log('All leaders removed!');
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(resp);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

leaderRouter.route('/:leaderId')
.get(cors.cors, (req, res, next) => {
	Leaders.findById(req.params.leaderId)
	.then((leader) => {
		if(leader != null){
			res.statusCode = 200;
			res.setHeader('Content-Type', 'application/json');
			res.json(leader);
		} else {
			err = new Error('Leader ' + req.params.leaderId + ' not found!');
			err.status = 404;
			return next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.post(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	res.statusCode = 403;
	res.end('POST not supported in /leaders/' + req.params.leaderId);
})
.put(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	Leaders.findByIdAndUpdate(req.params.leaderId, {
		$set: req.body
	}, { new: true })
	.then((leader) => {
		if(leader != null){
			res.statusCode = 200;
			res.setHeader('Content-Type', 'application/json');
			res.json(leader);
		} else {
			err = new Error('Leader ' + req.params.leaderId + ' not found!');
			err.status = 404;
			return next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.delete(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	Leaders.findByIdAndRemove(req.params.leaderId)
	.then((leader) => {
		if(leader != null){
			res.statusCode = 200;
			res.setHeader('Content-Type', 'application/json');
			res.json(leader);
		} else {
			err = new Error('Leader ' + req.params.leaderId + ' not found!');
			err.status = 404;
			return next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

module.exports = leaderRouter;