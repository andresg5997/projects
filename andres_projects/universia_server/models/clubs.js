var mongoose = require('mongoose');
var Schema = mongoose.Schema;

const Clubs = new Schema({
	name: {
		type: String,
		required: true
	},
	avatar: {
		type: String,
		default: ''
	},
	sport: {
		type: String,
		required: true
	},
	leader: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'User'
	}
},{
	timestamps: true
});

module.exports = mongoose.model('Club', Clubs);