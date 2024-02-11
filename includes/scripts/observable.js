class YzObservable {

    #observers = new Set();

    observe(observer) {
        this.#observers.add(observer);
    }

    cancel(observer) {
        this.#observers.delete(observer);
    }

    notify(...args) {
        this.#observers.forEach(observer => observer(...args));
    }
}