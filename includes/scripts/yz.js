/**
 * An observable which is used for observing events on elements within a YzElementReference
 */
class YzNodeReferenceObservable extends YzObservable {

    #reference;
    #event;

    constructor(reference, event) {
        super();

        this.#reference = reference;
        this.#event = event;

        this.#reference.forEach((node) => {
            node.addEventListener(this.#event, (...args) => {
                this.notify(...args);
            });
        });
    }
}

/**
 * Represents a collection of DOM nodes
 */
class YzNodeReference {

    #nodes;

    /**
     *
     * @param { NodeList | Array | Set } nodes
     */
    constructor(nodes) {
        if (nodes instanceof NodeList) {
            this.#nodes = new Set(Array.from(nodes));
        } else if (Array.isArray(nodes)) {
            this.#nodes = new Set(nodes);
        } else {
            this.#nodes = nodes;
        }
    }

    forEach(callback) {
        this.#nodes.forEach(callback);
    }

    /**
     *
     * @param index
     * @returns { YzNodeReference }
     */
    item(index = 0) {
        const nodeArray = Array.from(this.#nodes);

        if (nodeArray.length === 1 && nodeArray[0] instanceof DocumentFragment) {
            return nodeArray[0].children[index];
        }

        return Array.from(this.#nodes)[index];
    }

    select(selector) {
        const nodes = [];

        for (const node of this.#nodes) {
            if (node.matches(selector)) {
                nodes.push(node);
            }
            nodes.push(...Array.from(node.querySelectorAll(selector)));
        }

        return new YzNodeReference(nodes);
    }

    spy(event) {
        return new YzNodeReferenceObservable(this, event);
    }

    trigger(event, options) {
        this.forEach((element) => {
            element.dispatchEvent(new Event(event, options));
        });
    }

    attr(name, value) {
        if (value) {
            this.forEach((element) => {
                element.setAttribute(name, value);
            });
        }
        return this.item()?.getAttribute(name);
    }

    prop(name, value) {
        if (value) {
            this.forEach((element) => {
                element[name] = value;
            });
        }
        return this.item()?.[name];
    }

    append(node) {
        if (node instanceof Node) {
            this.item()?.append(node);
        } else if (node instanceof NodeList) {
            node.forEach((node) => {
                this.item()?.append(node);
            });
        } else {
            this.item()?.append(node.item());
        }
    }

    remove() {
        this.forEach((element) => {
            element.remove();
        });
    }
}

/**
 * Runs `ParentNode#querySelectorAll` against a given context (default is document)
 * @param target { string | Node | NodeList | YzNodeReference }
 * @param context { ParentNode }
 * @returns YzNodeReference
 */
function yz(target, context = document) {
    if (target instanceof Node) {
        return new YzNodeReference([target]);
    } else if (target instanceof NodeList) {
        return new YzNodeReference(target);
    } else if (typeof target === 'string') {
        return new YzNodeReference(context.querySelectorAll(target));
    } else {
        return target;
    }
}