import storage from 'redux-persist/lib/storage'; // default: localStorage if web, AsyncStorage if react-native
import { persistCombineReducers } from 'redux-persist';
import counterReducer from './counter';
import categoriesReducer from './categories';

const config = {
  key: 'primary',
  storage,
};

const reducers = {
  counter: counterReducer,
  categories: categoriesReducer,
};

// combine reducers with redux-persist
const rootReducer = persistCombineReducers(config, reducers);

export default rootReducer;
