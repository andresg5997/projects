import React, { Component } from 'react';
import { ScrollView, Alert } from 'react-native';
import { Input, Button } from 'react-native-elements';
import firebase from 'react-native-firebase';
import SecureStorage from 'react-native-secure-storage';
import Loading from '../../../components/loading';
import { cs } from '../../../theme';
import getFirestoreUser from './api';

class LoginTab extends Component {
  constructor(props) {
    super(props);
    this.state = {
      form: {
        email: '',
        password: '',
      },
      isLoading: false,
      oldUserStored: false,
    };
  }

  toggleLoading = () => {
    this.setState(prevState => ({
      ...prevState,
      isLoading: !prevState.isLoading,
    }));
  }

  onFormChange = (field, value) => {
    this.setState(prevState => ({
      ...prevState,
      form: {
        ...prevState.form,
        [field]: value,
      },
    }));
  }

  checkSecureStoreUser = async () => {
    const { navigation } = this.props;
    const userDataJSON = await SecureStorage.getItem('userinfo');
    if (userDataJSON) {
      this.setState(prevState => ({
        ...prevState,
        oldUserStored: true,
      }));
      const userData = JSON.parse(userDataJSON);
      const { email, password, type } = userData;
      firebase.auth().signInWithEmailAndPassword(email, password, type)
        .then(() => {
          if (type === 'client') {
            this.toggleLoading();
            navigation.navigate('Client');
          }
          if (type === 'pharmacy') {
            this.toggleLoading();
            navigation.navigate('Pharmacy');
          }
        })
        .catch((err) => {
          this.toggleLoading();
          Alert.alert('ERROR', err.message);
        });
    }
  }

  setSecureStoreUser = (email, password, type) => {
    const data = JSON.stringify({ email, password, type });
    SecureStorage.setItem('userinfo', data);
  }

  formValidation = () => {
    const { form: { email, password } } = this.state;
    if (!email.length) {
      Alert.alert('ERROR', 'Email is required');
      return false;
    }
    if (!password.length) {
      Alert.alert('ERROR', 'Password is required');
      return false;
    }
    return true;
  }

  handleLogin = async () => {
    const { form: { email, password } } = this.state;
    const { navigation } = this.props;
    if (!this.formValidation()) return;
    this.toggleLoading();
    firebase.auth().signInWithEmailAndPassword(email, password)
      .then(async ({ user }) => {
        try {
          const userDoc = await getFirestoreUser(user.uid);
          const userData = userDoc.data();
          if (userData.admin) {
            this.toggleLoading();
            navigation.navigate('Admin');
          } else {
            this.setSecureStoreUser(email, password, userData.type);
          }
          if (userData.type === 'client') {
            this.toggleLoading();
            navigation.navigate('Client', { name: user.displayName });
          }
          if (userData.type === 'pharmacy') {
            if (userData.active) {
              this.toggleLoading();
              navigation.navigate('Pharmacy', { name: user.displayName });
            } else {
              this.toggleLoading();
              Alert.alert('WARNING', 'This pharmacy is not active!');
            }
          }
        } catch (e) {
          this.toggleLoading();
          Alert.alert('ERROR', e);
        }
      })
      .catch((err) => {
        this.toggleLoading();
        Alert.alert('ERROR', err.message);
      });
  }

  componentWillMount() {
    this.checkSecureStoreUser();
  }

  render() {
    const { form: { email, password }, isLoading, oldUserStored } = this.state;
    if (!oldUserStored) {
      return (
        <ScrollView contentContainerStyle={cs.container}>
          <Input
            placeholder="Email"
            leftIcon={{ type: 'font-awesome', name: 'user-o' }}
            onChangeText={text => this.onFormChange('email', text)}
            value={email}
            inputStyle={cs.formInputText}
            containerStyle={cs.formInput}
          />
          <Input
            placeholder="Password"
            secureTextEntry
            leftIcon={{ type: 'font-awesome', name: 'key' }}
            onChangeText={text => this.onFormChange('password', text)}
            value={password}
            inputStyle={cs.formInputText}
            containerStyle={cs.formInput}
          />
          {isLoading ? <Loading /> : (
            <Button
              title="Login"
              onPress={() => this.handleLogin()}
              containerStyle={cs.formButton}
              buttonStyle={cs.formButtonStyle}
              icon={{ name: 'sign-in', type: 'font-awesome', color: 'white' }}
            />
          )}
        </ScrollView>
      );
    }
    return (<Loading />);
  }
}

export default LoginTab;
