var mongoose = require('mongoose');
var Schema = mongoose.Schema;

const Evaluations = new Schema({
	title: {
		type: String,
		required: true
	},
	content: {
		type: String,
		required: true
	},
	author: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'User'
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

module.exports = mongoose.model('Evaluation', Evaluations);