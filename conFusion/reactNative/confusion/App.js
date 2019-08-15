import React from 'react';
import { Provider } from 'react-redux';
import { PersistGate } from 'redux-persist/es/integration/react';
import Main from './app/src/components/MainComponent';
import ConfigureStore from './app/src/redux/configureStore';
import Loading from './app/src/components/LoadingComponent';

const { persistor, store } = ConfigureStore();

export default class App extends React.Component {
  render() {
    return (
      <Provider store={store}>
        <PersistGate
          loading={<Loading />}
          persistor={persistor}
        >
          <Main />
        </PersistGate>
      </Provider>
    );
  }
}
