import { createComponent, RECEIVE_PROPS } from 'melody-component';
import { identity, property } from 'lodash/fp';
import { compose, withRefs } from 'melody-hoc';
import { connect } from 'melody-redux';
import template from './index.twig';
import {
    actionCreator,
    dispatchTo,
    parseFields,
    drawHandler,
    BLUE,
    ORANGE,
    RED,
} from '../utils';

// ##############################################################
// ### TASK: WEB-104 Refactoring by removing code duplication ###
// ##############################################################
// TODO: Please take a look at the calls to `actionCreator` and `dispatchTo` inside the
// following two code sections.
// There seems to be some repetion here.
// Can you cut down the noise while still making usage of these helpers?

// Action creators that will dispatch the defined actions to the bound store when called.
const actionsHandler = (type, component) => {
    const actionCalls = [
        { actionType: 'START_STOP', selector: 'startStop', elementId: 'powerSwitch', event: 'click' },
        { actionType: 'RESET', selector: 'init', elementId: 'reset', event: 'click' },
        { actionType: 'CELLS_SELECTED', selector: 'cellsSelected' },
        { actionType: 'FRAME_RATE_CHANGE', selector: 'frameRate', elementId: 'framerateSlider', event: 'change' },
        { actionType: 'STORE_PATTERN', selector: 'storePattern', elementId: 'store', event: 'click' },
        { actionType: 'PATTERN_SELECTED', selector: 'patternSelected', elementId: 'pattern', event: 'click', property: 'target.id' },
        { actionType: 'COLOR_CHANGED', selector: 'colorChanged', elementId: 'colorSwitch', event: 'click' },
    ]
    let response = {}
    actionCalls.forEach(action => {
        if(type === 'action')
            response[action.selector] = actionCreator(action.actionType, action.property ? property(action.property) : identity)
        if(type === 'dispatch')
            response[action.elementId] = dispatchTo(component, action.event, action.selector)
    })
    return response
}
const mapDispatchToProps = actionsHandler('action');

// Higher-Order-Component that connects the component to a store and wraps the
// template refs to a dispatch method.
const enhance = compose(
    connect(
        identity,
        mapDispatchToProps,
    ),
    withRefs(component => ({ ...actionsHandler('dispatch', component), grid: drawHandler(component) })),
);
export default enhance(createComponent(template));
