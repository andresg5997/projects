import React, { Component } from 'react';
import { Text, FlatList, ScrollView } from 'react-native';
import { Card, ListItem, Button } from 'react-native-elements';
import getFirestorePharmacyData from './api';
import { cs } from '../../../../theme';
import styles from './styles';

type RenderMedicamentsProps = {
  medicaments: Array,
  pharmacyData: Object,
}

function RenderMedicaments(props: RenderMedicamentsProps) {
  const { medicaments, pharmacyData } = props;

  const capitalizeFirstLetter = string => string.charAt(0).toUpperCase() + string.slice(1);

  const renderMedicamentItem = ({ item, index }) => { // eslint-disable-line
    // To show data in subtitle
    let medicamentSubtitle = '';
    medicamentSubtitle += `Quantity: ${item.medicament.quantity}\n`;
    medicamentSubtitle += `Price: ${item.medicament.price} BsS.\n`;
    if (item.medicament.components) {
      medicamentSubtitle += 'Component: ';
      for (let i = 0; i < item.medicament.components.length; i += 1) {
        medicamentSubtitle += capitalizeFirstLetter(item.medicament.components[i]);
        if (i !== (item.medicament.components.length - 1)) {
          medicamentSubtitle += ', ';
        } else {
          medicamentSubtitle += '\n';
        }
      }
    }
    medicamentSubtitle += `Pharmacy Name: ${item.pharmacyData.name}`;
    if (item.pharmacyData.address) {
      medicamentSubtitle += `\nLocated in: ${item.pharmacyData.address}`;
    }

    // Return medicament item
    return (
      <ListItem
        containerStyle={{
          marginVertical: 5,
          borderRadius: 4,
          borderWidth: 1.5,
          borderColor: '#d6d7da',
        }}
        key={item.medicament.id}
        title={item.medicament.name}
        subtitle={medicamentSubtitle}
      />
    );
  };

  // Return message if there are no elements
  if (medicaments.length < 1) {
    return (
      <Text style={styles.noResultsTitle}>No Results</Text>
    );
  }

  // Return medicament list
  const medicamentsWithPharmacyData = [];
  medicaments.forEach((medicament) => {
    if (Object.keys(pharmacyData).length !== 0) {
      if (pharmacyData.names[medicament.pharmacy]) {
        medicamentsWithPharmacyData.push({
          medicament,
          pharmacyData: {
            name: pharmacyData.names[medicament.pharmacy],
            address: pharmacyData.addresses[medicament.pharmacy],
          },
        });
      } else {
        medicamentsWithPharmacyData.push({
          medicament,
          pharmacyData: {
            name: '',
            address: '',
          },
        });
      }
    }
  });
  return (
    <FlatList
      data={medicamentsWithPharmacyData}
      renderItem={renderMedicamentItem}
      keyExtractor={item => item.medicament.id}
    />
  );
}

type Props = {
  navigation: Object,
}

class ShowMedicaments extends Component<null, Props> {
  constructor(props) {
    super(props);

    this.state = {
      medicaments: [],
      pharmacyData: {},
    };
  }

  static navigationOptions = {
    title: 'Show Medicaments',
  }

  setPharmacyData = async (pharmacyId) => {
    try {
      const data = await getFirestorePharmacyData(pharmacyId);
      if (!data.address) data.address = '';
      this.setState(prevState => ({
        ...prevState,
        pharmacyData: {
          ...prevState.pharmacyData,
          names: {
            ...prevState.pharmacyData.names,
            [pharmacyId]: data.name,
          },
          addresses: {
            ...prevState.pharmacyData.addresses,
            [pharmacyId]: data.address,
          },
        },
      }));
    } catch (error) {
      this.setState(prevState => ({
        ...prevState,
        pharmacyData: {
          ...prevState.pharmacyData,
          names: {
            ...prevState.pharmacyData.names,
            [pharmacyId]: 'Error getting pharmacy data',
          },
          addresses: {
            ...prevState.pharmacyData.addresses,
          },
        },
      }));
      console.log('ERROR: ', error);
    }
  }

  componentDidMount() {
    const { navigation } = this.props;
    this.setState(prevState => ({
      ...prevState,
      medicaments: navigation.getParam('medicaments'),
    }));
    navigation.getParam('medicaments').forEach((medicament) => {
      this.setPharmacyData(medicament.pharmacy);
    });
  }

  render() {
    const { navigation } = this.props;
    const { medicaments, pharmacyData } = this.state;
    const name = navigation.getParam('name');
    const type = navigation.getParam('searchByComponent') ? 'Component' : 'Medicament';
    return (
      <ScrollView>
        <Card style={cs.container} title={`${type} ${name}`}>
          <RenderMedicaments
            medicaments={medicaments}
            pharmacyData={pharmacyData}
          />
        </Card>
        <Button
          title="Scan QR"
          onPress={() => navigation.navigate('ScanQR')}
          containerStyle={cs.formButton}
          buttonStyle={cs.formButtonStyle}
          icon={{ name: 'qrcode', type: 'font-awesome', color: 'white' }}
        />
      </ScrollView>
    );
  }
}

export default ShowMedicaments;
