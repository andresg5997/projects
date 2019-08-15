import firebase from 'react-native-firebase';
import * as ActionTypes from '../ActionTypes';

const commentsFailed = errmess => ({
  type: ActionTypes.COMMENTS_FAILED,
  payload: errmess,
});

const addComments = comments => ({
  type: ActionTypes.ADD_COMMENTS,
  payload: comments,
});

const addComment = comment => ({
  type: ActionTypes.ADD_COMMENT,
  payload: comment,
});

const postComment = comment => (dispatch) => {
  setTimeout(() => {
    dispatch(addComment(comment));
  }, 2000);
};

const fetchComments = () => (dispatch) => {
  const db = firebase.firestore();
  db.collection('comments').get()
    .then(async (comments) => {
      const commentsDocs = comments.docs;
      const commentsArray = [];
      commentsDocs.forEach(comment => commentsArray.push(comment.data()));
      dispatch(addComments(commentsArray));
    })
    .catch(error => dispatch(commentsFailed(error)));
};

const CommentsActions = {
  commentsFailed,
  addComments,
  addComment,
  fetchComments,
  postComment,
};

export default CommentsActions;
