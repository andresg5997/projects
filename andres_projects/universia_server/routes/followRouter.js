var express = require('express');
var followRouter = express.Router();
var User = require('../models/users');
var Follows = require('../models/follows');
var authenticate = require('../authenticate');

followRouter.get('/following', authenticate.verifyUser, (req, res, next) => {
	Follows.find({user: req.user._id})
	.populate('following')
	.then((follows) => {
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(follows);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

followRouter.get('/followers/:playerId', (req, res, next) => {
	User.users.findById(req.params.playerId)
	.then((player) => {
		if(player != null){
			Follows.find({following: player._id})
			.populate({path: 'user', populate: {path: '_player', populate: {path: 'name'}}})
			.then((follows) => {
				res.statusCode = 200;
				res.setHeader('Content-Type', 'application/json');
				res.json(follows);
			}, (err) => { next(err) })
			.catch((err) => { next(err) });
		}
		else {
			res.statusCode = 404;
			res.end('Player ' + req.params.playerId + ' not found!');
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

followRouter.get('/following/:playerId', (req, res, next) => {
	User.users.findById(req.params.playerId)
	.then((player) => {
		if(player != null){
			Follows.find({user: player._id})
			.populate({path: 'following', populate: {path: '_player', populate: {path: 'name'}}})
			.then((follows) => {
				res.statusCode = 200;
				res.setHeader('Content-Type', 'application/json');
				res.json(follows);
			}, (err) => { next(err) })
			.catch((err) => { next(err) });
		}
		else {
			res.statusCode = 404;
			res.end('Player ' + req.params.playerId + ' not found!');
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

followRouter.route('/:playerId')
.get((req, res, next) => {
	User.users.findById(req.params.playerId)
	.then((player) => {
		if(player != null){
			Follows.find({following: player._id})
			.then((followers) => {
				Follows.find({user: player._id})
				.then((following) => {
					res.statusCode = 200;
					res.setHeader('Content-Type', 'application/json');
					res.json({followers: followers, following: following});
				}, (err) => { next(err) })
				.catch((err) => { next(err) });
			}, (err) => { next(err) })
			.catch((err) => { next(err) });
		}
		else {
			res.statusCode = 404;
			res.end('Player ' + req.params.playerId + ' not found!');
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.post(authenticate.verifyUser, (req, res, next) => {
	Follows.findOne({user: req.user._id, following: req.params.playerId})
	.then((follow) => {
		if(!follow){
			User.users.findById(req.params.playerId)
			.then((player) => {
				console.log(req.params.playerId);
				if(player != null){
					Follows.create({
						user: req.user._id,
						following: req.params.playerId
					})
					.then((follow) => {
						res.statusCode = 200;
						res.setHeader('Content-Type', 'application/json');
						res.json(follow);
					}, (err) => { next(err) })
					.catch((err) => { next(err) });
				}
				else {
					res.statusCode = 404;
					res.end('Player ' + req.params.playerId + ' not found!');
				}
			}, (err) => { next(err) })
			.catch((err) => { next(err) });
		}
		else {
			var err = new Error('You already follow this player!');
			err.status = 403;
			return next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.put((req, res, next) => {
	res.statusCode = 403;
	res.end('PUT not supported in /follow/' + req.params.playerId);
})
.delete(authenticate.verifyUser, (req, res, next) => {
	Follows.findOne({user: req.user._id, following: req.params.playerId})
	.then((follow) => {
		if(follow) {
			User.users.findById(req.params.playerId)
			.then((player) => {
				if(player != null){
					Follows.findOne({
						user: req.user._id,
						following: req.params.playerId
					})
					.then((follow) => {
						follow.remove()
						.then((follow) => {
							res.statusCode = 200;
							res.setHeader('Content-Type', 'application/json');
							res.json(follow);
						}, (err) => { next(err) })
						.catch((err) => { next(err) });
					}, (err) => { next(err) })
					.catch((err) => { next(err) });
				}
				else {
					res.statusCode = 404;
					res.end('Player ' + req.params.playerId + ' not found!');
				}
			}, (err) => { next(err) })
			.catch((err) => { next(err) });
		}
		else {
			var err = new Error('You are not following this player!');
			err.status = 403;
			return next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

module.exports = followRouter;