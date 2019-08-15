import { createSwitchNavigator, createStackNavigator } from 'react-navigation';
// components
import LoginScreen from './screens/auth/login/index';
import RegisterScreen from './screens/auth/register';
import ForgotPasswordScreen from './screens/auth/forgot-password';
import DashboardScreen from './screens/dashboard';
import SettingsScreen from './screens/settings';
import CategoriesScreen from './screens/categories';
import AddCategoryScreen from './screens/categories/addCategory';
import SplashScreen from './components/splash';
import TransactionsScreen from './screens/transactions';
import AddTransactionScreen from './screens/transactions/addTransaction';

const AppStack = createStackNavigator({
  Dashboard: DashboardScreen,
  Transactions: TransactionsScreen,
  AddTransaction: AddTransactionScreen,
  Categories: CategoriesScreen,
  AddCategory: AddCategoryScreen,
  Settings: SettingsScreen,
});

const AuthStack = createStackNavigator({
  Login: LoginScreen,
  Register: RegisterScreen,
  ForgotPassword: ForgotPasswordScreen,
});

const Navigator = createSwitchNavigator({
  Splash: SplashScreen,
  Auth: AuthStack,
  App: AppStack,
}, { initialRouteName: 'Splash' });

export default Navigator;
