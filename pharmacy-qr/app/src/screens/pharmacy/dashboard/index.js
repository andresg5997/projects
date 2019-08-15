import React, { Component } from 'react';
import { View, ScrollView, FlatList, Alert } from 'react-native';
import { ListItem } from 'react-native-elements';
import ActionButton from 'react-native-action-button';
import { connect } from 'react-redux';
import firebase from 'react-native-firebase';
import { colors } from '../../../theme';
import getFirestoreMedicaments from './api';
import MedicamentsActions from '../../../redux/actions/medicaments';

type Props = {
  navigation: Object,
}

class PharmacyDashboard extends Component<null, Props> {
  constructor(props) {
    super(props);

    this.state = {
      medicaments: [],
      pharmacyName: '',
    };
  }

  static navigationOptions = {
    title: 'Pharmacy Dashboard',
  };

  fetchPharmacyMedicaments = async () => {
    try {
      const { addMedicaments } = this.props;
      const pharmacyId = await firebase.auth().currentUser.uid;
      const medicamentsDocs = await getFirestoreMedicaments(pharmacyId);
      const medicaments = [];
      medicamentsDocs.forEach((medicament) => {
        medicaments.push({ id: medicament.id, ...medicament.data() });
      });
      addMedicaments(medicaments);
    } catch (error) {
      Alert.alert('ERROR', error);
    }
  }

  getPharmacyName = async () => {
    const pharmacyName = await firebase.auth().currentUser.displayName;
    this.setState(prevState => ({
      ...prevState,
      pharmacyName,
    }));
  }

  capitalizeFirstLetter = string => string.charAt(0).toUpperCase() + string.slice(1);

  componentDidMount() {
    this.getPharmacyName();
    this.fetchPharmacyMedicaments();
  }

  render() {
    const { navigation, medicaments } = this.props;
    const renderMedicamentItem = ({ item, index }) => {
      let subtitle = '';
      item.components.forEach((component, arrayIndex) => {
        subtitle += this.capitalizeFirstLetter(component);
        if (arrayIndex !== (item.components.length - 1)) {
          subtitle += ', ';
        }
      });
      return (
        <ListItem
          key={index}
          containerStyle={{
            marginVertical: 5,
            borderRadius: 4,
            borderWidth: 0.5,
            borderColor: '#d6d7da',
          }}
          title={item.name}
          subtitle={subtitle}
          onPress={() => navigation.navigate('ShowMedicament', { medicament: item })}
        />
      );
    };

    return (
      <View style={{ flex: 1 }}>
        <ScrollView>
          <FlatList
            data={medicaments.medicaments}
            renderItem={renderMedicamentItem}
            keyExtractor={item => item.id.toString()}
          />
        </ScrollView>
        <ActionButton
          buttonColor={colors.mainDark}
          onPress={() => navigation.navigate('CreateMedicament')}
        />
      </View>
    );
  }
}

const mapStateToProps = state => ({
  medicaments: state.medicaments,
});

const mapActionsToProps = {
  addMedicaments: MedicamentsActions.addMedicaments,
};

export default connect(mapStateToProps, mapActionsToProps)(PharmacyDashboard);
