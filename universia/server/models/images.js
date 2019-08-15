var mongoose = require('mongoose');
var Schema = mongoose.Schema;

const Images = new Schema({
	path: {
		type: String,
		required: true
	}
},{
	timestamps: true
});

module.exports = mongoose.model('Image', Images);