import React, { Component } from 'react';
import SecureStorage from 'react-native-secure-storage';
import Loading from '../../../components/loading';
import logOut from './api';

type Props = {
  navigation: Object,
}

class LogOut extends Component<null, Props> {
  deleteSecureStoreUser = () => {
    SecureStorage.removeItem('userinfo');
  }

  componentWillMount() {
    logOut();
    this.deleteSecureStoreUser();
    const { navigation } = this.props;
    navigation.navigate('Auth');
  }

  render() {
    return (
      <Loading />
    );
  }
}

export default LogOut;
