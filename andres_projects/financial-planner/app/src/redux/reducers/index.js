import storage from 'redux-persist/lib/storage'; // default: localStorage if web, AsyncStorage if react-native
import { persistCombineReducers } from 'redux-persist';
import navigationReducer from './navigation';
import counterReducer from './counter';

const config = {
  key: 'primary',
  storage,
};

const reducers = {
  navigation: navigationReducer,
  counter: counterReducer,
};

// combine reducers with redux-persist
const rootReducer = persistCombineReducers(config, reducers);

export default rootReducer;
