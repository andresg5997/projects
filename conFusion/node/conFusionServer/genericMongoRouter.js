const express = require('express');
const bodyParser = require('body-parser');
const mongoose = require('mongoose');

/* FOR COURSERA PEER REVIEWERS

I've made a variation of the exercise proposed, instead of creating a router for each model I've made a single genericMongoRouter
that receives a description of the model fields and returns a specific router for that model. It works!

Below I copy a part of the app.js, including the descriptions that this genericMongoRouter receives and the "uses" that map the routes to them.

//DISH model description for genericMongoRouter
const dishModelDescription = {
  modelName: 'dish',
  modelNamePlural: 'dishes',
  subModels: [
  {
    subModelName: 'comments',
    subModelUpdatableFields: ['rating', 'comment']
  }
  ]
}

//LEADER model description for genericMongoRouter
const leaderModelDescription = {
  modelName: 'leader',
  modelNamePlural: 'leaders',
}

//PROMOTION model description for genericMongoRouter
const promoModelDescription = {
  modelName: 'promotion',
  modelNamePlural: 'promotions',
}

// routes based on genericMongoRouter
app.use('/dishes', genericMongoRouter(dishModelDescription));
app.use('/promotions', genericMongoRouter(promoModelDescription));
app.use('/leaders', genericMongoRouter(leaderModelDescription));


*/




genericMongoRouter = (modelDescription) => {

  const modelRouter = express.Router();

  const Model = require(`../models/${modelDescription.modelNamePlural}`);

  modelRouter.use(bodyParser.json());

  // ROUTES for MAIN model WITHOUT ID
  modelRouter.route('/')
    // GET-SHOW Route - gets ALL registers
    .get((req, res, next) => {
      Model.find({})
        .then((items) => {
          res.statusCode = 200;
          res.setHeader('Content-Type', 'applications/json');
          res.json(items);
        }, (err) => next(err))
        .catch((err) => next(err));
    })
    // POST-CREATE Route - creates ONE register
    .post((req, res, next) => {
      Model.create(req.body)
        .then((item) => {
          console.log(`${modelDescription.modelName} Created`, item);
          res.statusCode = 200;
          res.setHeader('Content-Type', 'applications/json');
          res.json(item);
        }, (err) => next(err))
        .catch((err) => next(err));
    })
    // PUT-UPDATE Route (NOT allowed WITHOUT ID)
    .put((req, res, next) => {
      res.statusCode = 403;
      res.end(`PUT not supported on /${modelDescription.modelNamePlural}.`);
    })
    // DELETE Route - deletes ALL registers
    .delete((req, res, next) => {
      Model.remove({})
        .then((resp) => {
          res.statusCode = 200;
          res.setHeader('Content-Type', 'applications/json');
          res.json(resp)
        }, (err) => next(err))
        .catch((err) => next(err));
    });

  // ROUTES for MAIN model WITH ID
  modelRouter.route('/:idField')
    // GET-SHOW Route - gets ONE register
    .get((req, res, next) => {
      Model.findById(req.params.idField)
        .then((item) => {
          res.statusCode = 200;
          res.setHeader('Content-Type', 'applications/json');
          res.json(item);
        }, (err) => next(err))
        .catch((err) => next(err));
    })
    // POST-CREATE Route (NOT allowed WITH ID)
    .post((req, res, next) => {
      res.statusCode = 403;
      res.end(`POST not supported on /${modelDescription.modelNamePlural}/${req.params.idField} `); // no tÃ© sentit fer un post amb id
    })
    // PUT-UPDATE Route - updates ONE register
    .put((req, res, next) => {
      Model.findByIdAndUpdate(req.params.idField, {
          $set: req.body
        }, {
          new: true
        })
        .then((item) => {
          res.statusCode = 200;
          res.setHeader('Content-Type', 'applications/json');
          res.json(item);
        }, (err) => next(err))
        .catch((err) => next(err));
    })
    // DELETE Route - deletes ONE register
    .delete((req, res, next) => {
      Model.findByIdAndRemove(req.params.idField)
        .then((resp) => {
          res.statusCode = 200;
          res.setHeader('Content-Type', 'applications/json');
          res.json(resp)
        }, (err) => next(err))
        .catch((err) => next(err))
    });




  // SUB-MODELS
  // if there are sub-models (or sub-documents) we implement get, put, post, delete for them
  // sub-models are included in main model as Arrays of objects
  // the description of those sub-models arrives in array 'subModels', and consists of two properties:
  //          subModelName ('comments') which contains the name of the property in the main model that holds the array of sub-models
  //          subModelUpdatableFields ('rating', 'comment') array of strings with the names of the fields in submodel that can be updated through PUT
  
  
  if (modelDescription.subModels != null) {

    // being subModels an array, genericMongoRouter can hold more than one subModel for mainModel
    for (var t = 0; t < modelDescription.subModels.length; t++) {

      // current subModel name and updatable fields array
      let subModel = modelDescription.subModels[t].subModelName;
      let subModelUpdatableFields = modelDescription.subModels[t].subModelUpdatableFields;

      // ROUTES for SUBMODEL WITHOUT ID, preceded by MODEL ID
      modelRouter.route(`/:idField/${subModel}`)
        // GET-SHOW Route - gets ALL submodels
        .get((req, res, next) => {
          Model.findById(req.params.idField)
            .then((item) => {
              // must check if item (main model with given idField) exists
              if (item != null) {
                res.statusCode = 200;
                res.setHeader('Content-Type', 'applications/json');
                res.json(item[subModel]);
              } else {
                err = new Error(modelDescription.modelName + ' ' + req.params.idField + " nott exists");
                err.status = 404;
                return next(err);
              }
            }, (err) => next(err))
            .catch((err) => next(err));
        })
        // POST-CREATE creates ONE submodel register
        .post((req, res, next) => {
          // looking for main model ID
          Model.findById(req.params.idField)
            .then((item) => {
              // if exists (not null) we push req.body into property of main model that holds array of submodels
              if (item != null) {
                item[subModel].push(req.body);
                item.save()
                  .then((item) => {
                    res.statusCode = 200;
                    res.setHeader('Content-Type', 'applications/json');
                    res.json(item);
                  }, (err) => next(err));
              } else {
                // if main model id not exists, error
                err = new Error(modelDescription.modelName + ' ' + req.params.idField + " not exists");
                err.status = 404;
                return next(err);
              }
            }, (err) => next(err))
            .catch((err) => next(err));
        })
        // PUT-UPDATE NOT ALLOWED without specific submodel ID
        .put((req, res, next) => {
          res.statusCode = 403;
          res.end(`PUT operation not supported on /${modelDescription.modelNamePlural}/${idField}/${subModel}`);
        })
        // DELETEs all submodels
        .delete((req, res, next) => {
          Model.findById(req.params.idField)
            .then(
              (item) => {
                if (item != null) {
                  // foreach item in submodel array, remove item
                  for (var i = item[subModel].length - 1; i >= 0; i--) {
                    item[subModel].id(item[subModel][i]._id).remove();
                  }
                  // saving main model after deleting submodels
                  item.save()
                    .then(
                      (item) => {
                        res.statusCode = 200;
                        res.setHeader('Content-Type', 'applications/json');
                        res.json(item);
                      },
                      (err) => next(err)
                    );
                } else {
                  err = new Error(modelDescription.modelName + ' ' + req.params.idField + " nott exists");
                  err.status = 404;
                  return next(err);
                }
              },
              (err) => next(err)
            )
            .catch((err) => next(err));
        });

      // ROUTES for SUBMODEL WITH ID, preceded by MODEL ID
      modelRouter.route(`/:idField/${subModel}/:idFieldSub`)
        // GET-SHOW ONE SUBMODEL ITEM
        .get((req, res, next) => {
          Model.findById(req.params.idField)
            .then(
              (item) => {
                // method .id() extracted from http://mongoosejs.com/docs/subdocs.html
                // "Each subdocument has an _id by default. Mongoose document arrays have a special id method
                // for searching a document array to find a document with a given _id.
                // we check if main model AND submodel exists
                if (item != null && item[subModel].id(req.params.idFieldSub) != null) {
                  res.statusCode = 200;
                  res.setHeader('Content-Type', 'applications/json');
                  res.json(item[subModel].id(req.params.idFieldSub));
                } else if (item == null) {
                  // main model NOT exists 
                  err = new Error(modelDescription.modelName + ' ' + req.params.idField + " not exists");
                  err.status = 404;
                  return next(err);
                } else {
                  // main model exists, but submodel doesn't
                  err = new Error(subModel + ' ' + req.params.idFieldSub + " not exists");
                  err.status = 404;
                  return next(err);
                }

              }, (err) => next(err)
            )
            .catch((err) => next(err));
        })
        // POST-CREATE not allowed without ID
        .post((req, res, next) => {
          res.statusCode = 403;
          res.end(`POST not supported on /${modelDescription.modelNamePlural}/${req.params.idField}/${subModel} `);
        })
        // PUT-UPDATE submodel by ID
        .put((req, res, next) => {
          Model.findById(req.params.idField)
            .then((item) => {
              if (item != null && item[subModel].id(req.params.idFieldSub) != null) {
                //saving each subModelUpdatableFields: ex) rating, comment
                for (var i = 0; i < subModelUpdatableFields.length; i++) {
                  if (req.body[subModelUpdatableFields[i]]) {
                    item[subModel].id(req.params.idFieldSub)[subModelUpdatableFields[i]] = req.body[subModelUpdatableFields[i]];
                  }
                }
                item.save()
                  .then((item) => {
                    res.statusCode = 200;
                    res.setHeader('Content-Type', 'applications/json');
                    res.json(item);
                  }, (err) => next(err));

              } else if (item == null) {
                err = new Error(modelDescription.modelName + ' ' + req.params.idField + " not exists");
                err.status = 404;
                return next(err);
              } else {
                err = new Error(subModel + ' ' + req.params.idFieldSub + " not exists");
                err.status = 404;
                return next(err);
              }

            }, (err) => next(err))
            .catch((err) => next(err));
        })
        // DELETE Submodel by ID
        .delete((req, res, next) => {
          Model.findById(req.params.idField)
            .then((item) => {
              if (item != null && item[subModel].id(req.params.idFieldSub) != null) {
                item[subModel].id(req.params.idFieldSub).remove();
                item.save()
                  .then((item) => {
                    res.statusCode = 200;
                    res.setHeader('Content-Type', 'applications/json');
                    res.json(item);
                  }, (err) => next(err));

              } else if (item == null) {
                err = new Error(modelDescription.modelName + ' ' + req.params.idField + " not exists");
                err.status = 404;
                return next(err);
              } else {
                err = new Error(subModel + ' ' + req.params.idFieldSub + " not exists");
                err.status = 404;
                return next(err);
              }

            }, (err) => next(err))
            .catch((err) => next(err));
        });
    } //for submodels
  } //if submodels


  return modelRouter;
}



module.exports = genericMongoRouter;