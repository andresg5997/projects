// @flow
import React, { Component } from 'react';
import { Alert, View } from 'react-native';
import { Content, Container, Item, Label, Input, Button, Text } from 'native-base';
import firebase from 'react-native-firebase';
// styles
import cs from '../../../theme/common-styles';
import Loading from '../../../components/loading';

type Props = {
  navigation: Object
}

class ForgotPasswordScreen extends Component<void, Props> {
  static navigationOptions = {
    title: 'Forgot Password',
  }

  state = {
    isLoading: false,
    form: {
      email: '',
    },
  }

  toggleLoading() {
    this.setState(prevState => ({
      ...prevState,
      isLoading: !prevState.isLoading,
      form: {
        ...prevState.form,
      },
    }));
  }

  changeValue = (field, text) => {
    this.setState(prevState => ({
      ...prevState,
      form: {
        ...prevState.form,
        [field]: text,
      },
    }));
  }

  sendResetLink = () => {
    const { form: { email } } = this.state;
    const { navigation } = this.props;
    if (!email) {
      Alert.alert('Error', 'You must enter an email');
      return;
    }
    this.toggleLoading();
    firebase.auth().sendPasswordResetEmail(email)
      .then(() => {
        this.toggleLoading();
        Alert.alert('Done!', 'Check your email for the reset link', [{
          text: 'OK',
          onPress: () => navigation.navigate('Login'),
        }]);
      })
      .catch((e) => {
        this.toggleLoading();
        Alert.alert('Error', e.message);
      });
  }

  render() {
    const { isLoading } = this.state;
    return (
      <Container>
        <Content padder>
          <View style={cs.card}>
            <Text>Enter your email address to reset your password.</Text>
            <Item floatingLabel>
              <Label>Email address</Label>
              <Input
                keyboardType="email-address"
                onChangeText={text => this.changeValue('email', text)}
              />
            </Item>
            <Button full style={{ marginTop: 10 }} onPress={() => this.sendResetLink()}>
              <Text>Send reset link</Text>
            </Button>
          </View>
        </Content>
        <Loading
          isLoading={isLoading}
        />
      </Container>
    );
  }
}

export default ForgotPasswordScreen;
