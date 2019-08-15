import firebase from 'react-native-firebase';

const db = firebase.firestore();

const searchFirestoreMedicaments = (name, searchByComponent) => {
  const col = db.collection('medicaments');
  if (!searchByComponent) {
    return col.where('lowercase', '==', name).get()
      .then(res => res.docs)
      .catch(err => err.message);
  }
  return col.where('components', 'array-contains', name).get()
    .then(res => res.docs)
    .catch(err => err.message);
};

export default searchFirestoreMedicaments;
