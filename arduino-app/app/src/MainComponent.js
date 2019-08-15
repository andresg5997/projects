import React, { Component } from 'react';
import { Text, View, ScrollView, StyleSheet, Image } from 'react-native';
import { Icon } from 'react-native-elements';
import { createStackNavigator, createDrawerNavigator, DrawerItems, SafeAreaView } from 'react-navigation';
import Login from './screens/auth/login';

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  drawerHeader: {
    backgroundColor: '#293E4A',
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

type DrawerProps = {
  navigation: Object,
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

const LoginNavigator = createStackNavigator({
  Login: { screen: Login,
    navigationOptions: ({ navigation }) => ({
      headerLeft: <NavbarDrawerIcon navigation={navigation} />,
    }),
  },
}, {
  navigationOptions: {
    headerStyle: {
      backgroundColor: '#293E4A',
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
          <Image source={require('../images/logo.png')} style={styles.drawerImage} />
        </View>
        <View style={{ flex: 2 }}>
          <Text style={styles.drawerHeaderText}>Pharmacy QR</Text>
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
}, {
  initialRouteName: 'Login',
  drawerBackgroundColor: '#3E5E70',
  contentComponent: CustomDrawerContentComponent,
});

class Main extends Component {
  render() {
    return (
      <View style={{ flex: 1 }}>
        <MainNavigator />
      </View>
    );
  }
}

export default Main;
