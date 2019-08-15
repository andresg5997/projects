var mongoose = require('mongoose');
mongoose.Promise = require('bluebird');

const Dishes = require('./models/dishes.js');

const url = 'mongodb://localhost:27017/conFusion';
const connect = mongoose.connect(url);

connect.then((client) => {	

	// Needed to work on Mongoose 5.x
	var db = client.connections[0];

	console.log('Connected to server');

	Dishes.create({
		name: 'Test Name',
		description : 'test'
	})
	.then((dish) => {
		console.log(dish);

		return Dishes.findByIdAndUpdate(dish._id, {
			$set: { description: 'Updated description' },
		},{
			new: true
		}).exec();
	})
	.then((dish) => {
		console.log(dish);

		dish.comments.push({
			rating: 5,
			comment: 'Good job',
			author: 'Leonardo DiCaprio'
		});

		return dish.save();
	})
	.then((dish) => {
		console.log(dish);

        return db.collection('dishes').drop();
	})
	.then(() => {
		return db.close();
	})
	.catch((err) => console.log(err));
})
.catch((err) => console.log(err));