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

        this.#reference.forEach((ref) => {
            ref.item().addEventListener(this.#event, (...args) => {
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
     * @param { Node | NodeList | Array | Set } nodes
     */
    constructor(nodes) {
        if (nodes instanceof Node) {
            this.#nodes = new Set([nodes]);
        } else if (nodes instanceof NodeList) {
            this.#nodes = new Set(Array.from(nodes));
        } else if (Array.isArray(nodes)) {
            this.#nodes = new Set(nodes);
        } else {
            this.#nodes = nodes;
        }
    }

    forEach(callback) {
        Array.from(this.#nodes).map(yz).forEach(callback);
    }

    /**
     * @param index
     * @returns { any }
     */
    item(index = 0) {
        return Array.from(this.#nodes)[index];
    }

    count() {
        return this.#nodes.size;
    }

    first() {
        return yz(this.item(0));
    }

    last() {
        return yz(this.item(this.#nodes.size() - 1));
    }

    next() {
        return new YzNodeReference(Array.from(this.#nodes).map((node) => node.nextElementSibling).filter(Boolean));
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

    closest(selector) {
        return new YzNodeReference(Array.from(this.#nodes).map((node) => node.closest(selector)).filter(Boolean));
    }

    spy(event) {
        return new YzNodeReferenceObservable(this, event);
    }

    trigger(event, options) {
        this.forEach((ref) => {
            ref.item().dispatchEvent(new Event(event, options));
        });
    }

    attr(name, value) {
        if (value) {
            this.forEach((element) => {
                element.item().setAttribute(name, value);
            });
            return this;
        }
        return this.item()?.getAttribute(name);
    }

    unsetAttr(name) {
        this.forEach((element) => {
            element.item().removeAttribute(name);
        });
        return this;
    }

    data(name, value) {
        if (value !== undefined) {
            this.forEach((element) => {
                element.item().dataset[name] = value;
            });
            return this;
        }
        return this.item()?.dataset[name];
    }

    unsetData(name) {
        this.forEach((element) => {
            delete element.item().dataset[name];
        });
        return this;
    }

    prop(name, value) {
        if (value !== undefined) {
            this.forEach((element) => {
                element.item()[name] = value;
            });
            return this;
        }
        return this.item()?.[name];
    }

    unsetProp(name) {
        this.forEach((element) => {
            try {
                delete element.item()[name];
            } catch {
                element.item()[name] = undefined;
            }
        });
        return this;
    }

    id(id) {
        return this.prop('id', id);
    }

    name(name) {
        return this.prop('name', name);
    }

    html(html) {
        return this.prop('innerHTML', html);
    }

    style(name, value) {
        if (value) {
            this.forEach((element) => {
                element.item().style[name] = value;
            });
            return this;
        }
        return this.item()?.style[name];
    }

    text(text) {
        return this.prop('textContent', text);
    }

    type(type) {
        return this.prop('type', type);
    }

    checked(checked) {
        return this.prop('checked', checked);
    }

    selected(selected) {
        return this.prop('selected', selected);
    }

    value(value) {
        return this.prop('value', value);
    }

    children() {
        return new YzNodeReference(this.item()?.childNodes);
    }

    class(className) {
        return this.prop('className', className);
    }

    classes() {
        return this.item()?.classList;
    }

    append(node) {
        if (node instanceof Node) {
            this.item()?.append(node);
        } else if (node instanceof NodeList) {
            node.forEach((node) => {
                this.item()?.append(node);
            });
        } else if (node instanceof YzTemplateNodeReference) {
            this.item()?.append(node.fragment);
        } else if (node instanceof YzNodeReference) {
            this.item()?.append(node.item());
        }
        return this;
    }

    remove() {
        this.forEach((ref) => {
            ref.item().remove();
        });
        return this;
    }

    show(options = {}) {
        this.forEach((ref) => {
            if (ref.is(HTMLDialogElement)) {
                if (options.modal) {
                    ref.item().showModal();
                } else {
                    ref.item().show();
                }
            } else {
                ref.unsetAttr('hidden');
            }
        });
        return this;
    }

    hide() {
        this.forEach((ref) => {
            if (ref.is(HTMLDialogElement)) {
                ref.item().close();
            } else {
                ref.prop('hidden', true);
            }
        });
        return this;
    }

    exists() {
        return this.item() !== undefined;
    }

    is(type) {
        return this.item() instanceof type;
    }
}

/**
 * Runs `ParentNode#querySelectorAll` against a given context (default is document)
 * @param target { string | Node | NodeList | YzNodeReference }
 * @param context { ParentNode | YzNodeReference }
 * @returns YzNodeReference
 */
function yz(target, context = document) {
    if (target instanceof Node) {
        return new YzNodeReference(target);
    } else if (target instanceof NodeList) {
        return new YzNodeReference(target);
    } else if (typeof target === 'string') {
        if (context instanceof Node) {
            return new YzNodeReference(context.querySelectorAll(target));
        } else {
            return context.select(target);
        }
    } else {
        return target;
    }
}

yz.element = function element(tag) {
    return new YzNodeReference([document.createElement(tag)]);
}

yz.fragment = function fragment() {
    return new YzNodeReference([new DocumentFragment()]);
}