import React, { Component } from 'react';
import { ScrollView, Alert, TouchableOpacity, Text } from 'react-native';
import { Input, Button } from 'react-native-elements';
import firebase from 'react-native-firebase';
import SecureStorage from 'react-native-secure-storage';
import Loading from '../../../components/loading';
import { colors, cs } from '../../../theme';
import createFirestoreClientUser from './api';

type Props = {
  navigation: Object,
}

class RegisterTab extends Component<null, Props> {
  constructor(props) {
    super(props);
    this.state = {
      form: {
        name: '',
        email: '',
        password: '',
        confirmPassword: '',
      },
      isLoading: false,
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

  setSecureStoreUser = (email, password, type) => {
    const data = JSON.stringify({ email, password, type });
    SecureStorage.setItem('userinfo', data);
  }

  formValidation = () => {
    const { form: { email, password, confirmPassword } } = this.state;
    if (!email.length) {
      Alert.alert('ERROR', 'Email is required');
      return false;
    }
    if (!password.length) {
      Alert.alert('ERROR', 'Password is required');
      return false;
    }
    if (!confirmPassword.length) {
      Alert.alert('ERROR', 'Password confirm is required');
      return false;
    }
    if (password !== confirmPassword) {
      Alert.alert('ERROR', 'Passwords are not the same');
      return false;
    }
    return true;
  }

  handleRegister = async () => {
    const { form: { name, email, password } } = this.state;
    const { navigation } = this.props;
    if (!this.formValidation()) return;
    this.toggleLoading();
    firebase.auth().createUserWithEmailAndPassword(email, password)
      .then(({ user }) => {
        user.updateProfile({ displayName: name });
        try {
          const data = {
            name,
            type: 'client',
          };
          createFirestoreClientUser(user.uid, data);
          this.setSecureStoreUser(email, password, 'client');
          this.toggleLoading();
          navigation.navigate('ClientDashboard');
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

  render() {
    const { form: { name, email, password, confirmPassword }, isLoading } = this.state;
    const { navigation } = this.props;
    return (
      <ScrollView contentContainerStyle={cs.container}>
        <Input
          placeholder="Full Name"
          leftIcon={{ type: 'font-awesome', name: 'drivers-license-o' }}
          onChangeText={text => this.onFormChange('name', text)}
          value={name}
          inputStyle={cs.formInputText}
          containerStyle={cs.formInput}
        />
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
        <Input
          placeholder="Confirm Password"
          secureTextEntry
          leftIcon={{ type: 'font-awesome', name: 'key' }}
          onChangeText={text => this.onFormChange('confirmPassword', text)}
          value={confirmPassword}
          inputStyle={cs.formInputText}
          containerStyle={cs.formInput}
        />
        <Button
          title="Register"
          onPress={() => this.handleRegister()}
          containerStyle={cs.formButton}
          buttonStyle={cs.formButtonStyle}
          icon={{ name: 'user-plus', type: 'font-awesome', color: 'white' }}
        />
        { isLoading && <Loading /> }
        <TouchableOpacity
          style={{ justifyContent: 'center', margin: 40 }}
          onPress={() => navigation.navigate('PharmacyRegister')}
        >
          <Text style={{ color: 'blue' }}>Register as a Pharmacy?</Text>
        </TouchableOpacity>
      </ScrollView>
    );
  }
}

export default RegisterTab;
