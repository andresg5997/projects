import firebase from 'react-native-firebase';

const db = firebase.firestore();

const getFirestoreMedicaments = id => (
  db.collection('medicaments').where('pharmacy', '==', id).get()
    .then(res => res.docs)
    .catch(err => err.message)
);

export default getFirestoreMedicaments;
