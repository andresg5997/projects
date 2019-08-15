// @flow
import React, { Component } from 'react';
import { Alert, View, Switch } from 'react-native';
import { Container, Content, Text, Item, Input, Label, H1, Button, Picker } from 'native-base';
import firebase from 'react-native-firebase';
// components
import Loading from '../../../components/loading';
// styles
import cs from '../../../theme/common-styles';
import styles from './styles';
import variables from '../../../theme/variables';
import { createFirestoreUser, fetchCurrencies } from './api';

type Props = {
  navigation: Object
}

class RegisterScreen extends Component<void, Props> {
  static navigationOptions = {
    title: 'Sign Up',
  }

  state = {
    currencies: [],
    agree: false,
    isLoading: false,
    form: {
      name: '',
      currency: 'USD',
      email: '',
      password: '',
      confirmPassword: '',
    },
    errors: {
      name: false,
      email: false,
      password: false,
      agree: false,
      confirmPassword: false,
    },
  }

  componentDidMount() {
    this.fetchCurrencies();
  }

  fetchCurrencies = async () => {
    try {
      const currencies = [];
      const currenciesDocs = await fetchCurrencies();
      currenciesDocs.forEach((currencyDoc) => {
        currencies.push({ id: currencyDoc.id, ...currencyDoc.data() });
      });
      this.setState({
        currencies,
      });
    } catch (e) {
      console.warn(e);
    }
  }

  onChange = (field, value) => {
    this.setState(prevState => ({
      ...prevState,
      errors: { name: false, email: false, password: false },
      form: {
        ...prevState.form,
        [field]: value,
      },
    }));
  }

  switchAgree = (value) => {
    this.setState({
      agree: value,
    });
  }

  passesValidation = () => {
    const { agree, form } = this.state;
    const fields = Object.keys(form);
    let errorCount = 0;

    fields.forEach((field) => {
      // if field is empty, set to true the error
      if (field !== 'currency' && field !== 'confirmPassword' && !form[field]) {
        errorCount += 1;
        this.setState(prevState => ({
          ...prevState,
          errors: {
            ...prevState.errors,
            [field]: true,
          },
        }));
      }
      if (field === 'confirmPassword' && form.password !== form.confirmPassword) {
        errorCount += 1;
        this.setState(prevState => ({
          ...prevState,
          errors: {
            ...prevState.errors,
            [field]: true,
          },
        }));
      }
    });

    if (errorCount > 0) return false;

    if (agree === false) {
      Alert.alert('Error', 'You must agree with the terms and conditions');
      return false;
    }
    return true;
  }

  toggleLoading = () => {
    this.setState(prevState => ({
      ...prevState,
      isLoading: !prevState.isLoading,
    }));
  }

  createUser = async () => {
    const { form: { name, email, password, currency } } = this.state;

    if (!this.passesValidation()) return;
    this.toggleLoading();

    await firebase.auth().createUserAndRetrieveDataWithEmailAndPassword(email, password)
      .then(({ user }) => {
        user.updateProfile({ displayName: name });
        createFirestoreUser(user.uid, { currency });
      })
      .catch((e) => {
        this.toggleLoading();
        Alert.alert('Error', e.message);
      });
  }

  render() {
    const {
      errors,
      currencies,
      isLoading,
      agree,
      form: {
        name,
        currency,
        email,
        password,
        confirmPassword,
      },
    } = this.state;

    return (
      <Container>
        <Content padder>
          <View style={cs.card}>
            <View style={cs.cardHeader}>
              <H1 style={cs.colorPrimary}>Financial Planner</H1>
            </View>
            <Item style={cs.item} floatingLabel>
              <Label>Name</Label>
              <Input value={name} onChangeText={text => this.onChange('name', text)} />
            </Item>
            {errors.name && <Text style={{ color: variables.brandDanger }}>Name is required</Text>}
            <Item style={cs.item} floatingLabel>
              <Label>Email Address</Label>
              <Input value={email} keyboardType="email-address" onChangeText={text => this.onChange('email', text)} />
            </Item>
            {errors.email
              && <Text style={{ color: variables.brandDanger }}>Email is required</Text>}
            {errors.currency
              && <Text style={{ color: variables.brandDanger }}>Currency is required</Text>}
            <View>
              <Label>Currency</Label>
              <Picker
                onValueChange={value => this.onChange('currency', value)}
                selectedValue={currency}
              >
                {currencies.map(currencyItem => (
                  <Picker.Item
                    value={currencyItem.id}
                    label={`${currencyItem.id} (${currencyItem.name})`}
                    key={currencyItem.id}
                  />
                ))}
              </Picker>
            </View>
            <Item style={cs.item} floatingLabel>
              <Label>Password</Label>
              <Input secureTextEntry value={password} onChangeText={text => this.onChange('password', text)} />
            </Item>
            {errors.password
              && <Text style={{ color: variables.brandDanger }}>The password is required</Text>}
            <Item style={cs.item} floatingLabel>
              <Label>Confirm Password</Label>
              <Input secureTextEntry value={confirmPassword} onChangeText={text => this.onChange('confirmPassword', text)} />
            </Item>
            {errors.confirmPassword
              && <Text style={{ color: variables.brandDanger }}>Passwords must match</Text>}
            <View style={cs.cardFooter}>
              <View style={styles.rowBetween}>
                <Text>
                  I accept the <Text style={styles.link}>terms and conditions</Text>
                </Text>
                <Switch
                  thumbTintColor={variables.brandPrimary}
                  value={agree}
                  onValueChange={value => this.switchAgree(value)}
                />
              </View>
              <Button full rounded style={{ marginTop: 7 }} onPress={() => this.createUser()}>
                <Text>Sign Up</Text>
              </Button>
            </View>
          </View>
        </Content>
        <Loading
          isLoading={isLoading}
        />
      </Container>
    );
  }
}

export default RegisterScreen;
