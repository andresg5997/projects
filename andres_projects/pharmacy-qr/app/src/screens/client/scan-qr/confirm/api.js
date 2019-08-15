import firebase from 'react-native-firebase';

const db = firebase.firestore();

export const buyFirestoreMedicament = (medicamentId, newQuantity) => {
  db.collection('medicaments').doc(medicamentId).update({ quantity: newQuantity })
    .catch(err => err.message);
};

export const getFirestoreMedicament = medicamentId => (
  db.collection('medicaments').doc(medicamentId).get()
    .then(res => res)
    .catch(err => err.message)
);
