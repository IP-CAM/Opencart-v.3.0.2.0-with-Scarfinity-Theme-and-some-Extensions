export const actionCreators = {
    getProducts: () => (dispatch, getState) => {
        console.log('Get Products');
        dispatch({ type: 'GetProducts', data: {} });
    }
}

const initalState = {
    loading: false,
    products: null
}

export const reducer = (state, incomingAction) => {
    const action = incomingAction;
    switch(action.type) {
        case 'GetProducts': console.log('RR: GetProducts');
    }
}

export const reducers = {
    cart: reducer
}