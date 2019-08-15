import firebase from 'react-native-firebase';

const db = firebase.firestore();

export const fetchFirestorePharmacyUsers = () => (
  db.collection('users').where('type', '==', 'pharmacy').get()
    .then(res => res.docs)
    .catch(err => err.message)
);

export const updateFirestorePharmacyUser = (userId, data) => {
  db.collection('users').doc(userId).update(data)
    .catch(e => console.log('ERROR', e.messsage));
};
