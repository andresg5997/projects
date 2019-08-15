// @flow
import { FETCH_CATEGORIES, ADD_CATEGORY } from '../constants';

const initialState = {
  categories: {},
};

const categoriesReducer = (state = initialState, action) => {
  switch (action.type) {
    case ADD_CATEGORY: {
      const newCategories = Object.assign({}, state.categories);
      if (action.payload.type === 'income') {
        newCategories.incomes.push(action.payload);
      } else {
        newCategories.expenses.push(action.payload);
      }
      return { ...state, categories: newCategories };
    }
    case FETCH_CATEGORIES:
      return { ...state, categories: action.payload };
    default:
      return state;
  }
};

export default categoriesReducer;
