import React, { Component } from 'react';
import { View, ScrollView, FlatList, Alert, ToastAndroid } from 'react-native';
import { Input, ListItem } from 'react-native-elements';
import Swipeout from 'react-native-swipeout';
import { cs } from '../../../theme';
import { fetchFirestorePharmacyUsers, updateFirestorePharmacyUser } from './api';

class VerifyPharmacies extends Component {
  constructor(props) {
    super(props);
    this.state = {
      pharmacies: [],
      visiblePharmacies: [],
      searchForm: '',
    };
  }

  static navigationOptions = {
    title: 'Verify Pharmacies',
  }

  fetchPharmacies = async () => {
    try {
      const pharmaciesArray = [];
      const pharmaciesDocs = await fetchFirestorePharmacyUsers();
      pharmaciesDocs.forEach((pharmacy) => {
        pharmaciesArray.push({ id: pharmacy.id, ...pharmacy.data() });
      });
      this.setState(prevState => ({
        ...prevState,
        pharmacies: pharmaciesArray,
        visiblePharmacies: pharmaciesArray,
      }));
    } catch (e) {
      Alert.alert('ERROR', e);
    }
  }

  togglePharmacyActive = (id, itemActive) => {
    const data = { active: !itemActive };
    const { pharmacies } = this.state;
    try {
      updateFirestorePharmacyUser(id, data);
      const updatedPharmacies = [];
      pharmacies.forEach((pharmacy) => {
        const temporalPharmacy = pharmacy;
        if (pharmacy.id === id) {
          temporalPharmacy.active = !itemActive;
        }
        updatedPharmacies.push(temporalPharmacy);
      });
      this.setState(prevState => ({
        ...prevState,
        pharmacies: updatedPharmacies,
      }));
    } catch (e) {
      console.log(e);
    }
  }

  onSearchFormChange = (value) => {
    const { pharmacies } = this.state;
    this.setState(prevState => ({
      ...prevState,
      searchForm: value,
    }));
    if (value === '') {
      this.setState(prevState => ({
        ...prevState,
        visiblePharmacies: prevState.pharmacies,
      }));
    } else {
      const newPharmaciesResults = [];
      pharmacies.forEach((pharmacy) => {
        if (pharmacy.name.toLowerCase() === value.toLowerCase()) {
          newPharmaciesResults.push(pharmacy);
        }
      });
      this.setState(prevState => ({
        ...prevState,
        visiblePharmacies: newPharmaciesResults,
      }));
    }
  }

  componentDidMount() {
    this.fetchPharmacies();
  }

  render() {
    const { visiblePharmacies, searchForm } = this.state;
    const renderUserItem = ({ item, index }) => {
      const rightButton = [{
        text: item.active ? 'Deactivate' : 'Activate',
        type: item.active ? 'delete' : 'primary',
        onPress: () => {
          Alert.alert(
            item.active ? `Deactivate ${item.name}` : `Activate ${item.name}`,
            'Confirm?',
            [
              { text: 'Cancel', type: 'cancel' },
              { text: 'OK', onPress: () => this.togglePharmacyActive(item.id, item.active) },
            ],
          );
        },
      }];
      console.log(`item(${index}):`, item);
      return (
        <Swipeout right={rightButton} autoClose>
          <ListItem
            key={index}
            title={item.name ? item.name : 'Pharmacy'}
            subtitle={`${item.email} - ${item.phone}`}
            containerStyle={{ backgroundColor: item.active ? '#00FF0088' : '#FF000088' }}
            hideChevron
            onPress={() => {
              ToastAndroid.show(
                'Drag the element from right to left to interact with it!',
                ToastAndroid.SHORT,
              );
            }}
          />
        </Swipeout>
      );
    };
    return (
      <View style={[cs.container, { margin: 0 }]}>
        <Input
          placeholder="Search"
          leftIcon={{ type: 'font-awesome', name: 'search' }}
          onChangeText={text => this.onSearchFormChange(text)}
          value={searchForm}
          inputStyle={cs.formInputText}
          containerStyle={[cs.formInput, {
            borderRadius: 4,
            borderWidth: 1.5,
            borderColor: '#d6d7da',
          }]}
        />
        <ScrollView>
          <FlatList
            data={visiblePharmacies}
            renderItem={renderUserItem}
            keyExtractor={item => item.id.toString()}
          />
        </ScrollView>
      </View>
    );
  }
}

export default VerifyPharmacies;
