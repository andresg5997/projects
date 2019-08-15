// @flow
import React from 'react';
import { createStore, applyMiddleware } from 'redux';
import { Provider } from 'react-redux';
import logger from 'redux-logger';
import { persistStore } from 'redux-persist';

import Theme from './app/src/theme';
import rootReducer from './app/src/redux/reducers';
import { Navigator, reduxNavigationMiddleware } from './app/src/navigator';

const store = createStore(rootReducer, applyMiddleware(logger, reduxNavigationMiddleware));

persistStore(
  store,
  null,
  () => {
    store.getState();
  },
);

class App extends React.Component {
  render() {
    return (
      <Theme>
        <Provider store={store}>
          <Navigator />
        </Provider>
      </Theme>
    );
  }
}

export default App;
