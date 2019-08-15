var mongoose = require('mongoose');
var Schema = mongoose.Schema;
require('mongoose-currency').loadType(mongoose);
var Currency = mongoose.Types.Currency;

var Comments = mongoose.model('Comment', new Schema({
	dishId: {
		type: Number,
		required: true
	},
	rating: {
		type: Number,
		min: 1,
		max: 5,
		required: true
	},
	comment: {
		type: String,
		required: true
	},
	author: {
		type: String,
		required: true
	},
	date: {
		type: Date,
		default: new Date()
	}
}));

module.exports = Comments;