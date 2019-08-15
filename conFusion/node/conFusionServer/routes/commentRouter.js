const express = require('express');
const bodyParser = require('body-parser');
const cors = require('./cors');

const Comments = require('../models/comments');

const commentRouter = express.Router();

commentRouter.route('/')
.options(cors.corsWithOptions, (req, res) => { res.statusCode = 200; })
.get(cors.cors, (req, res, next) => {
	Comments.find(req.query)
	.then((comments) => {
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(comments);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.post(cors.corsWithOptions, (req, res, next) => {
	Comments.create(req.body)
	.then((comment) => {
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(comment);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

commentRouter.route('/:dishShortId')
.options(cors.corsWithOptions, (req, res) => { res.statusCode = 200; })
.get(cors.cors, (req, res, next) => {
	Comments.find({dishId: parseInt(req.params.dishShortId, 10)})
	.then((comments) => {
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(comments);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

module.exports = commentRouter;