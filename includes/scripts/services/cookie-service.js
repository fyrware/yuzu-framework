class YzCookieService {


    get(name) {
        const cookie = document.cookie.split(';').find(cookie => {
            return cookie.split('=')[0].trim() === name;
        });

        return cookie ? cookie.split('=')[1] : null;
    }

    set(name, value, days) {
        const cookieSegments = [
            `${name}=${value}`
        ];

        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

            cookieSegments.push(`expires=${date.toUTCString()}`);
        }

        document.cookie = cookieSegments.join(';');
    }
}