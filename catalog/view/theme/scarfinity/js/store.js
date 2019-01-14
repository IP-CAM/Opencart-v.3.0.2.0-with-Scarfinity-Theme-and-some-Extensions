export var actionCreators = {
    getProducts: function getProducts() {
        return function (dispatch, getState) {
            console.log('Get Products');
            dispatch({ type: 'GetProducts', data: {} });
        };
    }
};

var initalState = {
    loading: false,
    products: null
};

export var reducer = function reducer(state, incomingAction) {
    var action = incomingAction;
    switch (action.type) {
        case 'GetProducts':
            console.log('RR: GetProducts');
    }
};

export var reducers = {
    cart: reducer
};