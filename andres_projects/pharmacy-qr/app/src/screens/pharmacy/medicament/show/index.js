import React, { Component } from 'react';
import { ScrollView, View, Text, FlatList } from 'react-native';
import { Icon, Card } from 'react-native-elements';
import QRCode from 'react-native-qrcode';
import { cs } from '../../../../theme';
import styles from './styles';

type Props = {
  navigation: Object,
}

class CreateMedicament extends Component<null, Props> {
  static navigationOptions = ({ navigation }) => ({
    title: navigation.getParam('medicament').name,
    headerRight: (
      <Icon
        iconStyle={{ marginRight: 10 }}
        name="edit"
        size={24}
        color="white"
        onPress={() => navigation.navigate(
          'EditMedicament',
          { medicament: navigation.getParam('medicament') },
        )}
      />
    ),
  });

  capitalizeFirstLetter = string => string.charAt(0).toUpperCase() + string.slice(1);

  render() {
    const { navigation } = this.props;
    const {
      id,
      quantity,
      price,
      components,
      name } = navigation.getParam('medicament');
    const componentsNames = [];
    components.forEach((component, index) => {
      componentsNames.push({ name: this.capitalizeFirstLetter(component), key: index });
    });
    const renderComponent = ({ item, index }) => (
      <Text style={styles.componentItem} key={item}>
        <Text style={styles.componentIndicator}>{(index + 1)}. </Text>{item.name}
      </Text>
    );
    return (
      <ScrollView contentContainerStyle={cs.container}>
        <Card title={name}>
          <View style={styles.qrview}>
            <QRCode value={id} size={200} />
          </View>
          <Text style={styles.quantityTitle}>
            Remaining: <Text style={styles.quantityNumber}>{quantity}</Text>
          </Text>
          <Text style={styles.priceTitle}>
            Price: <Text style={styles.priceNumber}>{price} BsS.</Text>
          </Text>
          <Text style={styles.componentsTitle}>Components:</Text>
          <FlatList
            data={componentsNames}
            renderItem={renderComponent}
            keyExtractor={item => item.key.toString()}
          />
        </Card>
        <View style={{ marginBottom: 50 }} />
      </ScrollView>
    );
  }
}

export default CreateMedicament;
