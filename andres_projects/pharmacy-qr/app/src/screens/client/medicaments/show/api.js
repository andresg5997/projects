import firebase from 'react-native-firebase';

const db = firebase.firestore();

const getFirestorePharmacyData = pharmacyId => (
  db.collection('users').doc(pharmacyId).get()
    .then(res => res.data())
    .catch(err => err.message)
);

export default getFirestorePharmacyData;
