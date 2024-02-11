class YzAjaxService {

    url = '/wp-admin/admin-ajax.php';
    nonce = '';

    query(action, params = {}) {
        const observable = new YzObservable();

        const options = {
            method: 'get',
            credentials: 'same-origin',
        };

        const queryString = '?action=' + action + '&' + Object.entries(params).map(([key, value]) => {
            return `${key}=${encodeURIComponent(String(value))}`;
        }).join('&');

        fetch(this.url + queryString, options).then(async response => {
            observable.notify(await response.json());
        });

        return observable;
    }

    submit(action, data = new FormData()) {
        const observable = new YzObservable();

        if (!(data instanceof FormData)) {
            const object = data;

            data = new FormData();

            Object.entries(object).forEach(([key, value]) => {
                data.append(key, value);
            });
        }

        const options = {
            method: 'post',
            credentials: 'same-origin',
            body: data
        };

        options.body.append('action', action);
        options.body.append('nonce', this.nonce);

        fetch(this.url, options).then(async response => {
            observable.notify(await response.json());
        });

        return observable;
    }
}