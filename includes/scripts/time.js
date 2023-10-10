Reflect.set(yz, 'debounce', function debounce(fn, delay = 250) {
    let timeout;

    return (...args) => {
        clearTimeout(timeout)
        timeout = setTimeout(() => fn(...args), delay);
    };
});

Reflect.set(yz, 'throttle', function throttle(fn, delay = 250) {
    let timeout;

    return (...args) => {
        if (timeout) return;

        timeout = setTimeout(() => {
            fn(...args);
            timeout = null;
        }, delay);
    };
});