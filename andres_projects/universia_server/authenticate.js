var passport = require('passport');
var LocalStrategy = require('passport-local').Strategy;
// var FacebookTokenStategy = require('passport-facebook-token');
var User = require('./models/users');
var JwtStrategy = require('passport-jwt').Strategy;
var ExtractJwt = require('passport-jwt').ExtractJwt;
var jwt = require('jsonwebtoken');

var config = require('./config');

exports.local = passport.use(new LocalStrategy(User.users.authenticate()));
passport.serializeUser(User.users.serializeUser());
passport.deserializeUser(User.users.deserializeUser());

exports.getToken = (user) => {
	return jwt.sign(user, config.secretKey, {expiresIn: 18000});
};

var opts = {};
opts.jwtFromRequest = ExtractJwt.fromAuthHeaderAsBearerToken();
opts.secretOrKey = config.secretKey;

exports.jwtPassport = passport.use(new JwtStrategy(opts, (jwt_payload, done) => {
	console.log("JWT payload: ", jwt_payload);
	User.users.findOne({_id: jwt_payload._id}, (err, user) => {
		if(err){
			return done(err, false);
		}
		else if(user){
			return done(null, user);
		}
		else {
			return done(null, false);
		}
	});
}));

exports.verifyUser = passport.authenticate('jwt', {session: false});

exports.verifyAdmin = (req, res, next) => {
	if(!req.user.admin){
		var err = new Error('You are not authorized to perform this action!');
		err.status = 403;
		next(err);
	}
	else{
		next();
	}
};

// exports.facebookPassport = passport.use(new FacebookTokenStategy({
// 		clientID: config.facebook.clientId,
// 		clientSecret: config.facebook.clientSecret,
// 	}, (accessToken, refreshToken, profile, done) => {
// 		console.log(profile);
// 		User.findOne({facebookId: profile.id}, (err, user) => {
// 			if(err) {
// 				return done(err, false);
// 			}
// 			if(!err && user != null) {
// 				return done(null, user);
// 			}
// 			else {
// 				user = new User({ username: profile.displayName });
// 				user.facebookId = profile.id;
// 				user.firstname = profile.name.givenName;
// 				user.lastname = profile.name.familyName;
// 				user.save((err, user) => {
// 					if(err)
// 						return done(err, false);
// 					else
// 						return done(null, user);
// 				})
// 			}
// 		});
// 	}
// ));