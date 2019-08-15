var mongoose = require('mongoose');
var Schema = mongoose.Schema;

const leaderSchema = new Schema({
	name: {
		type: String,
		required: true,
		unique: true
	}
},{
	timestamps: true
});

var Leader = mongoose.model('Leader', leaderSchema);

module.exports = Leader;