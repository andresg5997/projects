// @flow
import React, { Component } from 'react';
import { View } from 'react-native';
import { connect } from 'react-redux';
import { Text, Button } from 'native-base';
import variables from '../../theme/variables';
import counterActions from '../../redux/actions/counter';
import firebase from 'react-native-firebase';


type Props = {
  navigation: Object
}

class DashboardScreen extends Component<void, Props> {
  add = () => {
    const { setCounter, counter } = this.props;
    setCounter((counter.counter + 1));
    firebase.auth().signInAnonymously()
      .then((user) => {
        alert(user.isAnonymous);
        console.log(user.isAnonymous);
      });
  }

  render() {
    const { navigation, counter } = this.props;
    return (
      <View>
        <View>
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
            Go away
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
