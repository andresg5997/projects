const express = require('express');
const bodyParser = require('body-parser');
const cors = require('./cors');

const Feedbacks = require('../models/feedbacks');

const feedbackRouter = express.Router();

feedbackRouter.route('/')
.options(cors.corsWithOptions, (req, res) => { res.statusCode = 200; })
.get(cors.cors, (req, res, next) => {
	Feedbacks.find(req.query)
	.then((feedbacks) => {
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(feedbacks);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.post(cors.corsWithOptions, (req, res, next) => {
	console.log(req.body);
	Feedbacks.create(req.body)
	.then((feedback) => {
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(feedback);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

module.exports = feedbackRouter;