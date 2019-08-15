const express = require('express');
const bodyParser = require('body-parser');
const authenticate = require('../authenticate');
const cors = require('./cors');

const Favorite = require('../models/favorites');

const favoriteRouter = express.Router();

favoriteRouter.route('/')
.get(cors.cors, authenticate.verifyUser, (req, res, next) => {
	Favorite.findOne({user: req.user._id})
	.populate('favorites')
	.populate('user')
	.then((favorites) => {
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(favorites);
	})
	.catch((err) => { next(err) });
})
.post(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
	Favorite.findOne({user: req.user._id})
	.then((favorite) => {
		if(favorite != null){
			for (var i = req.body.length - 1; i >= 0; i--) {
				favorite.favorites.push(req.body[i]._id)
			}
			favorite.save()
			.then((favorite) => {
				res.statusCode = 200;
				res.setHeader('Content-Type', 'application/json');
				res.json(favorite);
			}, (err) => { next(err) })
			.catch((err) => { next(err) });
		}
		else {
			Favorite.create({
				user: req.user._id,
				favorites: []
			})
			.then((favorite) => {
				for (var i = req.body.length - 1; i >= 0; i--) {
					favorite.favorites.push(req.body[i]._id)
				}
				favorite.save()
				.then((favorite) => {
					console.log('Favorite created: ', favorite);
					res.statusCode = 200;
					res.setHeader('Content-Type', 'application/json');
					res.json(favorite);
				}, (err) => { next(err) })
				.catch((err) => { next(err) });
			})
			.catch((err) => { next(err) });
		}
	})
	.catch((err) => { next(err) });
})
.delete(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
	Favorite.findOne({user: req.user._id})
	.then((favorite) => {
		if(favorite != null){
			favorite.remove();
			favorite.save()
			.then((favorite) => {
				res.statusCode = 200;
				res.setHeader('Content-Type', 'application/json');
				res.json({status: true, message: "All favorites deleted!"});
			})
			.catch((err) => { next(err) });
		}
		else {
			res.statusCode = 404;
			res.end('You don\'t have any favorites!');
		}
	})
	.catch((err) => { next(err) });
});

favoriteRouter.route('/:dishId')
.get(cors.cors, authenticate.verifyUser, (req, res, next) => {
	Favorite.findOne({user: req.user._id})
	.then((favorite) => {
		if(!favorite) {
			res.statusCode = 200;
			res.setHeader('Content-Type', 'application/json');
			return res.json({exists: false, favorites: favorites});
		}
		else {
			if(favorite.favorites.indexOf(req.params.dishId) < 0){
				res.statusCode = 200;
				res.setHeader('Content-Type', 'application/json');
				return res.json({exists: false, favorites: favorites});
			}
			else{
				res.statusCode = 200;
				res.setHeader('Content-Type', 'application/json');
				return res.json({exists: true, favorites: favorites});
			}
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.post(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
	Favorite.findOne({user: req.user._id})
	.then((favorite) => {
		if(favorite != null){
			favorite.favorites.push(req.params.dishId)
			favorite.save()
			.then((favorite) => {
				res.statusCode = 200;
				res.setHeader('Content-Type', 'application/json');
				res.json(favorite);
			})
			.catch((err) => { next(err) });
		}
		else {
			Favorite.create({
				user: req.user._id,
				favorites: [{
					_id: req.params.dishId
				}]
			})
			.then((favorite) => {
				console.log('Favorite created', favorite);
				res.statusCode = 200;
				res.setHeader('Content-Type', 'application/json');
				res.json(favorite);
			})
			.catch((err) => { next(err) });
		}
	})
	.catch((err) => { next(err) });
})
.delete(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
	Favorite.findOne({user: req.user._id})
	.then((favorite) => {
		if(favorite != null){
			if(!favorite.favorite.indexOf(req.params.dishId) < 0) {
				res.statusCode = 404;
				res.end('You don\'t have the favorite ' + req.params.dishId + '!');
			}
			else {
				favorite.favorites.splice(favorite.favorite.indexOf(req.params.dishId), 1);
				favorite.save()
				.then((favorite) => {
					res.statusCode = 200;
					res.setHeader('Content-Type', 'application/json');
					res.json({status: true, message: "Favorite " + req.params.dishId + " deleted!"});
				})
				.catch((err) => { next(err) });
			}
		}
		else {
			res.statusCode = 404;
			res.end('You don\'t have any favorites!');
		}
	})
	.catch((err) => { next(err) });
});

module.exports = favoriteRouter;