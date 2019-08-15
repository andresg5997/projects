import { FETCH_COUNTER, SET_COUNTER } from '../constants';

const fetchCounter = data => ({
  type: FETCH_COUNTER,
});

const setCounter = counter => ({
  type: SET_COUNTER,
  payload: counter,
});

const counterActions = {
  fetchCounter,
  setCounter,
};

export default counterActions;
