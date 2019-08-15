import React, { Component } from 'react';
import { ScrollView, Alert } from 'react-native';
import { Input, Button } from 'react-native-elements';
import firebase from 'react-native-firebase';
import Loading from '../../../../components/loading';
import { cs } from '../../../../theme';
import createFirestorePharmacyUser from './api';

class PharmacyRegister extends Component {
  constructor(props) {
    super(props);
    this.state = {
      form: {
        name: '',
        email: '',
        phone: '',
        address: '',
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
    const { form: { name, email, phone, address, password } } = this.state;
    const { navigation } = this.props;
    if (!this.formValidation()) return;
    this.toggleLoading();
    firebase.auth().createUserWithEmailAndPassword(email, password)
      .then(({ user }) => {
        user.updateProfile({ displayName: name, phoneNumber: phone });
        try {
          const data = {
            name,
            phone,
            email,
            address,
            type: 'pharmacy',
            active: false,
          };
          createFirestorePharmacyUser(user.uid, data);
          this.toggleLoading();
          Alert.alert(
            'Pharmacy registered successfully',
            'This register will be verified by the administrators',
            [
              { text: 'OK', onPress: () => navigation.navigate('Login') },
            ],
          );
        } catch (e) {
          this.toggleLoading();
          Alert.alert('DATABASE ERROR', e);
        }
      })
      .catch((err) => {
        this.toggleLoading();
        Alert.alert('AUTH ERROR', err.message);
      });
  }

  render() {
    const {
      form: {
        name,
        email,
        phone,
        address,
        password,
        confirmPassword,
      },
      isLoading } = this.state;
    return (
      <ScrollView contentContainerStyle={cs.container}>
        <Input
          placeholder="Name"
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
          placeholder="Phone Number"
          leftIcon={{ type: 'font-awesome', name: 'phone' }}
          onChangeText={text => this.onFormChange('phone', text)}
          value={phone}
          inputStyle={cs.formInputText}
          containerStyle={cs.formInput}
        />
        <Input
          placeholder="Address"
          leftIcon={{ type: 'font-awesome', name: 'address-card' }}
          onChangeText={text => this.onFormChange('address', text)}
          value={address}
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
          title="Register Pharmacy"
          onPress={() => this.handleRegister()}
          containerStyle={[{ marginBottom: 50 }, cs.formButton]}
          buttonStyle={cs.formButtonStyle}
          icon={{ name: 'user-plus', type: 'font-awesome', color: 'white' }}
        />
        { isLoading && <Loading /> }
      </ScrollView>
    );
  }
}

export default PharmacyRegister;
