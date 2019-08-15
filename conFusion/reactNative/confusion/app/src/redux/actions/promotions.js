import firebase from 'react-native-firebase';
import * as ActionTypes from '../ActionTypes';

const promosLoading = () => ({
  type: ActionTypes.PROMOS_LOADING,
});

const promosFailed = errmess => ({
  type: ActionTypes.PROMOS_FAILED,
  payload: errmess,
});

const addPromos = promos => ({
  type: ActionTypes.ADD_PROMOS,
  payload: promos,
});

const fetchPromos = () => (dispatch) => {
  dispatch(promosLoading());
  const db = firebase.firestore();
  db.collection('promotions').get()
    .then(async (promos) => {
      const promosDocs = promos.docs;
      const promosArray = [];
      promosDocs.forEach(promo => promosArray.push(promo.data()));
      dispatch(addPromos(promosArray));
    })
    .catch(error => dispatch(promosFailed(error)));
};


const PromotionsActions = {
  promosLoading,
  promosFailed,
  addPromos,
  fetchPromos,
};

export default PromotionsActions;
