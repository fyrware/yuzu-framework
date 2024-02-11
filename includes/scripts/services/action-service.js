class YzActionService {

    #reducers = [];
    #state = {};

    get state() {
        return this.#state;
    }

    dispatch(action, payload) {
        this.#state = this.#reducers.reduce((state, reducer) => reducer(state, action, payload), this.#state);
        window.postMessage({ action, payload }, '*');
    }

    reduce(reducer) {
        this.#reducers.push(reducer);
    }

    spy(action) {
        const observable = new YzObservable();

        window.addEventListener('message', (event) => {
            if (event.data.action === action) {
                observable.notify(event.data.payload);
            }
        });

        return observable;
    }
}