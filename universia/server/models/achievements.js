var mongoose = require('mongoose');
var Schema = mongoose.Schema;

const Achievements = new Schema({
	name: {
		type: String,
		required: true
	},
	date: {
		type: Date,
		required: true
	},
	place: {
		type: String,
		default: ''
	},
	details: {
		type: String,
		required: true
	},
	player: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'User'
	},
	images:[{
		type: mongoose.Schema.Types.ObjectId,
		ref: 'Image'
	}]
},{
	timestamps: true
});

module.exports = mongoose.model('Achievement', Achievements);