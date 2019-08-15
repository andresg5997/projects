var express = require('express');
var bodyParser = require('body-parser');
var User = require('../models/user');
var passport = require('passport');
var router = express.Router();
var authenticate = require('../authenticate');
const cors = require('./cors');

router.use(bodyParser.json());

router.options('*', cors.corsWithOptions, (req, res) => { res.statusCode = 200; });

/* GET users listing. */
router.get('/', cors.cors, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	User.find({})
	.then((users) => {
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json(users);
	}, (err) => { next(err) })
	.catch((err) => { next(err) });
});

router.post('/signup', cors.corsWithOptions, (req, res, next) => {
	User.register(new User({username: req.body.username}), req.body.password, (err, user) => {
		if(err) {
			res.statusCode = 500;
			res.setHeader('Content-Type', 'application/json');
			res.json({err: err});
		}
		else {
			passport.authenticate('local')(req, res, () => {
				if (req.body.firstname){
					user.firstname = req.body.firstname;
				}
				if (req.body.lastname){
					user.lastname = req.body.lastname;
				}
				user.save((err, user) => {
					if(err){
						res.statusCode = 500;
						res.setHeader('Content-Type', 'application/json');
						res.json({err: err});
					}
					else{
						res.statusCode = 200;
						res.setHeader('Content-Type', 'application/json');
						res.json({success: true, status:'Registration Succesful!'});
					}
				});
				
			});
		}
	});
});

router.post('/login', cors.corsWithOptions, (req, res) => {

	passport.authenticate('local', (err, user, info) => {
		if(err)
			return next(err);

		if(!user) {
			res.statusCode = 401;
			res.setHeader('Content-Type', 'application/json');
			res.json({success: false, status: 'Login Unsuccessful', err: info});
		}

		req.logIn(user, (err) => {
			if(err) {
				res.statusCode = 401;
				res.setHeader('Content-Type', 'application/json');
				res.json({success: false, status: 'Login Unsuccessful', err: err});
			}

			var token = authenticate.getToken({_id: req.user._id});
			res.statusCode = 200;
			res.setHeader('Content-Type', 'application/json');
			res.json({success: true, token: token, status: 'Login successful!'});
		});
	})(req, res, next);
});

router.get('/logout', cors.corsWithOptions, (req, res, next) => {
	if(req.session) {
		req.session.destroy();
		res.clearCookie('session-id');
		res.redirect('/');
	}
	else {
		var err = new Error('You are not authenticated!');
		err.status = 403;
		next(err);
	}
});

router.get('/facebook/token', passport.authenticate('facebook-token'), (req, res) => {
	if(req.user){
		var token = authenticate.getToken({_id: req.user._id});
		res.statusCode = 200;
		res.setHeader('Content-Type', 'application/json');
		res.json({success: true, token: token, status: 'You are successfully logged in!'});
	}
});

router.get('/checkJWT', cors.corsWithOptions, (req, res) => {
	passport.authenticate('jwt', {session: false}, (err, user, info) => {
		if(err)
			return next(err);
		if(!user) {
			res.statusCode = 401;
			res.setHeader('Content-Type', 'application/json');
			return res.json({status: 'JWT invalid!', success: false, err: info});
		}
		else{
			res.statusCode = 200;
			res.setHeader('Content-Type', 'application/json');
			return res.json({status: 'JWT valid!', success: true, user: user});
		}
	})(req, res, next);
});

module.exports = router;