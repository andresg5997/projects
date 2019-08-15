import React, { Component } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  FlatList,
  Modal,
  Button,
  Alert,
  PanResponder,
  Share } from 'react-native';
import { Card, Icon, Rating, Input } from 'react-native-elements';
import firebase from 'react-native-firebase';
import * as Animatable from 'react-native-animatable';
import { connect } from 'react-redux';
import FavoritesActions from '../redux/actions/favorites';
import CommentsActions from '../redux/actions/comments';
import cs from '../theme/common-styles';

const styles = StyleSheet.create({
  iconsRow: {
    flex: 1,
    justifyContent: 'center',
    flexDirection: 'row',
  },
  modalItem: {
    margin: 15,
  },
});

type DishProps = {
  dish: Object,
  favorite: Boolean,
  imageLink: String,
  onPress: Function,
  toggleModal: Function,
}

function RenderDish(props: DishProps) {
  const { dish, favorite, imageLink, onPress, toggleModal } = props;

  let view;

  const handleViewRef = (ref) => {
    view = ref;
  };

  // I use 100 to fit well in my phone screen
  const recognizeDrag = ({ dx }) => {
    // Favorite Alert
    if (dx < -100) {
      return 'left';
    }
    // Comment Form
    if (dx > 100) {
      return 'right';
    }
    return false;
  };

  const panResponder = PanResponder.create({
    onStartShouldSetPanResponder: () => (true),
    onPanResponderGrant: () => view.rubberBand(1000),
    onPanResponderEnd: (e, gestureState) => {
      console.log(`pan responder ended: ${gestureState.toString()}`);
      if (recognizeDrag(gestureState) === 'left') {
        Alert.alert(
          'Add favorite',
          'Are you sure you want to add to favorites?',
          [
            { text: 'Cancel', style: 'cancel' },
            { text: 'OK', onPress: () => (favorite ? console.log('Already Favorite') : onPress()) },
          ],
          { cancelable: false },
        );
      }
      if (recognizeDrag(gestureState) === 'right') {
        toggleModal();
      }
    },
  });

  const shareDish = (title, message, url) => {
    Share.share({
      title,
      message: `${title}: ${message} ${url}`,
      url,
    }, {

    });
  };

  if (dish != null) {
    return (
      <Animatable.View animation="fadeInDown" duration={2000} delay={500} {...panResponder.panHandlers} ref={handleViewRef}>
        <Card
          featuredTitle={dish.name}
          image={imageLink === '' ? require('../../images/blank.png') : { uri: imageLink }}
        >
          <Text style={{ margin: 10 }}>
            {dish.description}
          </Text>
          <View style={styles.iconsRow}>
            <Icon
              raised
              reverse
              name={favorite ? 'heart' : 'heart-o'}
              type="font-awesome"
              color="#f50"
              onPress={() => (favorite ? console.log('Already Favorite') : onPress())}
            />
            <Icon
              raised
              reverse
              name="pencil"
              type="font-awesome"
              color="#512DA8"
              onPress={() => toggleModal()}
            />
            <Icon
              raised
              reverse
              name="share"
              type="font-awesome"
              color="#51D2A8"
              onPress={() => shareDish(dish.name, dish.description, imageLink)}
            />
          </View>
        </Card>
      </Animatable.View>
    );
  }
  return (
    <View />
  );
}

type CommentsProps = {
  comments: Object,
}

function RenderComments(props: CommentsProps) {
  const { comments } = props;

  const renderCommentItem = ({ item, index }) => ( // eslint-disable-line
    <View key={index} style={{ margin: 10 }}>
      <Text style={{ fontSize: 14 }}>{item.comment}</Text>
      <Text style={{ fontSize: 12 }}>{item.rating} Stars</Text>
      <Text style={{ fontSize: 12 }}>-- {item.author}, {item.date}</Text>
    </View>
  );

  return (
    <Animatable.View animation="fadeInDown" duration={2000} delay={500}>
      <Card title="Comments">
        <FlatList
          data={comments}
          renderItem={renderCommentItem}
          keyExtractor={item => item.id.toString()}
        />
      </Card>
    </Animatable.View>
  );
}

type CommentModalProps = {
  commentForm: Object,
  showModal: Boolean,
  toggleModal: Function,
  setRating: Function,
  onFormChange: Function,
  handleCommentSubmit: Function,
}

function RenderCommentModal(props: CommentModalProps) {
  const {
    commentForm,
    showModal,
    toggleModal,
    onFormChange,
    setRating,
    handleCommentSubmit,
  } = props;
  return (
    <Modal
      style={cs.modal}
      animationType="slide"
      transparent={false}
      visible={showModal}
      onDismiss={() => toggleModal()}
      onRequestClose={() => toggleModal()}
    >
      <Rating
        showRating
        onFinishRating={rating => setRating(rating)}
        style={{ paddingVertical: 10 }}
      />
      <Input
        containerStyle={styles.modalItem}
        onChangeText={text => onFormChange('author', text)}
        value={commentForm.author}
        placeholder="Author"
        leftIcon={{ type: 'font-awesome', name: 'user-o' }}
      />
      <Input
        containerStyle={styles.modalItem}
        onChangeText={text => onFormChange('comment', text)}
        value={commentForm.comment}
        placeholder="Comment"
        leftIcon={{ type: 'font-awesome', name: 'comment-o' }}
      />
      <View style={styles.modalItem}>
        <Button
          onPress={() => handleCommentSubmit()}
          color="#512DA8"
          title="Submit"
        />
      </View>
      <View style={styles.modalItem}>
        <Button
          onPress={() => toggleModal()}
          color="#888"
          title="Cancel"
        />
      </View>
    </Modal>
  );
}

class DishDetails extends Component<null, DishProps> {
  constructor(props) {
    super(props);
    this.state = {
      imageLink: '',
      showModal: false,
      commentForm: {
        rating: 3,
        author: '',
        comment: '',
      },
    };
  }

  static navigationOptions = {
    title: 'Dish Details',
  }

  markFavorite = (dishId) => {
    const { postFavorite } = this.props;
    postFavorite(dishId);
  }

  toggleModal = () => {
    this.setState(prevState => ({
      ...prevState,
      showModal: !prevState.showModal,
    }));
  }

  setRating = (rating) => {
    this.setState(prevState => ({
      ...prevState,
      commentForm: {
        ...prevState.commentForm,
        rating,
      },
    }));
  }

  onFormChange = (field, text) => {
    this.setState(prevState => ({
      ...prevState,
      commentForm: {
        ...prevState.commentForm,
        [field]: text,
      },
    }));
  }

  handleCommentSubmit = () => {
    const { comments, postComment, navigation } = this.props;
    const { commentForm } = this.state;
    const newComment = {
      ...commentForm,
      dishId: navigation.getParam('dishId', ''),
      date: new Date().toISOString(),
      id: comments.comments.length,
    };
    postComment(newComment);
    this.toggleModal();
    this.resetCommentForm();
  }

  resetCommentForm = () => {
    this.setState(prevState => ({
      ...prevState,
      commentForm: {
        ...prevState.commentForm,
        rating: 1,
        author: '',
        comment: '',
      },
    }));
  }

  componentWillMount() {
    const { navigation, dishes } = this.props;
    const dishId = navigation.getParam('dishId', '');
    const selectedDish = dishes.dishes.filter(dish => dish.id === dishId)[0];
    firebase.storage().ref(selectedDish.image).getDownloadURL()
      .then((url) => {
        this.setState(prevState => ({
          ...prevState,
          imageLink: url,
        }));
      });
  }

  render() {
    const { imageLink, showModal, commentForm } = this.state;
    const { navigation, dishes, comments, favorites } = this.props;
    const dishId = navigation.getParam('dishId', '');
    return (
      <ScrollView>
        <RenderDish
          dish={dishes.dishes.filter(dish => dish.id === dishId)[0]}
          favorite={favorites.some(el => el === dishId)}
          imageLink={imageLink}
          onPress={() => this.markFavorite(dishId)}
          toggleModal={() => this.toggleModal()}
        />
        <RenderComments
          comments={comments.comments.filter(comment => comment.dishId === dishId)}
        />
        <RenderCommentModal
          commentForm={commentForm}
          showModal={showModal}
          toggleModal={() => this.toggleModal()}
          setRating={rating => this.setRating(rating)}
          onFormChange={(field, text) => this.onFormChange(field, text)}
          handleCommentSubmit={() => this.handleCommentSubmit()}
        />
      </ScrollView>
    );
  }
}

const mapStateToProps = state => ({
  dishes: state.dishes,
  comments: state.comments,
  favorites: state.favorites,
});

const mapActionsToProps = {
  postFavorite: FavoritesActions.postFavorite,
  postComment: CommentsActions.postComment,
};

export default connect(mapStateToProps, mapActionsToProps)(DishDetails);
