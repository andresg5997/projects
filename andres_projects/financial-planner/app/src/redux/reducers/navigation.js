import { NavigationActions } from 'react-navigation';
import { Navigator } from '../../navigator';

const initialAction = { type: NavigationActions.Init };

const initialState = Navigator.router.getStateForAction(initialAction);

export default (state = initialState, action) => Navigator.router.getStateForAction(action, state);
