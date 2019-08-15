import React, { Component } from 'react';
import { connect } from 'react-redux';
import { StackNavigator, addNavigationHelpers } from 'react-navigation';
// import new redux helpers
import { createReactNavigationReduxMiddleware, createReduxBoundAddListener } from 'react-navigation-redux-helpers';

import DashboardScreen from './screens/dashboard';
import SettingsScreen from './screens/settings';

// Configure listener
// We'll use this middleware when we call `createStore` in App.js, so we'll export it from here
export const reduxNavigationMiddleware = createReactNavigationReduxMiddleware(
  'root',
  state => state.navigation,
);

const addListener = createReduxBoundAddListener('root');

export const Navigator = new StackNavigator({
  Dashboard: { screen: DashboardScreen },

  Settings: { screen: SettingsScreen },
},
{
  initialRouteName: 'Dashboard',
});

type Props = {
  dispatch: Function,
  navigation: Object
}

class Nav extends Component<void, Props> {
  render() {
    const { dispatch, navigation } = this.props;

    return (
      <Navigator
        navigation={addNavigationHelpers({
          dispatch,
          state: navigation,
          addListener,
        })}
      />
    );
  }
}

const mapStateToProps = state => ({
  navigation: state.navigation,
});

export default connect(mapStateToProps)(Nav);
