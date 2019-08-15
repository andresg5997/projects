const express = require('express');
const authenticate = require('../authenticate');
const multer = require('multer');
var Images = require('../models/images');
var Promise = require('bluebird');

function nameFormat(originalname) {
  var name = originalname.split('.');
  var today = new Date();
  var dd = today.getDate()
  var mm = today.getMonth();
  if(dd < 10)
    dd = '0' + dd;
  if(mm < 10)
    mm = '0' + mm;
  var date = dd + '-' + mm + '-' + today.getFullYear() +
  '_' +  today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
  var fullname = name[0] + '_' + date + '.' +name[1];
  return fullname;
}

const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, 'public/images')
  },

  filename: (req, file, cb) => {
    cb(null, nameFormat(file.originalname))
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
.get((req, res, next) => {
  res.statusCode = 403;
  res.end('GET not supported in /uploadImage');
})
.post((req, res, next) => {
  var promiseWhile = (condition, action) => {
    var resolver = Promise.defer();
    var loop = () => {
      if (!condition()) return resolver.resolve();
      return Promise.cast(action())
      .then(loop)
      .catch(resolver.reject);
    };
    process.nextTick(loop);
    return resolver.promise;
  };
  var count = 0;
  var uploadedImages = [];
  upload.array('imageFile', 5)(req, res, (err) => {
    if(!err) {
      promiseWhile(() => {
        return count < req.files.length;
      }, () => {
        return new Promise((resolve, reject) => {
          Images.create({ path: req.files[count].path })
          .then((image) => {
            uploadedImages.push(image._id);
            count++;
            if(count == req.files.length) {
              res.statusCode = 200;
              res.setHeader('Content-Type', 'application/json');
              res.json(uploadedImages);
            }
            resolve();
          }, (err) => { next(err) })
          .catch((err) => { next(err) });
        }); 
      });
    }
  });
})
.put((req, res, next) => {
  res.statusCode = 403;
  res.end('PUT not supported in /uploadImage');
})
.delete((req, res, next) => {
  res.statusCode = 403;
  res.end('DELETE not supported in /uploadImage');
})

module.exports = uploadRouter;