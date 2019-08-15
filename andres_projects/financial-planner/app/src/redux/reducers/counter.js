// @flow
import { FETCH_COUNTER, SET_COUNTER } from '../constants';

const initialState = {
  counter: 80,
};

const counterReducer = (state = initialState, action) => {
  switch (action.type) {
    case SET_COUNTER:
      return Object.assign({}, state, { counter: action.payload });
    case FETCH_COUNTER:
      return state;
    default:
      return state;
  }
};

export default counterReducer;
