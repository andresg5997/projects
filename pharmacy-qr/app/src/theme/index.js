import { StyleSheet } from 'react-native';
// Colors
export const colors = {
  mainDark: '#507c5c',
  mainMiddle: '#90B67D',
  mainLight: '#CFF09E',
};

// Common Styles
export const cs = StyleSheet.create({
  container: {
    justifyContent: 'center',
    margin: 20,
  },
  formInput: {
    margin: 20,
  },
  formInputText: {
    height: undefined,
  },
  formButton: {
    marginVertical: 20,
    marginHorizontal: 60,
  },
  formButtonStyle: {
    backgroundColor: colors.mainDark,
    borderRadius: 20,
  },
  formCheckbox: {
    margin: 20,
    backgroundColor: null,
  },
});
