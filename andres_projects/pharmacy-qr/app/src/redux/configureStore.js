import { createStore, applyMiddleware } from 'redux';
import { persistStore, persistCombineReducers } from 'redux-persist';
import storage from 'redux-persist/es/storage';
import logger from 'redux-logger';
import medicaments from './reducers/medicaments';

const config = {
  key: 'root',
  storage,
  debug: true,
};

const ConfigureStore = () => {
  const store = createStore(
    persistCombineReducers(config, {
      medicaments,
    }),
    applyMiddleware(logger),
  );

  const persistor = persistStore(store);

  return { persistor, store };
};

export default ConfigureStore;
