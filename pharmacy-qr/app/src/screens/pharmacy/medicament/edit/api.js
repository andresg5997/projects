import firebase from 'react-native-firebase';

const db = firebase.firestore();

const editFirestoreMedicament = (medicament, medicamentId) => (
  db.collection('medicaments').doc(medicamentId).update(medicament)
    .catch(err => console.log(err))
);

export default editFirestoreMedicament;
