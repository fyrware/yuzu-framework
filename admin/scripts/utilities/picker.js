globalThis.yz ??= {};

yz.colorPicker = {
    attach(id, options = {}) {
        Coloris({
            el: '#' + id,
            ...options
        });
    }
}

yz.mediaPicker = {
    attach(element, options = {}) {
        element.addEventListener('click', (event) => {
            event.preventDefault();

            const mediaFrame = wp.media(options);

            mediaFrame.on('select', () => {
                const attachment = mediaFrame.state().get('selection').first().toJSON();

                if (options.onSelect) {
                    options.onSelect(attachment);
                }
            });

            mediaFrame.open();
        });
    }
}
