import { createBottomTabNavigator } from 'react-navigation';
import LoginTab from './LoginComponent';
import RegisterTab from './RegisterComponent';

const Auth = createBottomTabNavigator({
  Login: LoginTab,
  Register: RegisterTab,
}, {
  tabBarOptions: {
    activeBackgroundColor: '#9575CD',
    inactiveBackgroundColor: '#D1C4E9',
    activeTintColor: '#ffffff',
    inactiveTintColor: 'gray',
  },
});

export default Auth;
