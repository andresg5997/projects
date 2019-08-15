import { StyleSheet } from 'react-native';
import variables from '../../../theme/variables';

const styles = StyleSheet.create({
  links: {
    marginTop: 20,
  },
  rowBetween: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  link: { color: variables.brandInfo },
});

export default styles;
