/**
 * @typedef { object } YzNotificationAction
 * @property { string } variant
 * @property { string } label
 * @property { function } handler
 */

/**
 * @typedef { object } YzNotification
 * @property { string } level
 * @property { string } icon
 * @property { string } title
 * @property { string } description
 * @property { ?boolean } dismissible
 * @property { ?number } duration
 * @property { YzNotificationAction[] } actions
 */

class YzNotificationService {

    alert(notification) {
        const alertInstance = yz.templates.instantiate('alert-notification', notification);

        yz('#alert-container').append(alertInstance);

        if (notification.dismissible) {
            alertInstance.select('button[type="reset"]').spy('click').observe(() => {
                alertInstance.remove();
            });
        }

        if (notification.duration) {
            setTimeout(() => {
                alertInstance.remove();
            }, notification.duration);
        }
    }

    toast(notification) {
        const toastInstance = yz.templates.instantiate('toast-notification', notification);

        yz('#toast-container').append(toastInstance.fragment);

        if (notification.dismissible) {
            toastInstance.select('button[type="reset"]').spy('click').observe(() => {
                toastInstance.remove();
            });
        }

        if (notification.duration) {
            setTimeout(() => {
                toastInstance.remove();
            }, notification.duration);
        }
    }
}