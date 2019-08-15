var express = require('express');
var achievementRouter = express.Router();
var Achievements = require('../models/achievements');
var User = require('../models/users');
var authenticate = require('../authenticate');

achievementRouter.route('/')
.get(authenticate.verifyUser, (req, res, next) => {
	Achievements.find({player: req.user._id})
	.populate('images')
	.populate('player')
	.then((achievements) => {
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(achievements);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.post(authenticate.verifyUser, (req, res, next) => {
	if(req.user._player) {
		Achievements.create({
			name: req.body.name,
			date: req.body.date,
			place: req.body.place,
			details: req.body.details,
			player: req.user._id,
			images: req.body.images
		})
		.then((achievement) => {
			res.statusCode = 200;
			res.setHeader('Content-Type', 'application/json');
			res.json(achievement);
		}, (err) => { next(err) })
		.catch((err) => { next(err) });
	}
	else {
		var err = new Error('You are not a player!');
		err.status = 403;
		next(err);
	}	
})
.put(authenticate.verifyUser, (req, res, next) => {
	res.statusCode = 403;
	res.end('PUT not supported in /achievements');
})
.delete(authenticate.verifyUser, (req, res, next) => {
	if(req.user._player) {
		Achievements.find({player: req.user._id})
		.then((achievements) => {
			achievements.remove()
			.then((resp) => {
				res.statusCode = 200;
				res.setHeader('Content-Type', 'application/json');
				res.json(resp);
			}, (err) => { next(err) })
			.catch((err) => { next(err) });
		}, (err) => { next(err) })
		.catch((err) => { next(err) });
	}
	else {
		var err = new Error('You are not a player!');
		err.status = 403;
		next(err);
	}	
})

achievementRouter.route('/:achievementId')
.get(authenticate.verifyUser, (req, res, next) => {
	Achievements.findById(req.params.achievementId)
	.then((achievement) => {
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(achievement);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.post(authenticate.verifyUser, (req, res, next) => {
	res.statusCode = 403;
	res.end('POST not supported in /achievements/' + req.params.achievementId);
})
.put(authenticate.verifyUser, (req, res, next) => {
	Achievements.findById(req.params.achievementId)
	.then((achievement) => {
		if(achievement){
			if(achievement.player == req.user._id){
				if(req.body.name)
					achievement.name = req.body.name;
				if(req.body.date)
					achievement.date = req.body.date;
				if(req.body.place)
					achievement.place = req.body.place;
				if(req.body.details)
					achievement.details = req.body.details;
				achievement.save()
				.then((achievement) => {
					res.statusCode = 200;
					res.setHeader('Content-Type', 'application/json');
					res.json(achievement);
				}, (err) => { next(err) })
				.catch((err) => { next(err) });
			}
			else {
				var err = new Error('You are not the owner of the achievement!');
				err.status = 403;
				next(err);
			}
		}
		else {
			var err = new Error('Achievement ' + req.params.achievementId + ' not found!');
			err.status = 404;
			next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.delete(authenticate.verifyUser, (req, res, next) => {
	Achievements.findById(req.params.achievementId)
	.then((achievement) => {
		if(achievement){
			if(achievement.player == req.user._id){
				achievement.remove()
				.then((resp) => {
					res.statusCode = 200;
					res.setHeader('Content-Type', 'application/json');
					res.json(resp);
				}, (err) => { next(err) })
				.catch((err) => { next(err) });
			}
			else {
				var err = new Error('You are not the owner of the achievement!');
				err.status = 403;
				next(err);
			}
		}
		else {
			var err = new Error('Achievement ' + req.params.achievementId + ' not found!');
			err.status = 404;
			next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

module.exports = achievementRouter;