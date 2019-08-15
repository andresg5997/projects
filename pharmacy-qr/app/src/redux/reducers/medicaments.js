import * as ActionTypes from '../ActionTypes';

const initialState = {
  medicaments: [],
};

const medicaments = (state = initialState, action) => {
  switch (action.type) {
    case ActionTypes.ADD_MEDICAMENTS:
      return { ...state, medicaments: action.payload };

    case ActionTypes.ADD_SINGLE_MEDICAMENT:
      return { ...state, medicaments: state.medicaments.concat(action.payload) };

    case ActionTypes.EDIT_MEDICAMENT: {
      const clonedMedicaments = state.medicaments;
      let editedMedicament = {};
      state.medicaments.forEach((medicament, index) => {
        if (medicament.id === action.payload.id) {
          editedMedicament = { id: action.payload.id, ...action.payload.data };
          clonedMedicaments.splice(index, 1, editedMedicament);
        }
      });
      return { ...state, medicaments: [].concat(clonedMedicaments) };
    }

    default:
      return state;
  }
};

export default medicaments;
