import firebase from 'react-native-firebase';

const db = firebase.firestore();

const addFirestoreMedicament = data => (
  db.collection('medicaments').add(data)
    .then(res => res)
    .catch(err => err.message)
);

export default addFirestoreMedicament;
