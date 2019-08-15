var mongoose = require('mongoose');
var Schema = mongoose.Schema;

const Matches = new Schema({
	result: {
		type: String,
		required: true
	},
	sport: {
		type: Date,
		required: true
	},
	homeClub: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'Club'
	},
	visitantClub: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'Club'
	},
	place: {
		type: String,
		default: ''
	},
	details: {
		type: String,
		required: true
	},
	date: {
		type: Date,
		required: true
	},
	images:[{
		type: mongoose.Schema.Types.ObjectId,
		ref: 'Image'
	}]
},{
	timestamps: true
});	

module.exports = mongoose.model('Match', Matches);