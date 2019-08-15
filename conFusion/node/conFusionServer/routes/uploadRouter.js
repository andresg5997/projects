const express = require('express');
const authenticate = require('../authenticate');
const multer = require('multer');
const cors = require('./cors');

const storage = multer.diskStorage({
	destination: (req, file, cb) => {
		cb(null, 'public/images')
	},

	filename: (req, file, cb) => {
		cb(null, file.originalname + '-' + Date.now())
	}
});

const imageFileFilter = (req, file, cb) => {
	if(!file.originalname.match(/\.(jpg|jpeg|png|gif)$/)) {
		return cb(new Error('You can only upload image files!'), false);
	}
	cb(null, true);
};

const upload = multer({storage: storage, fileFilter: imageFileFilter});

const uploadRouter = express.Router();

uploadRouter.route('/')
.get(cors.cors, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	res.statusCode = 403;
	res.end('GET not supported in /upload');
})
.post(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, upload.single('imageFile'), (req, res, next) => {
	res.statusCode = 200;
	res.setHeader('Content-Type', 'application/json');
	res.json(req.file);
})
.put(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	res.statusCode = 403;
	res.end('PUT not supported in /upload');
})
.delete(cors.corsWithOptions, authenticate.verifyUser, authenticate.verifyAdmin, (req, res, next) => {
	res.statusCode = 403;
	res.end('DELETE not supported in /upload');
})

module.exports = uploadRouter;