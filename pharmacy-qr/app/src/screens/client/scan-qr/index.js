import React, { Component } from 'react';
import { Text } from 'react-native';
import QRCodeScanner from 'react-native-qrcode-scanner';
import styles from './styles';

type Props = {
  navigation: Object,
}

class ScanQR extends Component<null, Props> {
  static navigationOptions = {
    title: 'QR Scan',
  }

  onSuccess = (event) => {
    const { navigation } = this.props;
    navigation.navigate('ConfirmScan', { data: event.data });
  }

  render() {
    return (
      <QRCodeScanner
        onRead={event => this.onSuccess(event)}
        bottomContent={(
          <Text style={styles.buttonText}>Scan the QR Code from the other device.</Text>
        )}
        checkAndroid6Permissions
      />
    );
  }
}

export default ScanQR;
