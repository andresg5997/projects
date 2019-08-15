import React, { Component } from 'react';
import { View, TouchableOpacity, FlatList } from 'react-native';
import { Container,
  Content,
  Picker,
  Label,
  Input,
  Item,
  H2,
  Button,
  Icon,
  Text,
} from 'native-base';
import { connect } from 'react-redux';
import categoriesActions from '../../../redux/actions/categories';
import createCategory from './api';
// styles
import cs from '../../../theme/common-styles';
import icons from '../../../shared/iconlist';
import CommonModal from '../../../components/commonModal';

type Props = {
  navigation: Object
}

class AddCategoryScreen extends Component<void, Props> {
  static navigationOptions = {
    title: 'Add Category',
  }

  state = {
    form: {
      name: '',
      type: 'income',
      icon: 'md-alarm',
    },
    iconModalIsOpen: false,
  }

  onChange = (field, value) => {
    this.setState(prevState => ({
      ...prevState,
      form: {
        ...prevState.form,
        [field]: value,
      },
    }));
  }

  toggleIconModal = () => {
    this.setState(prevState => ({
      ...prevState,
      iconModalIsOpen: !prevState.iconModalIsOpen,
      form: {
        ...prevState.form,
      },
    }));
  }

  selectIcon = (newIcon) => {
    this.setState(prevState => ({
      ...prevState,
      form: {
        ...prevState.form,
        icon: newIcon,
      },
    }));
    this.toggleIconModal();
  }

  submit = async () => {
    try {
      const { form } = this.state;
      const { navigation, addCategory } = this.props;
      const create = await createCategory(form);
      if (create) {
        addCategory(form);
        navigation.navigate('Categories');
      } else {
        console.warn('Couldn\'t submit the category');
      }
    } catch (error) {
      console.warn('Error submiting', error);
    }
  }

  render() {
    const { iconModalIsOpen, form: { name, type, icon } } = this.state;
    return (
      <Container>
        <Content padder>
          <View style={cs.card}>
            <View style={cs.cardHeader}>
              <H2 style={cs.colorPrimary}>
                New Category
              </H2>
            </View>
            <Item style={cs.item} floatingLabel>
              <Label>Name</Label>
              <Input value={name} onChangeText={text => this.onChange('name', text)} />
            </Item>
            <View>
              <Label>Type</Label>
              <Picker
                onValueChange={value => this.onChange('type', value)}
                selectedValue={type}
              >
                <Picker.Item
                  value="income"
                  label="Income"
                  key="income"
                />
                <Picker.Item
                  value="expense"
                  label="Expense"
                  key="expense"
                />
              </Picker>
            </View>
            <View>
              <Label>Selected Icon</Label>

              <Icon name={icon} />
              <Button onPress={() => this.toggleIconModal()}>
                <Text>
                  Change Icon
                </Text>
              </Button>
            </View>
            <View style={cs.cardFooter}>
              <Button onPress={() => this.submit()}>
                <Text>
                  Submit
                </Text>
              </Button>
            </View>
          </View>
          <CommonModal
            modalIsOpen={iconModalIsOpen}
            toggleModal={() => this.toggleIconModal()}
          >
            <View style={[cs.card]}>
              <FlatList
                data={icons}
                numColumns={4}
                columnWrapperStyle={{ flex: 1, justifyContent: 'space-between' }}
                keyExtractor={item => item}
                renderItem={({ item }) => (
                  <TouchableOpacity
                    onPress={() => this.selectIcon(item)}
                    key={item}
                    style={{ margin: 1 }}
                  >
                    <Icon name={item} style={[cs.primaryColor, { fontSize: 50 }]} />
                  </TouchableOpacity>
                )}
              />
            </View>
          </CommonModal>
        </Content>
      </Container>
    );
  }
}

const mapActionsToProps = {
  addCategory: categoriesActions.addCategory,
};

export default connect(null, mapActionsToProps)(AddCategoryScreen);
