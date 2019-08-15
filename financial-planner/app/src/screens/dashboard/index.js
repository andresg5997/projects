// @flow
import React, { Component } from 'react';
import { Alert, View } from 'react-native';
import { connect } from 'react-redux';
import firebase from 'react-native-firebase';
import { Text, Button } from 'native-base';
import variables from '../../theme/variables';
import counterActions from '../../redux/actions/counter';

type Props = {
  navigation: Object
}

class DashboardScreen extends Component<void, Props> {
  static navigationOptions = {
    title: 'Dashboard',
  }

  state = {
    user: {},
  }

  componentDidMount() {
    this.setUser();
  }

  setUser = () => {
    const user = firebase.auth().currentUser;
    // we must have the user in redux with the info saved in the db
    this.setState({
      user,
    });
  }

  signOut = async () => {
    const { navigation } = this.props;
    try {
      await firebase.auth().signOut();
      navigation.navigate('Login');
    } catch (e) {
      Alert.alert('Error', e.message);
    }
  }

  add = () => {
    const { setCounter, counter } = this.props;
    setCounter((counter.counter + 1));
  }

  render() {
    const { navigation, counter } = this.props;
    const { user } = this.state;
    return (
      <View>
        <View>
          <Text>Welcome, {user.email}</Text>
          <Text style={{ fontSize: 24, color: variables.brandPrimary, textAlign: 'center' }}>
            {counter && counter.counter}
          </Text>
        </View>
        <Text>
          Pretty useless counter
        </Text>
        <Button onPress={() => this.add()}>
          <Text>
            Add
          </Text>
        </Button>
        <Button onPress={() => navigation.navigate('Settings')}>
          <Text>
            Settings
          </Text>
        </Button>
        <Button onPress={() => navigation.navigate('Transactions')}>
          <Text>
            Transactions
          </Text>
        </Button>
        <Button onPress={() => navigation.navigate('Categories')}>
          <Text>
            Categories
          </Text>
        </Button>
        <Button onPress={() => this.signOut()}>
          <Text>
            Sign Out
          </Text>
        </Button>
      </View>
    );
  }
}

const mapStateToProps = state => ({
  counter: state.counter,
});

const mapActionsToProps = {
  setCounter: counterActions.setCounter,
};

export default connect(mapStateToProps, mapActionsToProps)(DashboardScreen);
