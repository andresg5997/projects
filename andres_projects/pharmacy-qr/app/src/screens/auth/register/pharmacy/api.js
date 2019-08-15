import firebase from 'react-native-firebase';

const createFirestorePharmacyUser = (userId, data) => {
  const db = firebase.firestore();
  db.collection('users').doc(userId).set(data)
    .catch(e => console.log('ERR: ', e.message));
};

export default createFirestorePharmacyUser;
