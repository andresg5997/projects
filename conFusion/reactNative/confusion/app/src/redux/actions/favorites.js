import * as ActionTypes from '../ActionTypes';

const addFavorite = dishId => ({
  type: ActionTypes.ADD_FAVORITE,
  payload: dishId,
});

const postFavorite = dishId => (dispath) => {
  setTimeout(() => {
    dispath(addFavorite(dishId));
  }, 2000);
};

const deleteFavorite = dishId => ({
  type: ActionTypes.DELETE_FAVORITE,
  payload: dishId,
});

const FavoritesActions = {
  addFavorite,
  postFavorite,
  deleteFavorite,
};

export default FavoritesActions;
