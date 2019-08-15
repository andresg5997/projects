var express = require('express');
var userRouter = express.Router();
var User = require('../models/users');
var authenticate = require('../authenticate');
var passport = require('passport');

userRouter.post('/login', (req, res) => {

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
      res.json({success: true, token: token, status: 'Login Successful!'});
    });
  })(req, res);
});

userRouter.post('/signup', (req, res, next) => {
  User.names.create({
    firstName: req.body.firstName,
    lastName: req.body.lastName
  })
  .then((name) => {
    if(req.body.userType == 'coach') {
      User.coaches.create({ name: name._id })
      .then((coach) => {
        User.users.register(new User.users({username: req.body.username, email: req.body.email}), req.body.password, (err, user) => {
          if(err){
            res.statusCode = 500;
            res.setHeader('Content-Type', 'application/json');
            res.json({err: err});
          }
          else {
            passport.authenticate('local')(req, res, () => {
              user._coach = coach._id;
              user.save((err, user) => {
                if(err){
                  res.statusCode = 500;
                  res.setHeader('Content-Type', 'application/json');
                  res.json({err: err});
                }
                else {
                  User.users.findById(user._id)
                  .populate({path: '_coach', populate: {path: 'name'}})
                  .then((user) => {
                    console.log('Coach created!');
                    res.statusCode = 200;
                    res.setHeader('Content-Type', 'application/json');
                    res.json(user);
                  }, (err) => { next(err) })
                  .catch((err) => { next(err) });
                }
              });
            });
          }
        });
      }, (err) => { next(err) })
      .catch((err) => { next(err) });
    }
    else if(req.body.userType == 'player') {
      User.players.create({ name: name._id })
      .then((player) => {
        User.users.register(new User.users({username: req.body.username, email: req.body.email}), req.body.password, (err, user) => {
          if(err){
            res.statusCode = 500;
            res.setHeader('Content-Type', 'application/json');
            res.json({err: err});
          }
          else {
            passport.authenticate('local')(req, res, () => {
              user._player = player._id;
              user.save((err, user) => {
                if(err){
                  res.statusCode = 500;
                  res.setHeader('Content-Type', 'application/json');
                  res.json({err: err});
                }
                else {
                  User.users.findById(user._id)
                  .populate({path: '_player', populate: {path: 'name'}})
                  .then((user) => {
                    console.log('Player created!');
                    res.statusCode = 200;
                    res.setHeader('Content-Type', 'application/json');
                    res.json(user);
                  }, (err) => { next(err) })
                  .catch((err) => { next(err) });
                }
              });
            });
          }
        });
      }, (err) => { next(err) })
      .catch((err) => { next(err) });
    }
    else if(req.body.userType == 'regular'){
      User.regulars.create({ name: name._id })
      .then((regular) => {
        User.users.register(new User.users({username: req.body.username, email: req.body.email}), req.body.password, (err, user) => {
          if(err){
            res.statusCode = 500;
            res.setHeader('Content-Type', 'application/json');
            res.json({err: err});
          }
          else {
            passport.authenticate('local')(req, res, () => {
              user._regular = regular._id;
              user.save((err, user) => {
                if(err){
                  res.statusCode = 500;
                  res.setHeader('Content-Type', 'application/json');
                  res.json({err: err});
                }
                else {
                  User.users.findById(user._id)
                  .populate({path: '_regular', populate: {path: 'name'}})
                  .then((user) => {
                    console.log('Regular User created!');
                    res.statusCode = 200;
                    res.setHeader('Content-Type', 'application/json');
                    res.json(user);
                  }, (err) => { next(err) })
                  .catch((err) => { next(err) });
                }
              });
            });
          }
        });
      }, (err) => { next(err) })
      .catch((err) => { next(err) });
    }
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
});

userRouter.route('/')
.get((req, res, next) => {
  User.users.find()
  .populate({path: '_regular', populate: {path: 'name'}})
  .populate({path: '_player', populate: {path: 'name'}})
  .populate({path: '_coach', populate: {path: 'name'}})
  .then((users) => { 
    res.statusCode = 200;
    res.setHeader('Content-Type', 'application/json');
    res.json(users);
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
})
.post((req, res, next) => {
  res.statusCode = 404;
  res.end('To signup users use \'/users/signup/\'');
})
.put((req, res, next) => {
  res.statusCode = 403;
  res.end('PUT not supported in /users');
})
.delete(authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
  User.names.remove({})
  .then((resp) => {
    console.log('All names removed! :', resp);
    User.regulars.remove({})
    .then((resp) => {
      console.log('All regulars removed! :', resp);
      User.players.remove({})
      .then((resp) => {
        console.log('All players removed! :', resp);
        User.coaches.remove({})
        .then((resp) => {
          console.log('All coaches removed! :', resp);
          User.users.remove({})
          .then((resp) => {
            console.log('All users removed! :', resp);
            res.statusCode = 200;
            res.end('All users removed!');
          }, (err) => { next(err) })
          .catch((err) => { next(err) });
        }, (err) => { next(err) })
        .catch((err) => { next(err) });
      }, (err) => { next(err) })
      .catch((err) => { next(err) });
    }, (err) => { next(err) })
    .catch((err) => { next(err) });
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
});

userRouter.route('/:userId')
.get((req, res, next) => {
  User.users.findById(req.params.userId)
  .populate({path: '_regular', populate: {path: 'name'}})
  .populate({path: '_player', populate: {path: 'name'}})
  .populate({path: '_coach', populate: {path: 'name'}})
  .then((user) => {
    if(!user) {
      res.statusCode = 404;
      res.end('User not found!');
    }
    else {
      res.statusCode = 200;
      res.setHeader('Content-Type', 'application/json');
      res.json(user);
    }
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
})
.post((req, res, next) => {
  res.statusCode = 403;
  res.end('POST not supported in /users/' + req.params.userId); 
})
.put(authenticate.verifyUser, (req, res, next) => {
  User.users.findById(req.params.userId)
  .then((user) => {
    if(!user) {
      res.statusCode = 404;
      res.end('User not found!');
    }
    if(req.body.email){
      user.email = req.body.email;
      user.save();
    }
    if(user._regular) {
      User.regulars.findById(user._regular)
      .then((regular) => {
        if(req.body.firstName || req.body.lastName) {
          User.names.findById(regular.name)
          .then((name) => {
            if(req.body.firstName)
              name.firstName = req.body.firstName;
            if(req.body.lastName)
              name.lastName = req.body.lastName;
            name.save();
          }, (err) => { next(err) })
          .catch((err) => { next(err) });
        }
        if(req.body.avatar)
          regular.avatar = req.body.avatar;
        regular.save();
        User.users.findById(req.params.userId)
        .populate({path: '_regular', populate: {path: 'name'}})
        .then((user) => {
          res.statusCode = 200;
          res.setHeader('Content-Type', 'application/json');
          res.json(user);
        }, (err) => { next(err) })
        .catch((err) => { next(err) });
      }, (err) => { next(err) })
      .catch((err) => { next(err) });
    }
    else if(user._player) {
      User.players.findById(user._player)
      .then((player) => {
        if(req.body.firstName || req.body.lastName) {
          User.names.findById(player.name)
          .then((name) => {
            if(req.body.firstName)
              name.firstName = req.body.firstName;
            if(req.body.lastName)
              name.lastName = req.body.lastName;
            name.save();
          }, (err) => { next(err) })
          .catch((err) => { next(err) });
        }
        if(req.body.avatar)
          player.avatar = req.body.avatar;
        if(req.body.sport)
          player.sport = req.body.sport;
        if(req.body.height)
          player.height = req.body.height;
        if(req.body.weight)
          player.weight = req.body.weight;
        if(req.body.birthDate)
          player.birthDate = req.body.birthDate;
        if(req.body.nationality)
          player.nationality = req.body.nationality;
        if(req.body.nickname)
          player.nickname = req.body.nickname;
        if(req.body.position)
          player.position = req.body.position;
        player.save();
        User.users.findById(req.params.userId)
        .populate({path: '_player', populate: {path: 'name'}})
        .then((user) => {
          res.statusCode = 200;
          res.setHeader('Content-Type', 'application/json');
          res.json(user);
        }, (err) => { next(err) })
        .catch((err) => { next(err) });
      }, (err) => { next(err) })
      .catch((err) => { next(err) });
    }
    else if(user._coach) {
      User.coaches.findById(user._coach)
      .then((coach) => {
        if(req.body.firstName || req.body.lastName) {
          User.names.findById(coach.name)
          .then((name) => {
            if(req.body.firstName)
              name.firstName = req.body.firstName;
            if(req.body.lastName)
              name.lastName = req.body.lastName;
            name.save();
          }, (err) => { next(err) })
          .catch((err) => { next(err) });
        }
        if(req.body.avatar)
          coach.avatar = req.body.avatar;
        coach.save();
        User.users.findById(req.params.userId)
        .populate({path: '_coach', populate: {path: 'name'}})
        .then((user) => {
          res.statusCode = 200;
          res.setHeader('Content-Type', 'application/json');
          res.json(user);
        }, (err) => { next(err) })
        .catch((err) => { next(err) });
      }, (err) => { next(err) })
      .catch((err) => { next(err) });
    }
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
})
.delete(authenticate.verifyUser, (req, res, next) => {
  User.users.findById(req.params.userId)
  .then((user) => {
    if(!user) {
      res.statusCode = 404;
      res.end('User not found!');
    }
    else{
      User.users.findById(req.params.userId)
      .populate({path: '_regular', populate: {path: 'name'}})
      .populate({path: '_player', populate: {path: 'name'}})
      .populate({path: '_coach', populate: {path: 'name'}})
      .then((user) => {
        user.remove()
        .then((user) => {
          console.log('User deleted: ', user);
          res.status = 200;
          res.setHeader('Content-Type', 'application/json');
          res.json(user);
        }, (err) => { next(err) })
        .catch((err) => { next(err) });
      }, (err) => { next(err) })
      .catch((err) => { next(err) });
      if(user._regular) {
        User.regulars.findById(user._regular)
        .then((regular) => {
          User.names.findByIdAndRemove(regular.name)
          .then((name) => {
            console.log('Name deleted: ', name);
          }, (err) => { next(err) })
          .catch((err) => { next(err) });
          regular.remove()
          .then((regular) => {
            console.log('Regular User deleted: ', regular);
          }, (err) => { next(err) })
          .catch((err) => { next(err) });
        }, (err) => { next(err) })
        .catch((err) => { next(err) });
      } else if(user._player) {
        User.players.findById(user._player)
        .then((player) => {
          User.names.findByIdAndRemove(player.name)
          .then((name) => {
            console.log('Name deleted: ', name);
          }, (err) => { next(err) })
          .catch((err) => { next(err) });
          player.remove()
          .then((player) => {
            console.log('Player deleted: ', player);
          }, (err) => { next(err) })
          .catch((err) => { next(err) });
        }, (err) => { next(err) })
        .catch((err) => { next(err) });
      } else if(user._coach) {
        User.coaches.findById(user._coach)
        .then((coach) => {
          User.names.findByIdAndRemove(coach.name)
          .then((name) => {
            console.log('Name deleted: ', name);
          }, (err) => { next(err) })
          .catch((err) => { next(err) });
          coach.remove()
          .then((coach) => {
            console.log('Coach deleted: ', coach);
          }, (err) => { next(err) })
          .catch((err) => { next(err) });
        }, (err) => { next(err) })
        .catch((err) => { next(err) });
      }
    }
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
})

module.exports = userRouter;
