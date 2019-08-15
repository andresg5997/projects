import { createStore, applyMiddleware } from 'redux';
import { persistStore, persistCombineReducers } from 'redux-persist';
import storage from 'redux-persist/es/storage';
import thunk from 'redux-thunk';
import logger from 'redux-logger';
import dishes from './reducers/dishes';
import comments from './reducers/comments';
import promotions from './reducers/promotions';
import leaders from './reducers/leaders';
import favorites from './reducers/favorites';
import imagelinks from './reducers/imagelinks';

const config = {
  key: 'root',
  storage,
  debug: true,
};

const ConfigureStore = () => {
  const store = createStore(
    persistCombineReducers(config, {
      dishes,
      comments,
      promotions,
      leaders,
      favorites,
      imagelinks,
    }),
    applyMiddleware(thunk, logger),
  );

  const persistor = persistStore(store);

  return { persistor, store };
};

export default ConfigureStore;
