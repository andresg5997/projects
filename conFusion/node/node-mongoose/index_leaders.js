var mongoose = require('mongoose');
mongoose.Promise = require('bluebird');

const Dishes = require('./models/dishes.js');
const Leaders = require('./models/leaders.js');

const url = 'mongodb://localhost:27017/conFusion';
const connect = mongoose.connect(url);

connect.then((client) => {	

	// Needed to work on Mongoose 5.x
	var db = client.connections[0];

	console.log('Connected to server');

	var newDish = new Dishes({
		name: 'Test Name',
		description : 'test'
	});

	var newLeader = new Leaders({
		name: 'Test Leader Name'
	});

	newDish.save()
		.then((dish) => {
			console.log('\nNew dish');
			console.log(dish);
			
			return newLeader.save()
		})
		.then((leader) => {
			console.log('\nNew leader');
			console.log(leader)

			return Dishes.find({}).exec();
		})
		.then((dishes) => {
			console.log('\nAll dishes');
			console.log(dishes);

			return Leaders.find({}).exec();
		})
		.then((leaders) => {
			console.log('\nAll leaders');
			console.log(leaders);

            return db.collection('dishes').drop();
		})
		.then(() => {
            return db.collection('leaders').drop();
		})
		.then(() => {
			return db.close();
		})
		.catch((err) => console.log(err));
});