import * as ActionTypes from '../ActionTypes';

const initialState = {
  imagelinks: {
    dish: '',
    leader: '',
    promotion: '',
  },
};

const imagelinks = (state = initialState, action) => {
  switch (action.type) {
    case ActionTypes.ADD_IMAGELINKS:
      return { ...state, imagelinks: action.payload };
    default:
      return state;
  }
};

export default imagelinks;
