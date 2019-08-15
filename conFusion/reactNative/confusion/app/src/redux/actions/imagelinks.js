import firebase from 'react-native-firebase';
import * as ActionTypes from '../ActionTypes';

const addImageLinks = imagelinks => ({
  type: ActionTypes.ADD_IMAGELINKS,
  payload: imagelinks,
});

const fetchImageLinks = (dish, leader, promotion) => (dispatch) => {
  firebase.storage().ref(dish.image).getDownloadURL()
    .then((dishUrl) => {
      firebase.storage().ref(leader.image).getDownloadURL()
        .then((leaderUrl) => {
          firebase.storage().ref(promotion.image).getDownloadURL()
            .then((promotionUrl) => {
              const data = {
                dish: dishUrl,
                leader: leaderUrl,
                promotion: promotionUrl,
              };
              dispatch(addImageLinks(data));
            });
        });
    });
};

const ImageLinksActions = {
  addImageLinks,
  fetchImageLinks,
};

export default ImageLinksActions;
