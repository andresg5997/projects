import firebase from 'react-native-firebase';

const db = firebase.firestore();

export default function createCategory(data) {
  const user = firebase.auth().currentUser;
  return db.collection('users').doc(user.uid).collection('categories').add(data)
    .then(() => true)
    .catch(e => e);
}
