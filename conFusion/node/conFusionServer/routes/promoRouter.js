const express = require('express');
const bodyParser = require('body-parser');
const authenticate = require('../authenticate');
const cors = require('./cors');

const Promotions = require('../models/promotions');

const promoRouter = express.Router();

promoRouter.route('/')
.get(cors.cors, (req, res, next) => {
	Promotions.find(req.query)
	.then((promotions) => {
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(promotions);
	}, (err)=> { next(err) })
	.catch((err) => { next(err) });
})
.post(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	Promotions.create(req.body)
	.then((promo) => {
		console.log('Promo created!');
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(promo);
	}, (err)=> { next(err) })
	.catch((err) => { next(err) });	
})
.put(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	res.statusCode = 403;
	res.end('PUT not supported in /promotions');
})
.delete(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	Promotions.remove({})
	.then((resp) => {
		console.log('All promotions removed!');
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(resp);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

promoRouter.route('/:promoId')
.get(cors.cors, (req, res, next) => {
	Promotions.findById(req.params.promoId)
	.then((promo) => {
		if(promo != null){
			res.statusCode = 200;
			res.setHeader('Content-Type', 'application/json');
			res.json(promo);
		} else {
			err = new Error('Promotion ' + req.params.promoId + ' not found!');
			err.status = 404;
			return next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.post(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	res.statusCode = 403;
	res.end('POST not supported in /promotions/' + req.params.promoId);
})
.put(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	Promotions.findByIdAndUpdate(req.params.promoId, {
		$set: req.body
	}, { new:true })
	.then((promo) => {
		if(promo != null){
			res.statusCode = 200;
			res.setHeader('Content-Type', 'application/json');
			res.json(promo);
		} else {
			err = new Error('Promotion ' + req.params.promoId + ' not found!');
			err.status = 404;
			return next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.delete(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	Promotions.findByIdAndRemove(req.params.promoId)
	.then((promo) => {
		if(promo != null){
			res.statusCode = 200;
			res.setHeader('Content-Type', 'application/json');
			res.json(promo);
		} else {
			err = new Error('Promotion ' + req.params.promoId + ' not found!');
			err.status = 404;
			return next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

module.exports = promoRouter;