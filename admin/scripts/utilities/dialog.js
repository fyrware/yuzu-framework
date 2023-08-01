globalThis.yz ??= {};

yz.openDialog = function yzOpenDialog(dialog, options = { modal: true }) {
    if (options.modal) {
        dialog.showModal();
    } else {
        dialog.show();
    }

    if (options.onOpen) {
        dialog.addEventListener('open', options.onOpen);
    }

    if (options.onClose) {
        dialog.addEventListener('close', options.onClose);
    }

    dialog.dispatchEvent(new Event('open'));
}

yz.closeDialog = function yzCloseDialog(dialog) {
    dialog.close();
}