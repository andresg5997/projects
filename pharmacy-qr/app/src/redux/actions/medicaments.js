import * as ActionTypes from '../ActionTypes';

const addMedicaments = medicaments => ({
  type: ActionTypes.ADD_MEDICAMENTS,
  payload: medicaments,
});

const addSingleMedicament = medicament => ({
  type: ActionTypes.ADD_SINGLE_MEDICAMENT,
  payload: medicament,
});

const editMedicament = (data, id) => ({
  type: ActionTypes.EDIT_MEDICAMENT,
  payload: { data, id },
});

const MedicamentsActions = {
  addMedicaments,
  addSingleMedicament,
  editMedicament,
};

export default MedicamentsActions;
