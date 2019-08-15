const MongoClient = require('mongodb').MongoClient;
const assert = require('assert');
const dboper = require('./operations.js');

const url = 'mongodb://localhost:27017/conFusion';

MongoClient.connect(url).then((client) => {

    var db = client.db('conFusion');

    console.log('Connected to database');

    dboper.insertDocument(db, {name: 'Trial name', description: 'Test description'}, 'dishes')
        .then((result) => {
            console.log('Insert document:\n', result.ops);

            return dboper.findDocuments(db, 'dishes')
        })
        .then((docs) => {
            console.log('Found documents:\n', docs);

            return dboper.updateDocument(db, {name: 'Trial name'}, {description: 'New description'}, 'dishes')
        })
        .then((result) => {
            console.log('Updated document:\n', result.result);

            return dboper.findDocuments(db, 'dishes')
        })
        .then((docs) => {
            console.log('Found documents:\n', docs);

            return db.dropCollection('dishes')
        })
        .then((result) => {
            console.log('Dropped collection: ', result);

            client.close();
        })
        .catch((err) => console.log(err));
    })
.catch((err) => console.log(err));