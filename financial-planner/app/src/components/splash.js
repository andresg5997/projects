import React from 'react';
import {
  ActivityIndicator,
  StatusBar,
  StyleSheet,
  View,
} from 'react-native';
import firebase from 'react-native-firebase';
// styles
import variables from '../theme/variables';

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
});

class SplashScreen extends React.Component {
  constructor(props) {
    super(props);
    this.checkLoggedIn();
  }

  checkLoggedIn = async () => {
    const { navigation } = this.props;
    try {
      await firebase.auth().onAuthStateChanged(user => navigation.navigate(user ? 'App' : 'Auth'));
      // should set the user extra info to redux
    } catch (e) {
      console.warn(e);
    }
  }

  render() {
    return (
      <View style={styles.container}>
        <ActivityIndicator size="large" color={variables.brandPrimary} />
        <StatusBar barStyle="default" />
      </View>
    );
  }
}

export default SplashScreen;
