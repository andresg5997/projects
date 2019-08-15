import { StyleSheet, Dimensions } from 'react-native';
// variables
import variables from './variables';

// get the screen width
const { height, width } = Dimensions.get('window');
const screenWidth = width;
const screenHeight = height;
const colors = {
  primaryColor: 'rgba(51, 166, 255, 0.8)',
};

const CommonStyles = StyleSheet.create({
  card: {
    backgroundColor: '#fff',
    elevation: 4,
    borderRadius: 6,
    paddingVertical: 30,
    paddingHorizontal: 15,
  },
  cardHeader: {
    marginBottom: 15,
  },
  cardFooter: {
    marginTop: 10,
    paddingVertical: 5,
  },
  colorPrimary: {
    color: variables.brandPrimary,
  },
  backgroundPrimary: {
    backgroundColor: variables.brandPrimary,
  },
  colorInfo: {
    color: variables.brandInfo,
  },
  item: {
    marginVertical: 7,
  },
  modalContainer: {
    backgroundColor: 'rgba(0,0,0,0.6)',
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },
  modal: {
    height: screenHeight * 0.9,
    width: screenWidth * 0.9,
    justifyContent: 'center',
  },
  primaryColor: {
    color: colors.primaryColor,
  },
});

export default CommonStyles;
