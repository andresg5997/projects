import firebase from 'react-native-firebase';
import * as ActionTypes from '../ActionTypes';

const dishesLoading = () => ({
  type: ActionTypes.DISHES_LOADING,
});

const dishesFailed = errmess => ({
  type: ActionTypes.DISHES_FAILED,
  payload: errmess,
});

const addDishes = dishes => ({
  type: ActionTypes.ADD_DISHES,
  payload: dishes,
});

const fetchDishes = () => (dispatch) => {
  dispatch(dishesLoading());
  const db = firebase.firestore();
  db.collection('dishes').get()
    .then(async (dishes) => {
      const dishesDocs = dishes.docs;
      const dishesArray = [];
      dishesDocs.forEach(dish => dishesArray.push(dish.data()));
      dispatch(addDishes(dishesArray));
    })
    .catch(error => dispatch(dishesFailed(error)));
};

const DishesActions = {
  dishesLoading,
  dishesFailed,
  addDishes,
  fetchDishes,
};

export default DishesActions;
