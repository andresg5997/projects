import firebase from 'react-native-firebase';

const db = firebase.firestore();

export default function fetchCategories(type) {
  const user = firebase.auth().currentUser;
  return db.collection('users').doc(user.uid).collection('categories').where('type', '==', type)
    .get()
    .then(res => res.docs)
    .catch(e => console.warn('Error fetching categories', e.message));
}
