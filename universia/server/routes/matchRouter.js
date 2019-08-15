var express = require('express');
var matchRouter = express.Router();
var Matches = require('../models/matches');
var Clubs = require('../models/clubs');
var User = require('../models/users');
var authenticate = require('../authenticate');
var cors = require('./cors');

matchRouter.get('/club/:clubId', cors.cors, authenticate.verifyUser, (req, res, next) => {
	Clubs.findById(req.params.clubId)
	.then((club) => {
		if(club) {
			Matches.find({homeClub: req.params.clubId})
			.then((homeClub) => {
				Matches.find({visitantClub: req.params.clubId})
				.then((visitantClub) => {
					res.statusCode = 200;
					res.setHeader('Content-Type', 'application/json');
					res.json({home: homeClub, visitant: visitantClub});
				}, (err) => { next(err) })
				.catch((err) => { next(err) });
			}, (err) => { next(err) })
			.catch((err) => { next(err) });
		}
		else {
			var err = new Error('Club ' + req.params.clubId + ' not found!');
			err.status = 404;
			next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

matchRouter.route('/')
.get(cors.cors, authenticate.verifyUser, (req, res, next) => {
	Matches.find({})
	.then((matches) => {
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(matches);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.post(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
	Clubs.findById(req.body.homeClub)
	.then((club) => {
		if(club.leader.toString() == req.user._id.toString()){
			Matches.create({
				result: req.body.result,
				sport: req.body.sport,
				homeClub: req.body.homeClub,
				visitantClub: req.body.visitantClub,
				place: req.body.place,
				date: req.body.date,
				details: req.body.details,
				images: req.body.images
			})
			.then((match) => {
				res.statusCode = 200;
				res.setHeader('Content-Type', 'application/json');
				res.json(match);
			}, (err) => { next(err) })
			.catch((err) => { next(err) });
		}
		else {
			var err = new Error('You are not the leader of the home club!');
			err.status = 403;
			next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.put(cors.corsWithOptions, (req, res, next) => {
	res.statusCode = 403;
	res.end('PUT not supported in /matches');
})
.delete(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	Matches.remove({})
	.then((resp) => {
		console.log('All matches deleted!');
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(resp);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

matchRouter.route('/:matchId')
.get(cors.cors, authenticate.verifyUser, (req, res, next) => {
	Matches.findById(req.params.matchId)
	.then((match) => {
		if(match){
			res.statusCode = 200;
			res.setHeader('Content-Type', 'application/json');
			res.json(match);
		}
		else {
			var err = new Error('Match ' + req.params.matchId + ' not found!');
			err.status = 404;
			next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.post(cors.corsWithOptions, (req, res, next) => {
	res.statusCode = 403;
	res.end('POST not supported in /matches/' + req.params.matchId);
})
.put(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
	Matches.findById(req.params.matchId)
	.then((match) => {
		if(match){
			Club.findById(match.homeClub)
			.then((club) => {
				if(club.leader.toString() == req.user._id.toString()){
					if(req.body.result)
						match.result = req.body.result;
					if(req.body.visitantClub)
						match.visitantClub = req.body.visitantClub;
					if(req.body.place)
						match.place = req.body.place;
					if(req.body.date)
						match.date = req.body.date;
					match.save()
					.then((match) => {
						res.statusCode = 200;
						res.setHeader('Content-Type', 'application/json');
						res.json(match);
					}, (err) => { next(err) })
					.catch((err) => { next(err) });
				}
				else {
					var err = new Error('You are not the leader of the home club of this match!');
					err.status = 403;
					next(err);
				}
			}, (err) => { next(err) })
			.catch((err) => { next(err) });
		}
		else {
			var err = new Error('Match ' + req.params.matchId + ' not found!');
			err.status = 404;
			next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})
.delete(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
	Matches.findById(req.params.matchId)
	.then((match) => {
		if(match){
			Club.findById(match.homeClub)
			.then((club) => {
				if(club.leader.toString() == req.user._id.toString()){
					match.remove()
					.then((resp) => {
						res.statusCode = 200;
						res.setHeader('Content-Type', 'application/json');
						res.json(resp);
					}, (err) => { next(err) })
					.catch((err) => { next(err) });
				}
				else {
					var err = new Error('You are not the leader of the home club of this match!');
					err.status = 403;
					next(err);
				}
			}, (err) => { next(err) })
			.catch((err) => { next(err) });
		}
		else {
			var err = new Error('Match ' + req.params.matchId + ' not found!');
			err.status = 404;
			next(err);
		}
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
})

module.exports = matchRouter;