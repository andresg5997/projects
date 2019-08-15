import { StyleSheet, Dimensions } from 'react-native';

const styles = StyleSheet.create({
  deviceHeight: {
    height: Dimensions.get('window').height,
  },
});

export default styles;
