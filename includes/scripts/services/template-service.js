class YzTemplateNodeReference extends YzNodeReference {

    #documentFragment;

    get fragment() {
        return this.#documentFragment;
    }

    constructor(documentFragment) {
        super(documentFragment.childNodes);
        this.#documentFragment = documentFragment;
    }
}

class YzTemplateService {

    /**
     *
     * @param { YzNodeReference | HTMLTemplateElement | string } template
     * @param { object } data
     * @returns { YzTemplateNodeReference }
     */
    instantiate(template, data = {}) {
        const source = template instanceof YzNodeReference
            ? template.item()
            : typeof template === 'string'
                ? yz(`template[name="${template}"]`).item()
                : template;

        const instance = new YzTemplateNodeReference(source.content.cloneNode(true));

        Object.entries(data).forEach(([key, value]) => {
            instance.select(`[data-bind*="${key}"]`).forEach((element) => {
                const dataBindAttribute = element.getAttribute('data-bind');
                const dataBindPairs = dataBindAttribute.split(';');

                dataBindPairs.forEach((pair) => {
                    const [property, keyMatch] = pair.trim().split(':');

                    if (key === keyMatch.trim()) {
                        element[property] = value;
                    }
                });
            });
        });

        return instance;
    }
}