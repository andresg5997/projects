import React, { Component } from 'react';
import { View, Text, FlatList, ScrollView, Alert } from 'react-native';
import firebase from 'react-native-firebase';
import { ListItem } from 'react-native-elements';
import { connect } from 'react-redux';
import Swipeout from 'react-native-swipeout';
import * as Animatable from 'react-native-animatable';
import Loading from './LoadingComponent';
import FavoritesActions from '../redux/actions/favorites';

type Props = {
  navigation: Object,
}

class Favorites extends Component<null, Props> {
  constructor(props) {
    super(props);

    this.state = {
      imageLinks: {},
    };
  }

  static navigationOptions = {
    title: 'My Favorites',
  };

  componentDidMount() {
    const { dishes, favorites } = this.props;
    if (!dishes.isLoading) {
      favorites.forEach((favorite) => {
        const selectedDish = dishes.dishes.filter(dish => dish.id === favorite)[0];
        firebase.storage().ref(selectedDish.image).getDownloadURL()
          .then((url) => {
            this.setState(prevState => ({
              ...prevState,
              imageLinks: {
                [favorite]: url,
                ...prevState.imageLinks,
              },
            }));
          })
          .catch((err) => {
            console.log('FIREBASE ERROR: ', err);
          });
      });
    }
  }

  render() {
    const { navigation, deleteFavorite, dishes, favorites } = this.props;
    const renderMenuItem = ({ item, index }) => {
      const { imageLinks } = this.state;
      const rightButton = [
        {
          text: 'Delete',
          type: 'delete',
          onPress: () => {
            Alert.alert(
              'Delete Favorite',
              `Are you sure you want to delete the dish ${item.name}?`,
              [
                {
                  text: 'Cancel',
                  type: 'cancel',
                },
                {
                  text: 'Ok',
                  onPress: () => deleteFavorite(item.id),
                },
              ],
            );
          },
        },
      ];
      return (
        <Swipeout right={rightButton} autoClose>
          <Animatable.View animation="fadeInDown" duration={2000} delay={500} >
            <ListItem
              key={index}
              title={item.name}
              subtitle={item.description}
              hideChevron
              onPress={() => navigation.navigate('DishDetails', { dishId: item.id })}
              leftAvatar={{ source: { uri: imageLinks[item.id] ? imageLinks[item.id] : 'NoImage' } }}
            />
          </Animatable.View>
        </Swipeout>
      );
    };

    if (dishes.isLoading) return <Loading />;
    if (dishes.errMess) {
      return (
        <View>
          <Text>{dishes.errMess}</Text>
        </View>
      );
    }
    return (
      <ScrollView>
        <FlatList
          data={dishes.dishes.filter(dish => favorites.some(el => el === dish.id))}
          renderItem={renderMenuItem}
          keyExtractor={item => item.id.toString()}
        />
      </ScrollView>
    );
  }
}

const mapStateToProps = state => ({
  dishes: state.dishes,
  favorites: state.favorites,
});

const mapActionsToProps = {
  deleteFavorite: FavoritesActions.deleteFavorite,
};

export default connect(mapStateToProps, mapActionsToProps)(Favorites);
