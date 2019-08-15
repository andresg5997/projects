import React, { Component } from 'react';
import {
  View,
  Text,
  ScrollView,
  Image,
  StyleSheet,
  NetInfo,
  ToastAndroid } from 'react-native';
import { Icon } from 'react-native-elements';
import PushNotification from 'react-native-push-notification';
import {
  createStackNavigator,
  createDrawerNavigator,
  DrawerItems,
  SafeAreaView } from 'react-navigation';
import { connect } from 'react-redux';
import Auth from './auth';
import Menu from './MenuComponent';
import Home from './HomeComponent';
import Contact from './ContactComponent';
import About from './AboutComponent';
import DishDetails from './DishdetailsComponent';
import Reservation from './ReservationComponent';
import Favorites from './FavoritesComponent';
import DishesActions from '../redux/actions/dishes';
import LeadersActions from '../redux/actions/leaders';
import PromotionsActions from '../redux/actions/promotions';
import CommentsActions from '../redux/actions/comments';

type DrawerProps = {
  navigation: Object
};

type ComponentProps = {
  fetchDishes: Function,
  fetchLeaders: Function,
  fetchPromos: Function,
}

const NavbarDrawerIcon = (props:DrawerProps) => (
  <Icon
    iconStyle={{ marginLeft: 10 }}
    name="menu"
    size={24}
    color="white"
    onPress={() => props.navigation.toggleDrawer()}
  />
);

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  drawerHeader: {
    backgroundColor: '#512DA8',
    height: 140,
    alignItems: 'center',
    justifyContent: 'center',
    flex: 1,
    flexDirection: 'row',
  },
  drawerHeaderText: {
    color: 'white',
    fontSize: 24,
    fontWeight: 'bold',
  },
  drawerImage: {
    margin: 10,
    width: 80,
    height: 60,
  },
});

const LoginNavigator = createStackNavigator({
  Login: { screen: Auth,
    navigationOptions: ({ navigation }) => ({
      headerLeft: <NavbarDrawerIcon navigation={navigation} />,
    }),
  },
}, {
  navigationOptions: {
    headerStyle: {
      backgroundColor: '#512DA8',
    },
    headerTintColor: '#fff',
    headerTitleStyle: {
      color: '#fff',
    },
  },
});

const MenuNavigator = createStackNavigator({
  Menu: { screen: Menu,
    navigationOptions: ({ navigation }) => ({
      headerLeft: <NavbarDrawerIcon navigation={navigation} />,
    }),
  },
  DishDetails: { screen: DishDetails },
}, {
  initialRouteName: 'Menu',
  navigationOptions: {
    headerStyle: {
      backgroundColor: '#512DA8',
    },
    headerTintColor: '#fff',
    headerTitleStyle: {
      color: '#fff',
    },
  },
});

const HomeNavigator = createStackNavigator({
  Home: { screen: Home,
    navigationOptions: ({ navigation }) => ({
      headerLeft: <NavbarDrawerIcon navigation={navigation} />,
    }),
  },
}, {
  navigationOptions: {
    headerStyle: {
      backgroundColor: '#512DA8',
    },
    headerTintColor: '#fff',
    headerTitleStyle: {
      color: '#fff',
    },
  },
});

const ContactNavigator = createStackNavigator({
  Contact: { screen: Contact,
    navigationOptions: ({ navigation }) => ({
      headerLeft: <NavbarDrawerIcon navigation={navigation} />,
    }),
  },
}, {
  navigationOptions: {
    headerStyle: {
      backgroundColor: '#512DA8',
    },
    headerTintColor: '#fff',
    headerTitleStyle: {
      color: '#fff',
    },
  },
});

const AboutNavigator = createStackNavigator({
  About: { screen: About,
    navigationOptions: ({ navigation }) => ({
      headerLeft: <NavbarDrawerIcon navigation={navigation} />,
    }),
  },
}, {
  navigationOptions: {
    headerStyle: {
      backgroundColor: '#512DA8',
    },
    headerTintColor: '#fff',
    headerTitleStyle: {
      color: '#fff',
    },
  },
});

const ReservationNavigator = createStackNavigator({
  Reservation: { screen: Reservation,
    navigationOptions: ({ navigation }) => ({
      headerLeft: <NavbarDrawerIcon navigation={navigation} />,
    }),
  },
}, {
  navigationOptions: {
    headerStyle: {
      backgroundColor: '#512DA8',
    },
    headerTintColor: '#fff',
    headerTitleStyle: {
      color: '#fff',
    },
  },
});

const FavoritesNavigator = createStackNavigator({
  Favorites: { screen: Favorites,
    navigationOptions: ({ navigation }) => ({
      headerLeft: <NavbarDrawerIcon navigation={navigation} />,
    }),
  },
}, {
  navigationOptions: {
    headerStyle: {
      backgroundColor: '#512DA8',
    },
    headerTintColor: '#fff',
    headerTitleStyle: {
      color: '#fff',
    },
  },
});

const CustomDrawerContentComponent = props => (
  <ScrollView>
    <SafeAreaView style={styles.container} forceInset={{ top: 'always', horizontal: 'never' }}>
      <View style={styles.drawerHeader}>
        <View style={{ flex: 1 }}>
          <Image source={require('../../images/logo.png')} style={styles.drawerImage} />
        </View>
        <View style={{ flex: 2 }}>
          <Text style={styles.drawerHeaderText}>Ristorante Con Fusion</Text>
        </View>
      </View>
      <DrawerItems {...props} />
    </SafeAreaView>
  </ScrollView>
);

const MainNavigator = createDrawerNavigator({
  Login: {
    screen: LoginNavigator,
    navigationOptions: {
      title: 'Login',
      drawerLabel: 'Login',
      drawerIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="sign-in"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
  Home: {
    screen: HomeNavigator,
    navigationOptions: {
      title: 'Home',
      drawerLabel: 'Home',
      drawerIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="home"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
  About: {
    screen: AboutNavigator,
    navigationOptions: {
      title: 'About Us',
      drawerLabel: 'About Us',
      drawerIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="info-circle"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
  Menu: {
    screen: MenuNavigator,
    navigationOptions: {
      title: 'Menu',
      drawerLabel: 'Menu',
      drawerIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="list"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
  Contact: {
    screen: ContactNavigator,
    navigationOptions: {
      title: 'Contact Us',
      drawerLabel: 'Contact Us',
      drawerIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="address-card"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
  Favorites: {
    screen: FavoritesNavigator,
    navigationOptions: {
      title: 'My Favorites',
      drawerLabel: 'My Favorites',
      drawerIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="heart"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
  Reservation: {
    screen: ReservationNavigator,
    navigationOptions: {
      title: 'Reserve Table',
      drawerLabel: 'Reserve Table',
      drawerIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="cutlery"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
}, {
  initialRouteName: 'Home',
  drawerBackgroundColor: '#D1C4E9',
  contentComponent: CustomDrawerContentComponent,
});

class Main extends Component<null, ComponentProps> {
  configurePushNotifications = () => {
    PushNotification.configure({
      onNotification: (notification) => {
        console.log('NOTIFICATION: ', notification);
      },
      popInitialNotification: true,
    });
  }

  handleConnectivityChange = (connectionInfo) => {
    switch (connectionInfo.type) {
      case 'none':
        ToastAndroid.show('You are now offline!', ToastAndroid.LONG);
        break;

      case 'wifi':
        ToastAndroid.show('You are now connected to WiFi!', ToastAndroid.LONG);
        break;

      case 'cellular':
        ToastAndroid.show('You are now connected to cellular!', ToastAndroid.LONG);
        break;

      case 'unknown':
        ToastAndroid.show('You have now an unknown connection!', ToastAndroid.LONG);
        break;

      default:
        break;
    }
  }

  componentDidMount() {
    const { fetchDishes, fetchLeaders, fetchPromos, fetchComments } = this.props;
    fetchDishes();
    fetchLeaders();
    fetchPromos();
    fetchComments();
    this.configurePushNotifications();
    NetInfo.getConnectionInfo()
      .then((connectionInfo) => {
        let message = '';
        message += `Inital Network Conectivity Type: ${connectionInfo.type}`;
        message += `, effectiveType: ${connectionInfo.effectiveType}`;
        ToastAndroid.show(message, ToastAndroid.LONG);
      });
    NetInfo.addEventListener('connectionChange', this.handleConnectivityChange);
  }

  componentWillUnmount() {
    NetInfo.removeEventListener('connectionChange', this.handleConnectivityChange);
  }

  render() {
    return (
      <View style={{ flex: 1 }}>
        <MainNavigator />
      </View>
    );
  }
}

const mapActionsToProps = {
  fetchDishes: DishesActions.fetchDishes,
  fetchLeaders: LeadersActions.fetchLeaders,
  fetchPromos: PromotionsActions.fetchPromos,
  fetchComments: CommentsActions.fetchComments,
};

export default connect(null, mapActionsToProps)(Main);
