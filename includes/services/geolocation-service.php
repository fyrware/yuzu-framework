<?php

class Yz_Geolocation_Service {

    public const API_KEY_OPTION = 'yz_geoapify_api_key';

    public function get_api_key(): string {
        return get_option(static::API_KEY_OPTION);
    }

    public function set_api_key(string $api_key): void {
        update_option(static::API_KEY_OPTION, $api_key);
    }

    public function has_api_key(): bool {
        return !empty($this->get_api_key());
    }

    public function request_geolocation($address, $format = 'json') {
        $geo_response = wp_remote_get('https://api.geoapify.com/v1/geocode/search?text=' . urlencode($address) . '&format=' . $format . '&apiKey=' . $this->get_api_key());

        if (is_wp_error($geo_response)) {
            return null;
        }

        $geo_body = wp_remote_retrieve_body($geo_response);

        if ($format === 'json') {
            return json_decode($geo_body);
        } else if ($format === 'xml') {
            return simplexml_load_string($geo_body);
        } else {
            return $geo_body;
        }
    }

    public function get_static_map_url($lon, $lat, $options = []): string {
        $width        = yz()->tools->get_value($options, 'width', 480);
        $height       = yz()->tools->get_value($options, 'height', 340);
        $marker_color = yz()->tools->get_value($options, 'marker_color', '#f4112c');

        return 'https://maps.geoapify.com/v1/staticmap?style=klokantech-basic&width=' . $width . '&height=' . $height .
            '&center=lonlat:' . $lon . ',' . $lat . '&zoom=14' .
            '&marker=lonlat:' . $lon . ',' . $lat . ';color:' . urlencode($marker_color) . ';size:medium&scaleFactor=2' .
            '&apiKey=' . $this->get_api_key();
    }
}