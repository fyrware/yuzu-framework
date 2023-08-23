globalThis.yz ??= {};

yz.dialog = {
    activeDialogs: [],
    open(dialog, options = { modal: true }) {
        if (options.modal || options.modal === undefined) {
            dialog.showModal();
        } else {
            dialog.show();
        }

        if (options.onOpen) {
            dialog.onopen = options.onOpen;
        }

        if (options.onClose) {
            dialog.onclose = options.onClose;
        }

        if (!this.activeDialogs.includes(dialog)) {
            this.activeDialogs.push(dialog);
        }

        dialog.dispatchEvent(new Event('open'));
    },
    close(dialog) {
        dialog.close();

        if (this.activeDialogs.includes(dialog)) {
            this.activeDialogs.splice(this.activeDialogs.indexOf(dialog), 1);
        }
    },
    persist(dialog) {
        dialog.addEventListener('cancel', (event) => {
            event.preventDefault();
        });
    }
};