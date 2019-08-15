import * as ActionTypes from '../ActionTypes';

const initialState = {
  errMess: null,
  comments: [],
};

const comments = (state = initialState, action) => {
  switch (action.type) {
    case ActionTypes.ADD_COMMENT:
      return { ...state, errMess: null, comments: state.comments.concat(action.payload) };

    case ActionTypes.ADD_COMMENTS:
      return { ...state, errMess: null, comments: action.payload };

    case ActionTypes.COMMENTS_FAILED:
      return { ...state, errMess: action.payload };

    default:
      return state;
  }
};

export default comments;
