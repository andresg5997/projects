import { createStore, applyMiddleware } from 'redux';
import { createEpicMiddleware } from 'redux-observable';
import { head } from 'lodash/fp';
import {
    drawMatrix,
    getNextGeneration,
    getCurrentLiving,
    updateCells,
    BLUE,
    ORANGE,
    RED,
} from '../utils';
import { survivalRule, mostFrequentColor } from './rules';
import { runTimeEpic } from './epics';
import { trivago } from './patterns/trivago';

const colors = [BLUE, ORANGE, RED];

const calucluateNextColor = colorAmount => index =>
    (index + 1) % (colorAmount + 1) === 0 ? 1 : index + 1;

const getNextColor = calucluateNextColor(colors.length);

const initialState = {
    columns: 103,
    rows: 67,
    matrix: [],
    patterns: [trivago],
    rules: [survivalRule, mostFrequentColor],
    color: BLUE,
    running: false,
    frameRate: 200,
};

initialState.matrix = drawMatrix(
    initialState.columns,
    initialState.rows,
    initialState.patterns[0],
);

// ################################################################
// ### TASK: WEB-103 Implement planned refactoring for Reducer  ###
// ################################################################
// TODO: Before our engineer left this code base he had an idea to refactor
// this reducer to something like
//
// const map = {
//     TICK: ({ matrix }) => getNextGeneration(matrix, state.rules),
//     RESET: () => {},
//     CELLS_SELECTED: ({ matrix }, { payload }) => updateCells(matrix, payload),
//     PATTERN_SELECTED: ({ patterns }, { payload }) => patterns[payload]
// };
//
// Can you make her idea real?

const reducer = (state, action) => {
    const map = {
        START_STOP: () => ({ running: !state.running }),
        FRAME_RATE_CHANGE: ({}, { payload }) => ({ frameRate: payload.target.value }),
        TICK: ({ columns, rows, matrix, rules }) => ({ matrix: drawMatrix(columns, rows, getNextGeneration(matrix, rules)) }),
        RESET: ({ patterns, columns, rows }) => ({ matrix: drawMatrix(columns, rows, head(patterns)) }),
        CELLS_SELECTED: ({ columns, rows, matrix, color }, { payload }) => ({ matrix: drawMatrix(columns, rows, updateCells(matrix, { [payload[0]]: [payload[1], color]})) }),
        STORE_PATTERN: ({ patterns, matrix }) => ({ patterns: [...patterns, getCurrentLiving(matrix)] }),
        PATTERN_SELECTED: ({ columns, rows, patterns }, { payload }) => ({ matrix: drawMatrix(columns, rows, patterns[payload]) }),
        COLOR_CHANGED: () => ({ color: getNextColor(state.color) }),
    };
    const newData = map[action.type] ? map[action.type](state, action) : {}
    return { ...state, ...newData }
};

const epicMiddleware = createEpicMiddleware();

export default function configureStore() {
    const store = createStore(
        reducer,
        initialState,
        applyMiddleware(epicMiddleware),
    );
    epicMiddleware.run(runTimeEpic);
    return store;
}
