// @flow
import React, { Component } from 'react';
import { View, FlatList, StyleSheet, ActivityIndicator } from 'react-native';
import { Container, Content, Text, Fab } from 'native-base';
import Icon from 'react-native-ionicons';
import { connect } from 'react-redux';
// styles
import variables from '../../theme/variables';
import cs from '../../theme/common-styles';

type Props = {
  navigation: Object
};

function TransactionCards({ data, transactionColor, isLoading }) {
  const styles = StyleSheet.create({
    transactionCard: {
      flex: 1,
      borderWidth: 1,
    },
    verticalCenter: {
      flex: 1,
      justifyContent: 'center',
      alignItems: 'center',
    },
    textCenter: {
      textAlign: 'center',
    },
  });
  if (!isLoading) {
    return (
      <FlatList
        inverted
        numColumns={3}
        columnWrapperStyle={{ flex: 1 }}
        data={data}
        extraData={this.props}
        keyExtractor={item => item.id}
        renderItem={({ item }) => (
          <View style={[cs.card, styles.transactionCard, { borderColor: transactionColor }]}>
            <View style={styles.verticalCenter}>
              <Text style={styles.textCenter}>{item.name}</Text>
            </View>
          </View>
        )}
      />
    );
  }
  if (isLoading) {
    return (
      <ActivityIndicator size="large" color={variables.brandPrimary} />
    );
  }
}

class TransactionsScreen extends Component<void, Props> {
  static navigationOptions = {
    title: 'Recurrent Transactions',
  }

  render() {
    const { navigation } = this.props;
    const trial = [
      {name: 'hola'},
      {name: 'adios'}
    ];
    return (
      <Container>
        <Content padder>
          <View style={cs.card}>
            <TransactionCards data={trial} transactionColor="blue" isLoading={false} />
          </View>
        </Content>
        <Fab
          position="bottomRight"
          style={cs.backgroundPrimary}
          onPress={() => navigation.navigate('AddTransaction')}
        >
          <Icon name="add" />
        </Fab>
      </Container>
    );
  }
}

export default connect(null, null)(TransactionsScreen);
