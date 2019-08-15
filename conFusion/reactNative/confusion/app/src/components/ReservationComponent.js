import React, { Component } from 'react';
import { Alert, Text, View, ScrollView, StyleSheet, Picker, Switch, Button } from 'react-native';
import * as Animatable from 'react-native-animatable';
import DatePicker from 'react-native-datepicker';
import PushNotification from 'react-native-push-notification';
import RNCalendarEvents from 'react-native-calendar-events';

// The following modules are being used because the project
// is ejected and doesn't rely on the expo SDK:
// react-native-push-notification
// react-native-calendar-events

// This last module (react-native-calendar-events) is the
// one in charge of creating the calendar events

const styles = StyleSheet.create({
  formRow: {
    alignItems: 'center',
    justifyContent: 'center',
    flex: 1,
    flexDirection: 'row',
    margin: 20,
  },
  formLabel: {
    fontSize: 20,
    flex: 2,
  },
  formItem: {
    flex: 1,
  },
});

class ReservationComponent extends Component {
  constructor(props) {
    super(props);

    this.state = {
      guests: 1,
      smoking: false,
      date: '',
    };
  }

  static navigationOptions = {
    title: 'Reserve Table',
  }

  sendNotification = (guests, smoking, date) => {
    const message = `Number of Guests: ${guests}\nSmoking? ${smoking}\nDate and Time: ${date}`;
    PushNotification.localNotification({
      title: 'Your reservation!',
      message: 'This are your reservation details',
      bigText: message,
      subText: 'conFusion',
      color: '#512DA8',
    });
  }

  submitReservation = () => {
    const { guests, smoking, date } = this.state;
    this.addReservationToCalendar(date);
    this.sendNotification(guests, smoking, date);
    this.resetForm();
  }

  obtainCalendarPermission = () => {
    RNCalendarEvents.authorizeEventStore();
  }

  addReservationToCalendar = (date) => {
    const formattedStartDate = new Date(date);
    formattedStartDate.setTime(
      formattedStartDate.getTime() + formattedStartDate.getTimezoneOffset() * 60 * 1000,
    );
    const formattedEndDate = new Date(formattedStartDate.getTime() + 2 * 3600 * 1000);
    RNCalendarEvents.saveEvent('Con Fusion Table Reservation', {
      startDate: formattedStartDate.toISOString(),
      endDate: formattedEndDate.toISOString(),
      location: '121, Clear Water Bay Road, Clear Water Bay, Kowloon, Hong Kong',
    });
  }

  handleReservation = () => {
    const { guests, smoking, date } = this.state;
    this.obtainCalendarPermission();
    const message = `Number of Guests: ${guests}\nSmoking? ${smoking}\nDate and Time: ${date}`;
    Alert.alert(
      'Your reservation OK?',
      message,
      [
        { text: 'Cancel', onPress: () => this.resetForm() },
        { text: 'OK', onPress: () => this.submitReservation() },
      ],
      { cancelable: false },
    );
  }

  resetForm = () => {
    this.setState(prevState => ({
      ...prevState,
      guests: 1,
      smoking: false,
      date: '',
    }));
  }

  render() {
    const { guests, smoking, date } = this.state;
    return (
      <ScrollView>
        <Animatable.View animation="zoomIn" duration={2000} delay={500}>
          <View style={styles.formRow}>
            <Text style={styles.formLabel}>Number of Guests</Text>
            <Picker
              style={styles.formItem}
              selectedValue={guests}
              onValueChange={
                itemValue => this.setState(prevState => ({ ...prevState, guests: itemValue }))
              }
            >
              <Picker.Item label="1" value="1" />
              <Picker.Item label="2" value="2" />
              <Picker.Item label="3" value="3" />
              <Picker.Item label="4" value="4" />
              <Picker.Item label="5" value="5" />
              <Picker.Item label="6" value="6" />
            </Picker>
          </View>
          <View style={styles.formRow}>
            <Text style={styles.formLabel}>Smoking/Non-Smoking?</Text>
            <Switch
              style={styles.formItem}
              value={smoking}
              onTintColor="#512DA8"
              onValueChange={
                value => this.setState(prevState => ({ ...prevState, smoking: value }))
              }
            />
          </View>
          <View style={styles.formRow}>
            <Text style={styles.formLabel}>Date and Time</Text>
            <DatePicker
              style={styles.formItem}
              date={date}
              format=""
              mode="datetime"
              placeholder="select day and time"
              minDate="2017-01-01"
              confirmBtnText="Confirm"
              cancelBtnText="Cancel"
              customStyles={{
                dateIcon: {
                  position: 'absolute',
                  left: 0,
                  top: 4,
                  marginLeft: 0,
                },
                dateInput: {
                  marginLeft: 36,
                },
              }}
              onDateChange={
                selectedDate => this.setState(prevState => ({ ...prevState, date: selectedDate }))
              }
            />
          </View>
          <View style={styles.formRow}>
            <Button
              onPress={() => this.handleReservation()}
              title="Reserve"
              color="#512DA8"
              accesibilityLabel="Learn more about this purple button"
            />
          </View>
        </Animatable.View>
      </ScrollView>
    );
  }
}

export default ReservationComponent;
