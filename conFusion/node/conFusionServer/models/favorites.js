var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var Favorites = mongoose.model('Favorite', new Schema({
	user: {
		type: mongoose.Schema.Types.ObjectId,
		ref: 'User'
	},
	favorites: [{ 
		type: mongoose.Schema.Types.ObjectId,
		ref: 'Dish'
	}]
}, {
	timestamps: true
}));

module.exports = Favorites;