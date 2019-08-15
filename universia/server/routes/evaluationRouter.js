var express = require('express');
var evaluationRouter = express.Router();
var User = require('../models/users');
var Images = require('../models/images');
var Evaluations = require('../models/evaluations');
const multer = require('multer');
var authenticate = require('../authenticate');
var cors = require('./cors');

evaluationRouter.route('/:userId')
.get(cors.cors, authenticate.verifyUser, (req, res, next) => {
  User.users.findById(req.params.userId)
  .then((user) => {
    if(user){
      if(user._player){
        Evaluations.find({player: user._id})
        .populate('author')
        .populate('player')
        .populate('images')
        .then((received) => {
          Evaluations.find({author: user._id})
        .populate('author')
        .populate('player')
        .populate('images')
          .then((sent) => {
            res.statusCode = 200;
            res.setHeader('Content-Type', 'application/json');
            res.json({sent: sent, received: received});
          }, (err) => { next(err) })
          .catch((err) => { next(err) });
        }, (err) => { next(err) })
        .catch((err) => { next(err) });
      } else {
        Evaluations.find({author: user._id})
        .populate('author')
        .populate('player')
        .populate('images')
        .then((sent) => {
          res.statusCode = 200;
          res.setHeader('Content-Type', 'application/json');
          res.json({sent: sent});
        }, (err) => { next(err) })
        .catch((err) => { next(err) });
      }
    }
    else {
      var err = new Error('User ' + req.params.userId + ' not found!');
      err.status = 404;
      return next(err);
    }
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
})
.post(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
  User.users.findById(req.params.userId)
  .then((user) => {
    if(user) {
      Evaluations.create({
        title: req.body.title,
        content: req.body.content
      })
      .then((evaluation) => {
        evaluation.author = req.user._id;
        evaluation.player = req.params.userId;
        evaluation.images = req.body.images;
        evaluation.save()
        .then((evaluation) => {
          res.statusCode = 200;
          res.setHeader('Content-Type', 'application/json');
          res.json(evaluation);
        }, (err) => { next(err) })
        .catch((err) => { next(err) });
      }, (err) => { next(err) })
      .catch((err) => { next(err) });
    }
    else {
      var err = new Error('User ' + req.params.userId + ' not found!');
      err.status = 404;
      next(err);
    }
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
})
.put(cors.corsWithOptions, (req, res, next) => {
  res.statusCode = 403;
  res.end('PUT not supported in /evaluations/' + req.params.userId);
})
.delete(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
  User.users.findById(req.params.userId)
  .then((user) => {
    if(user) {
      Evaluations.find({
        author: req.user._id,
        player: req.params.userId
      })
      .then((evaluations) => {
        for (var i = evaluations.length - 1; i >= 0; i--) {
          // Call image router to delete
          evaluations[i].remove();
        }
        res.statusCode = 200;
        res.setHeader('Content-Type', 'application/json');
        res.json(evaluations);
      }, (err) => { next(err) })
      .catch((err) => { next(err) });
    }
    else {
      var err = new Error('User ' + req.params.userId + ' not found!');
      err.status = 404;
      next(err);
    }
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
});

evaluationRouter.route('/:evaluationId')
.get(cors.cors, (req, res, next) => {
  Evaluations.findById(req.params.evaluationId)
  .then((evaluation) => {
    res.statusCode = 200;
    res.setHeader('Content-Type', 'application/json');
    res.json(evaluation);
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
})
.post(cors.corsWithOptions, (req, res, next) => {
  res.statusCode = 403;
  res.end('POST not supported in /evaluations/' + req.params.evaluationId);
})
.put(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
  Evaluations.findById(req.params.evaluationId)
  .then((evaluation) => {
    if(evaluation){
      if((evaluation.author == req.user._id) || (req.user.admin)){
        if(req.body.title)
          evaluation.title = req.body.title
        if(req.body.content)
          evaluation.content = req.body.content
        evaluation.save()
        .then((evaluation) => {
          res.statusCode = 200;
          res.setHeader('Content-Type', 'application/json');
          res.json(evaluation);
        }, (err) => { next(err) })
        .catch((err) => { next(err) });
      }
      else {
        var err = new Error('You are not the author of the evaluation!');
        err.status = 403;
        next(err);
      }
    }
    else {
      var err = new Error('Evaluation ' + req.params.evaluationId + ' not found!');
      err.status = 404;
      next(err);
    }
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
})
.delete(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
  Evaluations.findById(req.params.evaluationId)
  .then((evaluation) => {
    if(evaluation) {
      if((evaluation.author == req.user._id) || (req.user.admin)) {
        // Call image router to delete
        evaluation.remove();
        evaluation.save()
        .then((evaluation) => {
          res.statusCode = 200;
          res.setHeader('Content-Type', 'application/json');
          res.json(evaluation);
        }, (err) => { next(err) })
        .catch((err) => { next(err) });
      }
      else {
        var err = new Error('You are not the author of the evaluation!');
        err.status = 403;
        next(err);
      }
    }
    else {
      var err = new Error('Evaluation ' + req.params.evaluationId + ' not found!');
      err.status = 404;
      next(err);
    }
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
})

evaluationRouter.route('/:evaluationId/images/')
.get(cors.cors, (req, res, next) => {
  Evaluations.findById(req.params.evaluationId)
  .populate('images')
  .then((evaluation) => {
    if(evaluation) {
      res.statusCode = 200;
      res.setHeader('Content-Type', 'application/json');
      res.json(evaluation.images);
    }
    else {
      var err = new Error('Evaluation ' + req.params.evaluationId + ' not found!');
      err.status = 404;
      next(err);
    }
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
})
.post(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
  Evaluations.findById(req.params.evaluationId)
  .then((evaluation) => {
    if(evaluation) {
      if((evaluation.author == req.user._id) || (req.user.admin)) {
        for (var i = req.body.images.length - 1; i >= 0; i--) {
          evaluation.images.push(req.body.images[i]);
        }
        evaluation.save()
        .then((evaluation) => {
          res.statusCode = 200;
          res.setHeader('Content-Type', 'application/json');
          res.json(evaluation);
        }, (err) => { next(err) })
        .catch((err) => { next(err) });
      }
      else {
        var err = new Error('You are not the author of the evaluation!');
        err.status = 403;
        next(err);
      }
    }
    else {
      var err = new Error('Evaluation ' + req.params.evaluationId + ' not found!');
      err.status = 404;
      next(err);
    }
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
})
.put(cors.corsWithOptions, (req, res, next) => {
  res.statusCode = 403;
  res.end('PUT not supported in /evaluations/' + req.params.userId + '/images');
})
.delete(cors.corsWithOptions, authenticate.verifyUser, (req, res, next) => {
  Evaluations.findById(req.params.evaluationId)
  .then((evaluation) => {
    if(evaluation) {
      if((evaluation.author == req.user._id) || (req.user.admin)) {
        // Call image router to delete
      }
      else {
        var err = new Error('You are not the author of the evaluation!');
        err.status = 403;
        next(err);
      }
    }
    else {
      var err = new Error('Evaluation ' + req.params.evaluationId + ' not found!');
      err.status = 404;
      next(err);
    }
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
})

evaluationRouter.route('/:evaluationId/images/:imageId')
.get(cors.cors, (req, res, next) => {
  Evaluations.findById(req.params.evaluationId)
  .then((evaluation) => {
    if((evaluation != null) && (evaluation.images.id(req.params.imageId))) {
      // Remove image
      evaluation.images.id(req.params.imageId).remove();
    }
    else if(evaluation == null){
      var err = new Error('Evaluation ' + req.params.evaluationId + ' not found!');
      err.status = 404;
      next(err);
    } else {
      var err = new Error('Image ' + req.params.evaluationId + ' not found!');
      err.status = 404;
      next(err);
    }
  }, (err) => { next(err) })
  .catch((err) => { next(err) });
})

module.exports = evaluationRouter;
