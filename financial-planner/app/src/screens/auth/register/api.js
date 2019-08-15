import firebase from 'react-native-firebase';

const db = firebase.firestore();

export function createFirestoreUser(id, data) {
  db.collection('users').doc(id).set(data)
    .catch(e => console.warn('Error creating user', e.message));
}

export function fetchCurrencies() {
  return db.collection('currencies').get()
    .then(res => res.docs)
    .catch(e => console.warn('Error fetching currencies', e.message));
}
