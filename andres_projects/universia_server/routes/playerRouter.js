var express = require('express');
var playerRouter = express.Router();
var User = require('../models/users');
var Follows = require('../models/follow');
var authenticate = require('../authenticate');

module.exports = playerRouter;