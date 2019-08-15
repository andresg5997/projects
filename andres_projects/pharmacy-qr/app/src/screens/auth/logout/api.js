import firebase from 'react-native-firebase';

const logOut = () => {
  firebase.auth().signOut()
    .catch(e => console.log(e.message));
};

export default logOut;
