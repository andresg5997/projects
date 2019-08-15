import { StyleSheet } from 'react-native';
import { colors } from '../../../../theme';

const styles = StyleSheet.create({
  addComponentButton: {
    marginLeft: 20,
    borderRadius: 80,
    width: 110,
    backgroundColor: colors.mainMiddle,
  },
  addComponentButtonTitle: {
    fontSize: 12,
  },
  componentListTitle: {
    fontWeight: 'bold',
    margin: 20,
    marginTop: 10,
  },
  boldText: {
    fontWeight: 'bold',
  },
  componentItem: {
    flex: 1,
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginVertical: 10,
    marginHorizontal: 20,
  },
  createMedicamentButton: {
    backgroundColor: colors.mainDark,
  },
  createMedicamentButtonContainer: {
    marginBottom: 80,
  },
});

export default styles;
