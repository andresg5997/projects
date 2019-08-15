// @flow
import React, { Component } from 'react';
import { View, FlatList, StyleSheet, ActivityIndicator } from 'react-native';
import { Container, Content, Text, Fab } from 'native-base';
import Icon from 'react-native-ionicons';
import { connect } from 'react-redux';
// styles
import variables from '../../theme/variables';
import categoriesActions from '../../redux/actions/categories';
import cs from '../../theme/common-styles';
import fetchFirestoreCategories from './api';

type Props = {
  navigation: Object
};

function CategoryCards({ data, categoryColor, isLoading }) {
  const styles = StyleSheet.create({
    categoryCard: {
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
          <View style={[cs.card, styles.categoryCard, { borderColor: categoryColor }]}>
            <View style={styles.verticalCenter}>
              <Icon name={item.icon} style={{ color: categoryColor }} />
            </View>
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

class CategoriesScreen extends Component<void, Props> {
  static navigationOptions = {
    title: 'Categories',
  }

  state = {
    userCategories: [],
    isLoading: true,
  }

  componentDidMount() {
    this.fetchCategories();
  }

  toggleLoading = () => {
    this.setState(prevState => ({
      ...prevState,
      isLoading: !prevState.isLoading,
    }));
  }

  fetchCategories = async () => {
    try {
      const categories = {
        incomes: [],
        expenses: [],
      };
      const { fetchCategories } = this.props;
      const categoriesDocs = await fetchFirestoreCategories();
      categoriesDocs.forEach((categoryDoc) => {
        if (categoryDoc.data().type === 'income') {
          categories.incomes.push({ id: categoryDoc.id, ...categoryDoc.data() });
        } else {
          categories.expenses.push({ id: categoryDoc.id, ...categoryDoc.data() });
        }
      });
      console.log(categories);
      fetchCategories(categories);
    } catch (e) {
      console.warn('Error fetching categories', e);
    }
    this.toggleLoading();
  }

  render() {
    const { isLoading } = this.state;
    const { navigation, categories } = this.props;

    return (
      <Container>
        <Content padder>
          <View style={cs.card}>
            <Text style={{ color: 'green' }}>
              Incomes
            </Text>
            <CategoryCards data={categories.categories.incomes} categoryColor="green" isLoading={isLoading} />
          </View>
          <View style={cs.card}>
            <Text style={{ color: 'red' }}>
              Expenses
            </Text>
            <CategoryCards data={categories.categories.expenses} categoryColor="red" isLoading={isLoading} />
          </View>
        </Content>
        <Fab
          position="bottomRight"
          style={cs.backgroundPrimary}
          onPress={() => navigation.navigate('AddCategory')}
        >
          <Icon name="add" />
        </Fab>
      </Container>
    );
  }
}

const mapStateToProps = state => ({
  categories: state.categories,
});

const mapActionsToProps = {
  fetchCategories: categoriesActions.fetchCategories,
};

export default connect(mapStateToProps, mapActionsToProps)(CategoriesScreen);
