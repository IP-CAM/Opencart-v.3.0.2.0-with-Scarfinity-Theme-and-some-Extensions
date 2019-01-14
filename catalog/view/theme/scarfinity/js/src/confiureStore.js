import {
    createStore,
    applyMiddleware,
    compose, 
    combineReducers
}               from 'redux';
import thunk    from 'redux-thunk';
import { 
    routerReducer, 
    routerMiddleware 
}               from 'react-router-redux';
import { reducers } from './store.js';

export default function configureStore(history, initialState) {
    // If devTools is installed, connect to it
    const devToolsExtension = windowIfDefined && windowIfDefined.devToolsExtension;
    const createStoreWithMiddleware = compose(
        applyMiddleware(thunk, routerMiddleware(history)),
        devToolsExtension ? devToolsExtension() : (next) => next
    )(createStore);

    // Combine all reducers and instantiate the app-wide store instance
    const allReducers = buildRootReducer(reducers);
    const store = createStoreWithMiddleware(allReducers, initialState);

    return store;
}

function buildRootReducer(allReducers) {
    return combineReducers(Object.assign({}, allReducers, { routing: routerReducer }));
}