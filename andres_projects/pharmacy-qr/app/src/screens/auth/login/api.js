import firebase from 'react-native-firebase';

const db = firebase.firestore();

const getFirebaseUser = userId => (
  db.collection('users').doc(userId).get()
    .then(res => res)
    .catch(err => err.message)
);

export default getFirebaseUser;
