import { FETCH_CATEGORIES, ADD_CATEGORY } from '../constants';

const fetchCategories = categories => ({
  type: FETCH_CATEGORIES,
  payload: categories,
});

const addCategory = category => ({
  type: ADD_CATEGORY,
  payload: category,
});

const categoriesActions = {
  fetchCategories,
  addCategory,
};

export default categoriesActions;
