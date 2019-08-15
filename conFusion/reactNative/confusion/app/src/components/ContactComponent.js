import React, { Component } from 'react';
import { Text, Alert } from 'react-native';
import { Card, Button, Icon } from 'react-native-elements';
import * as Animatable from 'react-native-animatable';
import Mailer from 'react-native-mail';
import cs from '../theme/common-styles';

class Contact extends Component {
  static navigationOptions = {
    title: 'Contact',
  }

  sendMail = () => {
    Mailer.mail({
      subject: 'Enquiry',
      recipients: ['confusion@food.net'],
      body: 'To whom it may concern:',
    }, (error, event) => {
      Alert.alert(
        error,
        event,
        [
          { text: 'Ok' },
        ],
      );
    });
  }

  render() {
    return (
      <Animatable.View animation="fadeInDown" duration={2000} delay={500}>
        <Card title="Contact Information">
          <Text style={cs.cardText}>121, Clear Water Bay Road</Text>
          <Text style={cs.cardText}>Clear Water Bay, Kowloon</Text>
          <Text style={cs.cardText}>HONG KONG</Text>
          <Text style={cs.cardText}>Tel: +852 1234 5678</Text>
          <Text style={cs.cardText}>Fax: +852 8765 4321</Text>
          <Text style={cs.cardText}>Email: confusion@food.net</Text>
          <Button
            title="Send Email"
            buttonStyle={{ backgroundColor: '#512DA8' }}
            icon={<Icon name="envelope-o" type="font-awesome" color="white" />}
            onPress={() => this.sendMail()}
          />
        </Card>
      </Animatable.View>
    );
  }
}

export default Contact;
