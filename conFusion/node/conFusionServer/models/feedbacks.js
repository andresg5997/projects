var mongoose = require('mongoose');
var Schema = mongoose.Schema;
require('mongoose-currency').loadType(mongoose);
var Currency = mongoose.Types.Currency;

var Feedback = mongoose.model('Feedback', new Schema({
	firstname: {
		type: String
	},
    lastname: {
		type: String
	},
    telnum: {
		type: String
	},
	email: {
		type: String
	},
	agree: {
		type: Boolean
	},
	contactType: {
		type: String
	},
	message: {
		type: String
	}
}));

module.exports = Feedback;