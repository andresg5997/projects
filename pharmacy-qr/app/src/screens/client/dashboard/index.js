import React, { Component } from 'react';
import { View, ScrollView, Alert, ToastAndroid } from 'react-native';
import { Input, Card, Button, CheckBox } from 'react-native-elements';
import searchFirestoreMedicaments from './api';
import Loading from '../../../components/loading';
import { cs } from '../../../theme';

type Props = {
  navigation: Object,
}

class ClientDashboard extends Component<null, Props> {
  constructor(props) {
    super(props);

    this.state = {
      form: {
        name: '',
      },
      searchByComponent: false,
      isLoading: false,
    };
  }

  static navigationOptions = {
    title: 'Dashboard',
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

  toggleSearchByComponent = () => {
    this.setState(prevState => ({
      ...prevState,
      searchByComponent: !prevState.searchByComponent,
    }));
  }

  handleSearchSubmit = async () => {
    const { form: { name }, searchByComponent } = this.state;
    if (name === '') {
      ToastAndroid.show('Must insert a name!', ToastAndroid.SHORT);
      return;
    }
    const { navigation } = this.props;
    this.toggleLoading();
    try {
      const medicaments = [];
      const medicamentsDocs = await searchFirestoreMedicaments(
        name.toLowerCase(),
        searchByComponent,
      );
      medicamentsDocs.forEach((medicament) => {
        medicaments.push({ id: medicament.id, ...medicament.data() });
      });
      this.toggleLoading();
      navigation.navigate('ShowMedicaments', { name, medicaments, searchByComponent });
    } catch (error) {
      this.toggleLoading();
      Alert.alert('ERROR', error);
    }
  }

  render() {
    const { form: { name }, searchByComponent, isLoading } = this.state;
    const { navigation } = this.props;
    return (
      <ScrollView>
        <Card title="Search Medicament" style={cs.container}>
          <Input
            placeholder={searchByComponent ? 'Component Name' : 'Medicament Name'}
            leftIcon={{ type: 'font-awesome', name: 'cubes' }}
            onChangeText={text => this.onFormChange('name', text)}
            value={name}
            inputStyle={cs.formInputText}
            containerStyle={cs.formInput}
          />
          <ScrollView horizontal>
            <View style={{ flexDirection: 'row', justifyContent: 'space-between' }}>
              <CheckBox
                title="Medicament"
                checked={!searchByComponent}
                onPress={() => this.toggleSearchByComponent()}
                containerStyle={cs.formCheckbox}
              />
              <CheckBox
                title="Component"
                checked={searchByComponent}
                onPress={() => this.toggleSearchByComponent()}
                containerStyle={cs.formCheckbox}
              />
            </View>
          </ScrollView>
          <Button
            title="Search"
            onPress={() => this.handleSearchSubmit()}
            containerStyle={cs.formButton}
            buttonStyle={cs.formButtonStyle}
            icon={{ name: 'search', type: 'font-awesome', color: 'white' }}
          />
          <Button
            title="Scan QR"
            onPress={() => navigation.navigate('ScanQR')}
            containerStyle={cs.formButton}
            buttonStyle={cs.formButtonStyle}
            icon={{ name: 'qrcode', type: 'font-awesome', color: 'white' }}
          />
        </Card>
        { isLoading && <Loading /> }
      </ScrollView>
    );
  }
}

export default ClientDashboard;
