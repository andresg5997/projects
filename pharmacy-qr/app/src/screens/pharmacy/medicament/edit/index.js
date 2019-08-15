import React, { Component } from 'react';
import {
  View,
  ScrollView,
  FlatList,
  Text,
  Alert,
  ToastAndroid,
  TouchableOpacity } from 'react-native';
import { Input, Button, Icon } from 'react-native-elements';
import { connect } from 'react-redux';
import Loading from '../../../../components/loading';
import editFirestoreMedicament from './api';
import { cs } from '../../../../theme';
import styles from './styles';
import MedicamentActions from '../../../../redux/actions/medicaments';

type Props = {
  navigation: Props,
};

class EditMedicament extends Component<null, Props> {
  constructor(props) {
    super(props);

    this.state = {
      form: {
        name: '',
        components: [],
        price: '',
        quantity: '',
      },
      isLoading: false,
      componentField: '',
    };
  }

  static navigationOptions = {
    title: 'Edit Medicament',
  }

  onChange = (field, value) => {
    this.setState(prevState => ({
      ...prevState,
      [field]: value,
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

  onFormNumberChange = (field, value) => {
    let newText = '';
    const numbers = '0123456789.,';

    for (let i = 0; i < value.length; i += 1) {
      if (numbers.indexOf(value[i]) > -1) {
        newText += value[i];
      } else {
        ToastAndroid.show(
          'Must use only numbers!',
          ToastAndroid.SHORT,
        );
      }
    }
    this.onFormChange(field, newText);
  }

  toggleLoading = () => {
    this.setState(prevState => ({
      ...prevState,
      isLoading: !prevState.isLoading,
    }));
  }

  editMedicament = async () => {
    const { form } = this.state;
    const { editMedicament, navigation } = this.props;
    const medicamentId = navigation.getParam('medicament').id;
    this.toggleLoading();
    try {
      const { components, ...formWithoutComponents } = form;
      const data = formWithoutComponents;
      data.lowercase = form.name.toLowerCase();
      data.components = [];
      components.forEach((component, index) => {
        data.components[index] = {
          name: component.toLowerCase(),
        };
      });
      await editFirestoreMedicament(data, medicamentId);
      editMedicament(form, medicamentId);
      ToastAndroid.show(
        'The medicament has been edited successfully!',
        ToastAndroid.LONG,
      );
      navigation.navigate('PharmacyDashboard');
    } catch (e) {
      this.toggleLoading();
      Alert.alert('ERROR', e);
    }
  }

  removeComponent = async (index) => {
    const { form: { components } } = this.state;
    const newComponents = components;
    newComponents.splice(index, 1);
    if (newComponents !== []) {
      await this.setState(prevState => ({
        ...prevState,
        form: {
          ...prevState.form,
          components: newComponents,
        },
      }));
    } else {
      await this.setState(prevState => ({
        ...prevState,
        form: {
          ...prevState.form,
          components: [],
        },
      }));
    }
  }

  addComponent = () => {
    const { componentField } = this.state;
    if (componentField !== '') {
      this.setState(prevState => ({
        ...prevState,
        form: {
          ...prevState.form,
          components: prevState.form.components.concat(prevState.componentField),
        },
        componentField: '',
      }));
    } else {
      ToastAndroid.show(
        'Must insert component name in input field!',
        ToastAndroid.SHORT,
      );
    }
  }

  capitalizeFirstLetter = string => string.charAt(0).toUpperCase() + string.slice(1);

  componentDidMount() {
    const { navigation } = this.props;
    const { name, quantity, components, price } = navigation.getParam('medicament');
    const componentsNames = [];
    components.forEach((component) => {
      componentsNames.push(this.capitalizeFirstLetter(component));
    });
    this.setState(prevState => ({
      ...prevState,
      form: {
        name,
        quantity: quantity.toString(),
        components: componentsNames,
        price,
      },
    }));
  }

  render() {
    const {
      form: {
        name,
        components,
        price,
        quantity,
      },
      componentField,
      isLoading } = this.state;
    const renderComponentItem = ({ item, index }) => (
      <View style={styles.componentItem}>
        <Text><Text style={styles.boldText}>{(index + 1)}.</Text> {item}</Text>
        <TouchableOpacity onPress={() => this.removeComponent(index)}>
          <Icon name="close" type="font-awesome" color="red" />
        </TouchableOpacity>
      </View>
    );
    const RenderComponentList = () => {
      if (components.length < 1) {
        return (
          <View />
        );
      }
      return (
        <FlatList
          data={components}
          renderItem={renderComponentItem}
          keyExtractor={props => props}
        />
      );
    };
    return (
      <ScrollView contentContainerStyle={cs.container}>
        <Input
          placeholder="Name"
          leftIcon={{ type: 'font-awesome', name: 'medkit' }}
          onChangeText={text => this.onFormChange('name', text)}
          value={name}
          inputStyle={cs.formInputText}
          containerStyle={cs.formInput}
        />
        <Input
          placeholder="Component"
          leftIcon={{ type: 'font-awesome', name: 'cubes' }}
          onChangeText={text => this.onChange('componentField', text)}
          value={componentField}
          inputStyle={cs.formInputText}
          containerStyle={cs.formInput}
        />
        <View style={{ flexDirection: 'row', justifyContent: 'center' }}>
          <Text style={styles.componentListTitle}>List of Components</Text>
          <Button
            title="Add Component"
            onPress={() => this.addComponent()}
            buttonStyle={styles.addComponentButton}
            titleStyle={styles.addComponentButtonTitle}
          />
        </View>
        <RenderComponentList />
        <Input
          placeholder="Price"
          keyboardType="numeric"
          leftIcon={{ type: 'font-awesome', name: 'money' }}
          onChangeText={text => this.onFormNumberChange('price', text)}
          value={price}
          inputStyle={cs.formInputText}
          containerStyle={cs.formInput}
        />
        <Input
          placeholder="Quantity"
          keyboardType="numeric"
          leftIcon={{ type: 'font-awesome', name: 'database' }}
          onChangeText={text => this.onFormNumberChange('quantity', text)}
          value={quantity}
          inputStyle={cs.formInputText}
          containerStyle={cs.formInput}
        />
        { isLoading ? <Loading /> : (
          <Button
            title="Edit Medicament"
            onPress={() => this.editMedicament()}
            buttonStyle={styles.editMedicamentButton}
            containerStyle={styles.editMedicamentButtonContainer}
          />
        ) }
      </ScrollView>
    );
  }
}

const mapActionsToProps = {
  editMedicament: MedicamentActions.editMedicament,
};

export default connect(null, mapActionsToProps)(EditMedicament);
