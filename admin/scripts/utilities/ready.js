globalThis.yz ??= {};

yz.nextFrame = function yzNextFrame() {
    return new Promise((resolve) => {
        globalThis.requestAnimationFrame(() => {
            resolve();
        });
    });
}

yz.ready = function yzReady() {
    return new Promise((resolve) => {
        globalThis.addEventListener('load', () => {
            resolve();
        });
    });
}