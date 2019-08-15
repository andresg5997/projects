var createError = require('http-errors');
var express = require('express');
var path = require('path');
// var cookieParser = require('cookie-parser');
var logger = require('morgan');
var mongoose = require('mongoose');
mongoose.Promise = require('bluebird');
var passport = require('passport');
var config = require('./config');
const webpack = require('webpack');
const webpackDevMiddleware = require('webpack-dev-middleware');
const webpackConfig = require('./webpack.config.js');
const compiler = webpack(webpackConfig);

var connect = mongoose.connect(config.mongoUrl);

connect.then((client) => {
	var db = client.connections[0];

	console.log('Connected correctly to server!');
}, (err) => { console.log(err) });

var indexRouter = require('./routes/index');
var usersRouter = require('./routes/users');
var clubRouter = require('./routes/clubRouter');
var followRouter = require('./routes/followRouter');
var evaluationRouter = require('./routes/evaluationRouter');
var achievementRouter = require('./routes/achievementRouter');
var matchRouter = require('./routes/matchRouter');
var uploadRouter = require('./routes/uploadRouter');

var app = express();

// view engine setup
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'jade');

app.use(logger('dev'));
app.use(express.json());
// app.use(express.urlencoded({ extended: false }));
var bodyParser = require('body-parser');
app.use(bodyParser.json({limit: "50mb"}));
app.use(bodyParser.urlencoded({limit: "50mb", extended: true, parameterLimit:50000}));
app.use(webpackDevMiddleware(compiler, {
  publicPath: webpackConfig.output.publicPath
}));
// app.use(cookieParser());
app.use(passport.initialize());

app.use(express.static(path.join(__dirname, 'dist')));

app.use('/', indexRouter);
app.use('/users', usersRouter);
app.use('/clubs', clubRouter);
app.use('/follows', followRouter);
app.use('/evaluations', evaluationRouter);
app.use('/achievements', achievementRouter);
app.use('/matches', matchRouter);
app.use('/uploadImage', uploadRouter);
// catch 404 and forward to error handler
app.use(function(req, res, next) {
  next(createError(404));
});

// error handler
app.use(function(err, req, res, next) {
  // set locals, only providing error in development
  res.locals.message = err.message;
  res.locals.error = req.app.get('env') === 'development' ? err : {};

  // render the error page
  res.status(err.status || 500);
  res.render('error');
});

module.exports = app;
