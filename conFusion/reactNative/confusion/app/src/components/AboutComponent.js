import React, { Component } from 'react';
import { Text, ScrollView, FlatList } from 'react-native';
import { Card, ListItem } from 'react-native-elements';
import { connect } from 'react-redux';
import * as Animatable from 'react-native-animatable';
import firebase from 'react-native-firebase';
import cs from '../theme/common-styles';
import Loading from './LoadingComponent';

type Props = {
  leaders: Object
}

type LeaderItemProps = {
  item: Object,
  index: Number,
}

type LeaderCardProps = {
  listData: Array,
  errMess: String,
}

function renderLeaderItem(props:LeaderItemProps) {
  const { item, index } = props;
  return (
    <ListItem
      key={index}
      title={item.leader.name}
      subtitle={item.leader.description}
      hideChevron
      leftAvatar={{ source: { uri: item.imageLink } }}
    />
  );
}

function RenderLeadershipCard(props:LeaderCardProps) {
  const { listData, errMess } = props;
  if (listData === []) {
    return (
      <Card
        title="Corporate Leadership"
      >
        <Loading />
      </Card>
    );
  }
  if (errMess) {
    return (
      <Animatable.View animation="fadeInDown" duration={2000} delay={500} >
        <Card title="Corporate Leadership">
          <FlatList
            data={listData}
            renderItem={renderLeaderItem}
            keyExtractor={item => item.leader.id.toString()}
          />
        </Card>
      </Animatable.View>
    );
  }
  return (
    <Animatable.View animation="fadeInDown" duration={2000} delay={500} >
      <Card title="Corporate Leadership">
        <FlatList
          data={listData}
          renderItem={renderLeaderItem}
          keyExtractor={item => item.leader.id.toString()}
        />
      </Card>
    </Animatable.View>
  );
}

class About extends Component<null, Props> {
  constructor(props) {
    super(props);

    this.state = {
      listData: [],
    };
  }

  static navigationOptions = {
    title: 'About Us',
  }

  createLeaderListObject = async (leader) => {
    firebase.storage().ref(leader.image).getDownloadURL()
      .then((url) => {
        const data = {
          imageLink: url,
          leader,
        };
        this.setState(prevState => ({
          ...prevState,
          listData: [...prevState.listData, data],
        }));
      });
  }

  componentWillMount() {
    const { leaders } = this.props;
    if (!leaders.isLoading) {
      for (let i = 0; i < leaders.leaders.length; i += 1) {
        this.createLeaderListObject(leaders.leaders[i]);
      }
    }
  }

  render() {
    const { leaders } = this.props;
    const { listData } = this.state;
    return (
      <ScrollView>
        <Card
          title="Our History"
        >
          <Text style={cs.cardText}>Started in 2010, Ristorante con Fusion quickly established
          itself as a culinary icon par excellence in Hong Kong. With its unique brand of world
          fusion cuisine that can be found nowhere else, it enjoys patronage from the A-list
          clientele in Hong Kong. Featuring four of the best three-star Michelin chefs in the world,
          you never know what will arrive on your plate the next time you visit us.
          </Text>
          <Text style={cs.cardText}>The restaurant traces its humble beginnings to The Frying Pan,
          a successful chain started by our CEO, Mr. Peter Pan, that featured for the first time
          the world&apos;s best cuisines in a pan.
          </Text>
        </Card>
        <RenderLeadershipCard leaders={leaders} listData={listData} />
      </ScrollView>
    );
  }
}

const mapStateToProps = state => ({
  leaders: state.leaders,
});

export default connect(mapStateToProps)(About);
