import React, { Component } from 'react';
import { FlatList, Text } from 'react-native';
import firebase from 'react-native-firebase';
import * as Animatable from 'react-native-animatable';
import { Tile } from 'react-native-elements';
import { connect } from 'react-redux';
import Loading from './LoadingComponent';

type Props = {
  onDishSelect: Function,
  dishes: Object,
}

class Menu extends Component<void, Props> {
  constructor(props) {
    super(props);
    this.state = {
      listData: [],
    };
  }

  static navigationOptions = {
    title: 'Menu',
  };

  createDishListObject = async (dish) => {
    firebase.storage().ref(dish.image).getDownloadURL()
      .then((url) => {
        const data = {
          imageLink: url,
          dish,
        };
        this.setState(prevState => ({
          ...prevState,
          listData: [...prevState.listData, data],
        }));
      });
  }

  componentWillMount() {
    const { dishes } = this.props;
    if (!dishes.isLoading) {
      for (let i = 0; i < dishes.dishes.length; i += 1) {
        this.createDishListObject(dishes.dishes[i]);
      }
    }
  }

  render() {
    const { listData } = this.state;
    const { navigation, dishes } = this.props;
    const renderMenuItem = ({ item, index }) => (
      <Animatable.View animation="fadeInDown" duration={2000} delay={500} >
        <Tile
          key={index}
          title={item.dish.name}
          caption={item.dish.description}
          featured
          onPress={() => navigation.navigate('DishDetails', { dishId: item.dish.id })}
          imageSrc={{ uri: item.imageLink }}
        />
      </Animatable.View>
    );
    if (listData === []) return <Loading />;
    if (dishes.errMess) return <Text>{dishes.errMess}</Text>;
    return (
      <FlatList
        data={listData}
        renderItem={renderMenuItem}
        keyExtractor={item => item.dish.id.toString()}
      />
    );
  }
}

const mapStateToProps = state => ({
  dishes: state.dishes,
});

export default connect(mapStateToProps)(Menu);
