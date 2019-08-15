import firebase from 'react-native-firebase';
import * as ActionTypes from '../ActionTypes';

const leadersLoading = () => ({
  type: ActionTypes.LEADERS_LOADING,
});

const leadersFailed = errmess => ({
  type: ActionTypes.LEADERS_FAILED,
  payload: errmess,
});

const addLeaders = leaders => ({
  type: ActionTypes.ADD_LEADERS,
  payload: leaders,
});

const fetchLeaders = () => (dispatch) => {
  dispatch(leadersLoading());
  const db = firebase.firestore();
  db.collection('leaders').get()
    .then(async (leaders) => {
      const leadersDocs = leaders.docs;
      const leadersArray = [];
      leadersDocs.forEach(leader => leadersArray.push(leader.data()));
      dispatch(addLeaders(leadersArray));
    })
    .catch(error => dispatch(leadersFailed(error)));
};

const LeadersActions = {
  leadersLoading,
  leadersFailed,
  addLeaders,
  fetchLeaders,
};

export default LeadersActions;
