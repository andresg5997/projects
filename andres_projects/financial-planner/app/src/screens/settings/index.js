import React, { Component } from 'react';
import { View } from 'react-native';
import { Text, Button } from 'native-base';
import variables from '../../theme/variables';

class SettingsScreen extends Component {
  state = {
    counter: 0,
  }

  add = () => {
    this.setState(prevState => ({
      ...prevState,
      counter: (prevState.counter + 1),
    }));
  }

  render() {
    const { counter } = this.state;

    return (
      <View>
        <View>
          <Text style={{ fontSize: 24, color: variables.brandPrimary, textAlign: 'center' }}>
            {counter}
          </Text>
        </View>
        <Text>
          Settingssss
        </Text>
        <Button onPress={() => this.add()}>
          <Text>
            Add
          </Text>
        </Button>
      </View>
    );
  }
}

export default SettingsScreen;
