import React from 'react';
import { Icon } from 'react-native-elements';
import { createStackNavigator, createBottomTabNavigator } from 'react-navigation';
import LoginTab from './login';
import RegisterTab from './register';
import PharmacyRegister from './register/pharmacy';
import { colors } from '../../theme';

const LoginNavigator = createStackNavigator({
  LoginTab: {
    screen: LoginTab,
    navigationOptions: {
      headerTitle: 'Login',
      headerStyle: {
        backgroundColor: colors.mainDark,
      },
      headerTintColor: '#fff',
      headerTitleStyle: {
        color: '#fff',
      },
    },
  },
});

const RegisterNavigator = createStackNavigator({
  RegisterTab: {
    screen: RegisterTab,
    navigationOptions: {
      headerTitle: 'Register',
    },
  },
  PharmacyRegister: {
    screen: PharmacyRegister,
    navigationOptions: {
      headerTitle: 'Register Pharmacy',
    },
  },
}, {
  navigationOptions: {
    headerStyle: {
      backgroundColor: colors.mainDark,
    },
    headerTintColor: '#fff',
    headerTitleStyle: {
      color: '#fff',
    },
  },
});

const AuthTabNavigator = createBottomTabNavigator({
  Login: {
    screen: LoginNavigator,
    navigationOptions: {
      tabBarLabel: 'Login',
      tabBarIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="sign-in"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
  Register: {
    screen: RegisterNavigator,
    navigationOptions: {
      tabBarLabel: 'Register',
      tabBarIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="user-plus"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
}, {
  tabBarOptions: {
    activeBackgroundColor: colors.mainDark,
    inactiveBackgroundColor: colors.mainLight,
    activeTintColor: '#ffffff',
    inactiveTintColor: 'gray',
  },
});

export default AuthTabNavigator;
