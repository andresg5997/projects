import React, { Component } from 'react';
import { ScrollView, View, Text, Alert, FlatList } from 'react-native';
import { Card, Button } from 'react-native-elements';
import Loading from '../../../../components/loading';
import { cs } from '../../../../theme';
import styles from './styles';
import { getFirestoreMedicament, buyFirestoreMedicament } from './api';

type Props = {
  navigation: Object,
}

class ConfirmScan extends Component<null, Props> {
  constructor(props) {
    super(props);

    this.state = {
      medicament: '',
      isLoading: true,
    };
  }

  static navigationOptions = {
    title: 'Scan Confirm',
  }

  buyMedicament = async (medicament) => {
    const { navigation } = this.props;
    try {
      const newQuantity = (medicament.quantity - 1);
      await buyFirestoreMedicament(medicament.id, newQuantity);
      Alert.alert(
        'Success',
        'The medicament was purchased successfully',
        [{ text: 'Ok', onPress: () => navigation.navigate('ClientDashboard') }],
      );
    } catch (err) {
      Alert.alert('ERROR', err);
    }
  }

  getScannedMedicament = async (medicamentId) => {
    try {
      const medicament = await getFirestoreMedicament(medicamentId);
      if (medicament) {
        this.setState(prevState => ({
          ...prevState,
          medicament: { id: medicament.id, ...medicament.data() },
        }));
      }
    } catch (err) {
      Alert.alert('ERROR', err);
    }
    this.toggleLoading();
  }

  toggleLoading = () => {
    this.setState(prevState => ({
      ...prevState,
      isLoading: !prevState.isLoading,
    }));
  }

  componentDidMount() {
    const { navigation } = this.props;
    const data = navigation.getParam('data');
    this.getScannedMedicament(data);
  }

  render() {
    const { medicament, isLoading } = this.state;
    const RenderScannedItem = () => {
      const renderComponent = ({ item, index }) => (
        <Text style={styles.componentItem}>
          <Text style={styles.componentIndicator}>{(index + 1)}. </Text>{item}
        </Text>
      );
      if (!isLoading) {
        if (medicament) {
          return (
            <View>
              <Text style={styles.nameTitle}>
                Name: <Text style={styles.nameValue}>{medicament.name}</Text>
              </Text>
              <Text style={styles.quantityTitle}>
                Remaining: <Text style={styles.quantityNumber}>{medicament.quantity}</Text>
              </Text>
              <Text style={styles.priceTitle}>
                Price: <Text style={styles.priceNumber}>{medicament.price} BsS.</Text>
              </Text>
              <Text style={styles.componentsTitle}>Components:</Text>
              <FlatList
                data={medicament.components}
                renderItem={renderComponent}
                keyExtractor={item => item}
              />
              <Button
                title="Buy Medicament"
                onPress={() => Alert.alert(
                  'Buy Medicament',
                  'Are you sure?',
                  [
                    { text: 'Cancel', type: 'cancel' },
                    { text: 'Ok', onPress: () => this.buyMedicament(medicament) },
                  ],
                )}
                containerStyle={cs.formButton}
                buttonStyle={cs.formButtonStyle}
                icon={{ name: 'shopping-cart', type: 'font-awesome', color: 'white' }}
              />
            </View>
          );
        }
        return (
          <View>
            <Text style={styles.failedMessageTitle}>No medicaments found!</Text>
            <Text style={styles.failedMessageSubtitle}>
              It could be because the QR Code is not from
              a medicament or the medicaments was deleted
            </Text>
          </View>
        );
      }
      return <Loading />;
    };

    return (
      <ScrollView>
        <Card style={cs.container}>
          <RenderScannedItem />
        </Card>
      </ScrollView>
    );
  }
}

export default ConfirmScan;
