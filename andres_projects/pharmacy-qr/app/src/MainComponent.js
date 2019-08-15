import React, { Component } from 'react';
import { Text, View, ScrollView, StyleSheet, Image } from 'react-native';
import { Icon } from 'react-native-elements';
import {
  createStackNavigator,
  createDrawerNavigator,
  createSwitchNavigator,
  DrawerItems,
  SafeAreaView } from 'react-navigation';
import { colors } from './theme';
// Auth
import LogOut from './screens/auth/logout';
import AuthTabNavigator from './screens/auth';
// Client
import ClientDashboard from './screens/client/dashboard';
import ShowMedicaments from './screens/client/medicaments/show';
import ScanQR from './screens/client/scan-qr';
import ConfirmScan from './screens/client/scan-qr/confirm';
// Pharmacy
import PharmacyDashboard from './screens/pharmacy/dashboard';
import CreateMedicament from './screens/pharmacy/medicament/create';
import ShowMedicament from './screens/pharmacy/medicament/show';
import EditMedicament from './screens/pharmacy/medicament/edit';
// Admin
import VerifyPharmacies from './screens/admin/verify-pharmacies';

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  drawerHeader: {
    backgroundColor: colors.mainMiddle,
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

const ClientNavigator = createStackNavigator({
  ClientDashboard: {
    screen: ClientDashboard,
    navigationOptions: ({ navigation }) => ({
      headerLeft: <NavbarDrawerIcon navigation={navigation} />,
    }),
  },
  ShowMedicaments,
  ScanQR,
  ConfirmScan,
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

const PharmacyNavigator = createStackNavigator({
  PharmacyDashboard: {
    screen: PharmacyDashboard,
    navigationOptions: ({ navigation }) => ({
      headerLeft: <NavbarDrawerIcon navigation={navigation} />,
    }),
  },
  CreateMedicament,
  ShowMedicament,
  EditMedicament,
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

const AdminNavigator = createStackNavigator({
  VerifyPharmacies: {
    screen: VerifyPharmacies,
    navigationOptions: ({ navigation }) => ({
      headerLeft: <NavbarDrawerIcon navigation={navigation} />,
    }),
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


const ClientDrawerNavigator = createDrawerNavigator({
  ClientDashboard: {
    screen: ClientNavigator,
    navigationOptions: {
      title: 'Dashboard',
      drawerLabel: 'Dashboard',
      drawerIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="dashboard"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
  LogOut: {
    screen: LogOut,
    navigationOptions: {
      title: 'Log Out',
      drawerLabel: 'Log Out',
      drawerIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="sign-out"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
}, {
  initialRouteName: 'ClientDashboard',
  drawerBackgroundColor: colors.mainLight,
  contentComponent: CustomDrawerContentComponent,
});

const PharmacyDrawerNavigator = createDrawerNavigator({
  PharmacyDashboard: {
    screen: PharmacyNavigator,
    navigationOptions: {
      title: 'Dashboard',
      drawerLabel: 'Dashboard',
      drawerIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="dashboard"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
  LogOut: {
    screen: LogOut,
    navigationOptions: {
      title: 'Log Out',
      drawerLabel: 'Log Out',
      drawerIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="sign-out"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
}, {
  initialRouteName: 'PharmacyDashboard',
  drawerBackgroundColor: colors.mainLight,
  contentComponent: CustomDrawerContentComponent,
});

const AdminDrawerNavigator = createDrawerNavigator({
  VerifyPharmacies: {
    screen: AdminNavigator,
    navigationOptions: {
      title: 'Dashboard',
      drawerLabel: 'Dashboard',
      drawerIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="dashboard"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
  LogOut: {
    screen: LogOut,
    navigationOptions: {
      title: 'Log Out',
      drawerLabel: 'Log Out',
      drawerIcon: ({ tintColor }) => ( // eslint-disable-line
        <Icon
          name="sign-out"
          type="font-awesome"
          size={24}
          color={tintColor}
        />
      ),
    },
  },
}, {
  initialRouteName: 'VerifyPharmacies',
  drawerBackgroundColor: colors.mainLight,
  contentComponent: CustomDrawerContentComponent,
});

const MainNavigator = createSwitchNavigator({
  Auth: AuthTabNavigator,
  Client: ClientDrawerNavigator,
  Pharmacy: PharmacyDrawerNavigator,
  Admin: AdminDrawerNavigator,
}, {
  initialRouteName: 'Auth',
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
