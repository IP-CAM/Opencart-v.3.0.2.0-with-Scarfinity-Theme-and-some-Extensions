import { createStore, applyMiddleware, compose, combineReducers } from 'redux';
import thunk from 'redux-thunk';
import { routerReducer, routerMiddleware } from 'react-router-redux';
import { reducers } from './store.js';

export default function configureStore(history, initialState) {
    // If devTools is installed, connect to it
    var devToolsExtension = windowIfDefined && windowIfDefined.devToolsExtension;
    var createStoreWithMiddleware = compose(applyMiddleware(thunk, routerMiddleware(history)), devToolsExtension ? devToolsExtension() : function (next) {
        return next;
    })(createStore);

    // Combine all reducers and instantiate the app-wide store instance
    var allReducers = buildRootReducer(reducers);
    var store = createStoreWithMiddleware(allReducers, initialState);

    return store;
}

function buildRootReducer(allReducers) {
    return combineReducers(Object.assign({}, allReducers, { routing: routerReducer }));
}