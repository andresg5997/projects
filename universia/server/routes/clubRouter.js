var express = require('express');
var clubRouter = express.Router();
var Clubs = require('../models/clubs');
var User = require('../models/users');
var authenticate = require('../authenticate');
var cors = require('./cors');

clubRouter.post('/join/:clubId', cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
	Clubs.findById(req.params.clubId)
	.then((club) => {
		if(club != null){
			if(req.user._player) {
				User.players.findById(req.user._player)
				.then((player) => {
					player.club = club._id;
					player.save()
					.then((player) => {
						res.statusCode = 200;
						res.setHeader('Content-Type', 'application/json');
						res.json(player);
					}, (err) => { next(err) })
					.catch((err) => { next(err) });
				}, (err) => { next(err) })
				.catch((err) => { next(err) });
			} else if(req.user._coach) {
				User.coaches.findById(req.user._coach)
				.then((coach) => {
					coach.club = club._id;
					coach.save()
					.then((coach) => {
						res.statusCode = 200;
						res.setHeader('Content-Type', 'application/json');
						res.json(coach);
					}, (err) => { next(err) })
					.catch((err) => { next(err) });
				}, (err) => { next(err) })
				.catch((err) => { next(err) });
			} else if(req.user._regular) {
				var err = new Error('Regular users can\'t join a club!');
				return next(err);
			}
		} else {
			res.statusCode = 404;
			res.end('Club ' + req.params.clubId + ' not found!');
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

clubRouter.get('/members/:clubId/', cors.cors, (req, res, next) => {
	User.players.find({club: req.params.clubId})
	.populate('name')
	.then((players) => {
		User.coaches.findOne({club: req.params.clubId})
		.populate('name')
		.then((coach) => {
			res.statusCode = 200;
			res.setHeader('Content-Type', 'application/json');
			res.json({players: players, coach: coach});
		}, (err) => { next(err) })
		.catch((err) => { next(err) });
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

clubRouter.route('/')
.get(cors.cors, (req, res, next) => {
	Clubs.find({})
	.populate('leader')
	.then((clubs) => {
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(clubs);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.post(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
	if(req.user._regular){
		var err = new Error('Only players and coaches can create Clubs!');
		err.status = 403;
		return next(err);
	}
	else {
		Clubs.create({
			name: req.body.name,
			avatar: req.body.avatar,
			sport: req.body.sport,
			leader: req.user._id
		})
		.then((club) => {
			res.statusCode = 200;
			res.setHeader('Content-Type', 'application/json');
			res.json(club);
		}, (err) => { next(err) })
		.catch((err) => { next(err) });
	}
})
.put(cors.corsWithOptions, (req, res, next) => {
	res.statusCode = 403;
	res.end('PUT not supported in /clubs');
})
.delete(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	Clubs.remove({})
	.then((resp) => {
		console.log('All clubs deleted');
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(resp);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

clubRouter.route('/:clubId')
.get(cors.cors, (req, res, next) => {
	Clubs.findById(req.params.clubId)
	.populate('leader')
	.then((club) => {
		if(club != null){
			res.statusCode = 200;
			res.setHeader('Content-Type', 'application/json');
			res.json(club);
		}
		else {
			 var err = new Error('Club ' + req.params.clubId + ' not found!');
			 err.status = 404;
			 return next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.post(cors.corsWithOptions, (req, res, next) => {
	res.statusCode = 403;
	res.end('POST not supported in /clubs/' + req.param.clubId);
})
.put(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
	Clubs.findById(req.params.clubId)
	.then((club) => {
		if(club != null) {
			if(club.leader.toString() == req.user._id.toString()){
				if(req.body.name)
					club.name = req.body.name;
				if(req.body.avatar)
					club.avatar = req.body.avatar;
				if(req.body.leader)
					club.leader = req.body.leader;
				if(req.body.sport)
					club.sport = req.body.sport;
				club.save()
				.then((club) => {
					res.statusCode = 200;
					res.setHeader('Content-Type', 'application/json');
					res.json(club);
				}, (err) => { next(err) })
				.catch((err) => { next(err) });
			}
			else {
				var err = new Error('You are not the leader of the team!');
				err.status = 403;
				return next(err);
			}
		}
		else {
			 var err = new Error('Club ' + req.params.clubId + ' not found!');
			 err.status = 404;
			 return next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.delete(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
	Clubs.findById(req.params.clubId)
	.then((club) => {
		if(club != null) {
			if(club.leader.toString() == req.user._id.toString()){
				club.remove()
				.then((resp) => {
					res.statusCode = 200;
					res.setHeader('Content-Type', 'application/json');
					res.json(resp);
				}, (err) => { next(err) })
				.catch((err) => { next(err) });
			}
			else {
				var err = new Error('You are not the leader of the team!');
				err.status = 403;
				return next(err);
			}
		}
		else {
			 var err = new Error('Club ' + req.params.clubId + ' not found!');
			 err.status = 404;
			 return next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

module.exports = clubRouter;