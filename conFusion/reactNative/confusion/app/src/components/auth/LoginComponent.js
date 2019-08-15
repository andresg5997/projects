import React, { Component } from 'react';
import { View, Button, StyleSheet } from 'react-native';
import { Input, CheckBox, Icon } from 'react-native-elements';
import SecureStorage from 'react-native-secure-storage';
// react-native-secure-storage is used because the project
// is ejected and doesn't rely on the expo SDK

const styles = StyleSheet.create({
  container: {
    justifyContent: 'center',
    margin: 20,
  },
  formInput: {
    margin: 20,
  },
  formCheckbox: {
    margin: 20,
    backgroundColor: null,
  },
  formButton: {
    margin: 60,
  },
});

class LoginTab extends Component {
  constructor(props) {
    super(props);

    this.state = {
      username: '',
      password: '',
      remember: false,
    };
  }

  static navigationOptions = {
    title: 'Login',
    tabBarIcon: ({ tintColor }) => (
      <Icon
        name="sign-in"
        type="font-awesome"
        size={24}
        iconStyle={{ color: tintColor }}
      />
    ),
  };

  handleLogin = () => {
    const { username, password, remember } = this.state;
    if (remember) {
      const userdata = JSON.stringify({ username, password });
      SecureStorage.setItem('loginuserinfo', userdata);
    } else {
      SecureStorage.removeItem('loginuserinfo');
    }
  }

  getUserInfo = async () => {
    const userdataJSON = await SecureStorage.getItem('loginuserinfo');
    if (userdataJSON) {
      const userdata = JSON.parse(userdataJSON);
      this.setState(prevState => ({
        ...prevState,
        username: userdata.username,
        password: userdata.password,
      }));
    }
  }

  componentDidMount() {
    this.getUserInfo();
  }

  render() {
    const { username, password, remember } = this.state;
    return (
      <View style={styles.container}>
        <Input
          placeholder="Username"
          leftIcon={{ type: 'font-awesome', name: 'user-o' }}
          onChangeText={text => this.setState(prevState => ({ ...prevState, username: text }))}
          value={username}
          containerStyle={styles.formInput}
        />
        <Input
          placeholder="Password"
          leftIcon={{ type: 'font-awesome', name: 'key' }}
          onChangeText={text => this.setState(prevState => ({ ...prevState, password: text }))}
          value={password}
          containerStyle={styles.formInput}
        />
        <CheckBox
          title="Remember Me"
          center
          checked={remember}
          onPress={() => this.setState(prevState => (
            { ...prevState, remember: !prevState.remember }
          ))}
          containerStyle={styles.formCheckbox}
        />
        <View style={styles.formButton}>
          <Button
            onPress={() => this.handleLogin()}
            title="Login"
            color="#512AD8"
          />
        </View>
      </View>
    );
  }
}

export default LoginTab;
