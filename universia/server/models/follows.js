var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var Follow = new Schema({
	user: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'User'
	},
	following: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'User'	
	}
});

module.exports = mongoose.model('Follow', Follow);