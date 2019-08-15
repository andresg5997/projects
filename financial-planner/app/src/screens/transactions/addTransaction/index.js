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
import TextInputMask from 'react-native-text-input-mask';
import fetchCategories from './api';
// styles
import cs from '../../../theme/common-styles';

type Props = {
  navigation: Object
}

class AddTransactionScreen extends Component<void, Props> {
  static navigationOptions = {
    title: 'Add Transaction',
  }

  state = {
    form: {
      amount: '',
      category: '',
      description: '',
    },
    typeSelected: '',
    categories: [],
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

  selectTransactionType = async (type) => {
    this.setState(prevState => ({
      ...prevState,
      typeSelected: !prevState.typeSelected,
      form: {
        ...prevState.form,
      },
    }));
    await this.setState(prevState => ({
      ...prevState,
      categories: fetchCategories(type),
      form: {
        ...prevState.state,
      },
    }));
  }

  submit = async () => {
    const { form } = this.state;
    console.warn(form);
  }

  render() {
    const {
      transactionType,
      categories,
      form: {
        amount,
        category,
        description
      },
    } = this.state;
    return (
      <Container>
        <Content padder>
          <View style={cs.card}>
            <View style={cs.cardHeader}>
              <H2 style={cs.colorPrimary}>
                New Transaction
              </H2>
            </View>
            <Item style={cs.item} floatingLabel>
              <Label>Masked Input Amount</Label>
              <TextInputMask
                refInput={(ref) => { this.input = ref; }}
                onChangeText={(formatted, extracted) => {
                  console.warn(formatted); // +1 (123) 456-78-90
                  console.warn(extracted); // 1234567890
                }}
                mask="+1 ([000]) [000] [00] [00]"
                keyboardType="numeric"
              />
            </Item>
            <Item style={cs.item} floatingLabel>
              <Label>Amount</Label>
              <Input
                value={amount}
                onChangeText={text => this.onChange('amount', text)}
                keyboardType="numeric"
              />
            </Item>
            <Label>Transaction Type</Label>
            <Picker
              onValueChange={value => this.selectTransactionType(value)}
              selectedValue={transactionType}
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
            <Item style={cs.item} floatingLabel>
              <Label>Description</Label>
              <Input value={description} onChangeText={text => this.onChange('description', text)} />
            </Item>
            <View style={cs.cardFooter}>
              <Button onPress={() => this.submit()}>
                <Text>
                  Submit
                </Text>
              </Button>
            </View>
          </View>
          <FlatList
            data={categories}
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
        </Content>
      </Container>
    );
  }
}

export default connect(null, null)(AddTransactionScreen);
