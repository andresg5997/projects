var mongoose = require('mongoose');
var Schema = mongoose.Schema;
var passportLocalMongoose = require('passport-local-mongoose');

const Users = new Schema({
	email: {
		type: String,
		required: true
	},
	admin: {
		type: Boolean,
		default: false
	},
	_regular: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'Regular'
	},
	_player: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'Player'
	},
	_coach: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'Coach'
	}
}, {
	timestamps: true
});

const Regulars = new Schema({
	name: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'Name'
	},
	avatar: {
		type: String,
		default: ''
	}
});

const Players = new Schema({
	name: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'Name'
	},
	avatar: {
		type: String,
		default: ''
	},
	sport: {
		type: String,
		default: ''
	},
	height: {
		type: String,
		default: ''
	},
	weight: {
		type: String,
		default: ''
	},
	birthDate: {
		type: Date,
		default: ''
	},
	nationality: {
		type: String,
		default: ''
	},
	nickname: {
		type: String,
		default: ''
	},
	position: {
		type: String,
		default: ''
	},
	club: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'Club'
	}
});

const Coaches = new Schema({
	name: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'Name'
	},
	avatar: {
		type: String,
		default: ''
	},
	club: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'Club'
	}
});

const Names = new Schema({
	firstName: {
		type: String,
		required: true
	},
	lastName: {
		type: String,
		required: true
	}
});

Users.plugin(passportLocalMongoose);

exports.users = mongoose.model('User', Users);
exports.regulars = mongoose.model('Regular', Regulars);
exports.players = mongoose.model('Player', Players);
exports.coaches = mongoose.model('Coach', Coaches);
exports.names = mongoose.model('Name', Names);
