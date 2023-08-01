globalThis.yz = Object.assign(function yz(selector, context = document) {
    return Object.assign(context.querySelectorAll(selector), {
        item(index = 0) {
            return NodeList.prototype.item.call(this, index);
        }
    });
}, globalThis.yz ?? {});
