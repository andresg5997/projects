import React, { Component } from 'react';
import { Text, View, Animated, Easing } from 'react-native';
import { Card } from 'react-native-elements';
import { connect } from 'react-redux';
import ImageLinksActions from '../redux/actions/imagelinks';
import Loading from './LoadingComponent';

type Props = {
  dishes: Object,
  leaders: Object,
  promotions: Object,
};

type ItemProps = {
  item: Object,
  isLoading: Boolean,
  imageLink: String,
  errMess: String,
}

function RenderItem(props:ItemProps) {
  const { item, isLoading, errMess, imageLink } = props;
  if (isLoading) return <Loading />;
  if (errMess) return <Text>{errMess}</Text>;
  if (item != null) {
    return (
      <Card
        featuredTitle={item.name}
        featuredSubtitle={item.designation}
        image={imageLink === '' ? require('../../images/blank.png') : { uri: imageLink }}
      >
        <Text style={{ margin: 10 }}>
          {item.description}
        </Text>
      </Card>
    );
  }
  return <View />;
}

class Home extends Component<null, Props> {
  constructor(props) {
    super(props);
    this.animatedValue = new Animated.Value(0);

    this.state = {
      propsLoaded: false,
    };
  }

  static navigationOptions = {
    title: 'Home',
  }

  fetchImageLinks = async (props) => {
    const { fetchImageLinks } = this.props;
    const { dish, leader, promotion } = props;
    this.setState(prevState => ({
      ...prevState,
      propsLoaded: true,
    }));
    fetchImageLinks(dish, leader, promotion);
  }

  animate = () => {
    this.animatedValue.setValue(0);
    Animated.timing(
      this.animatedValue,
      {
        toValue: 16,
        duration: 8000,
        easing: Easing.linear,
      },
    ).start(() => this.animate());
  }

  componentWillReceiveProps(nextProps) {
    const { dishes, leaders, promotions } = nextProps;
    const { propsLoaded } = this.state;
    // Checking if the function was already dispatched
    if (!propsLoaded) {
      // Checking if the props are already fetched
      if (!dishes.isLoading && !leaders.isLoading && !promotions.isLoading) {
        const data = {
          dish: dishes.dishes.filter(dish => dish.featured)[0],
          leader: leaders.leaders.filter(leader => leader.featured)[0],
          promotion: promotions.promotions.filter(promo => promo.featured)[0],
        };
        this.fetchImageLinks(data);
      }
    }
  }

  componentDidMount() {
    this.animate();
  }

  render() {
    const { dishes, leaders, promotions, imagelinks } = this.props;
    const xpos1 = this.animatedValue.interpolate({
      inputRange: [0, 1, 3, 5, 7, 9, 11, 13, 16],
      outputRange: [1200, 900, 600, 300, 0, -300, -600, -900, -1200],
    });
    const xpos2 = this.animatedValue.interpolate({
      inputRange: [0, 2, 4, 6, 8, 10, 12, 14, 16],
      outputRange: [1200, 900, 600, 300, 0, -300, -600, -900, -1200],
    });
    const xpos3 = this.animatedValue.interpolate({
      inputRange: [0, 3, 5, 7, 9, 11, 13, 15, 16],
      outputRange: [1200, 900, 600, 300, 0, -300, -600, -900, -1200],
    });
    return (
      <View style={{ flex: 1, flexDirection: 'row', justifyContent: 'center' }}>
        <Animated.View style={{ width: '100%', transform: [{ translateX: xpos1 }] }}>
          <RenderItem
            item={dishes.dishes.filter(dish => dish.featured)[0]}
            isLoading={dishes.isLoading}
            errMess={dishes.errMess}
            imageLink={imagelinks.imagelinks.dish}
          />
        </Animated.View>
        <Animated.View style={{ width: '100%', transform: [{ translateX: xpos2 }] }}>
          <RenderItem
            item={leaders.leaders.filter(leader => leader.featured)[0]}
            isLoading={leaders.isLoading}
            errMess={leaders.errMess}
            imageLink={imagelinks.imagelinks.leader}
          />
        </Animated.View>
        <Animated.View style={{ width: '100%', transform: [{ translateX: xpos3 }] }}>
          <RenderItem
            item={promotions.promotions.filter(promo => promo.featured)[0]}
            isLoading={promotions.isLoading}
            errMess={promotions.errMess}
            imageLink={imagelinks.imagelinks.promotion}
          />
        </Animated.View>
      </View>
    );
  }
}

const mapStateToProps = state => ({
  dishes: state.dishes,
  leaders: state.leaders,
  promotions: state.promotions,
  imagelinks: state.imagelinks,
});

const mapActionsToProps = {
  fetchImageLinks: ImageLinksActions.fetchImageLinks,
};

export default connect(mapStateToProps, mapActionsToProps)(Home);
