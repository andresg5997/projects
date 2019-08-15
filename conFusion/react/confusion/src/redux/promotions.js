import * as ActionTypes from './ActionTypes';

export const Promotions = (state = {
	errMess: null,
	isLoading: true,
	promotions: []
}, action) => {
	switch(action.type) {
		case ActionTypes.ADD_PROMOS:
			return { ...state, errMess: null, isLoading: false, promotions: action.payload };

		case ActionTypes.PROMOS_LOADING:
			return { ...state, errMess: null, isLoading: true, promotions: [] };

		case ActionTypes.PROMOS_FAILED:
			return { ...state, errMess: action.payload, isLoading: false };

		default:
			return state;
	}
}