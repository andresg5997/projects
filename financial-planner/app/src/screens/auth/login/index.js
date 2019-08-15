// @flow
import React, { Component } from 'react';
import { Alert, View, TouchableOpacity } from 'react-native';
import { Container, Content, Text, Item, Input, Label, H1, Button } from 'native-base';
import firebase from 'react-native-firebase';
// styles
import cs from '../../../theme/common-styles';
import styles from './styles';
import Loading from '../../../components/loading';

type Props = {
  navigation: Object
}

class LoginScreen extends Component<void, Props> {
  static navigationOptions = {
    title: 'Log In',
  }

  state = {
    form: {
      email: '',
      password: '',
    },
    isLoading: false,
  }

  toggleLoading() {
    const { isLoading } = this.state;
    this.setState({
      isLoading: !isLoading,
    });
  }

  passesValidation = () => {
    const { form: { email, password } } = this.state;
    if (!email.length) {
      Alert.alert('Error', 'Email is required');
      return false;
    }
    if (!password.length) {
      Alert.alert('Error', 'Password is required');
      return false;
    }
    return true;
  }

  logIn = async () => {
    const { form: { email, password } } = this.state;
    const { navigation } = this.props;
    if (!this.passesValidation()) return;
    this.toggleLoading();
    firebase.auth().signInAndRetrieveDataWithEmailAndPassword(email, password)
      .then(() => navigation.navigate('Dashboard'))
      .catch((e) => {
        this.toggleLoading();
        Alert.alert('Error', e.message);
      });
  }

  onChange = (field, value) => {
    this.setState(prevState => ({
      ...prevState,
      form: {
        ...prevState.form,
        [field]: value,
      },
    }));
  }

  render() {
    const { navigation } = this.props;
    const { form: { email, password }, isLoading } = this.state;

    return (
      <Container>
        <Content padder>
          <View style={cs.card}>
            <View style={cs.cardHeader}>
              <H1 style={cs.colorPrimary}>Financial Planner</H1>
            </View>
            <Item style={cs.item} floatingLabel>
              <Label>Email Address</Label>
              <Input value={email} keyboardType="email-address" onChangeText={text => this.onChange('email', text)} />
            </Item>
            <Item style={cs.item} floatingLabel>
              <Label>Password</Label>
              <Input secureTextEntry value={password} onChangeText={text => this.onChange('password', text)} />
            </Item>
            <Loading isLoading={isLoading} />
            <View style={cs.cardFooter}>
              <Button full rounded onPress={() => this.logIn()}>
                <Text>Log In</Text>
              </Button>
              <View style={styles.links}>
                <TouchableOpacity onPress={() => navigation.navigate('ForgotPassword')}>
                  <Text style={cs.colorInfo}>Did you forget your password?</Text>
                </TouchableOpacity>
                <View style={{ flexDirection: 'row' }}>
                  <Text>
                    Dont have an account?
                  </Text>
                  <TouchableOpacity style={{ marginLeft: 5 }} onPress={() => navigation.navigate('Register')}>
                    <Text style={cs.colorInfo}>Create one!</Text>
                  </TouchableOpacity>
                </View>
              </View>
            </View>
          </View>
        </Content>
      </Container>
    );
  }
}

export default LoginScreen;
