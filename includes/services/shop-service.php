<?php

class Yz_Shop_Service {
    public const SUBSCRIPTION_PRICE           = '_subscription_price';
    public const SUBSCRIPTION_PERIOD          = '_subscription_period';
    public const SUBSCRIPTION_PERIOD_INTERVAL = '_subscription_period_interval';
    public const SUBSCRIPTION_SIGNUP_FEE      = '_subscription_sign_up_fee';

    public function create_product_category(string $name, string $description): array | WP_Error {
        global $yz;

        $slug = $yz->tools->format_url_slug($name);

        if (!term_exists($name, 'product_cat')) {
            return wp_insert_term($name, 'product_cat', [ 'slug' => $slug, 'description' => $description ]);
        }

        return new WP_Error('product_cat_exists', 'Product category already exists');
    }

    public function get_product_category(string $name): WP_Term | null {
        return get_term_by('name', $name, 'product_cat');
    }

    public function assign_product_category(int $product_id, string $category_name): void {
        $term = get_term_by('name', $category_name, 'product_cat');
        wp_set_object_terms($product_id, $term->term_id, 'product_cat');
    }

    public function create_subscription_product(array $subscription): WC_Product_Subscription {
        global $yz;

        $image         = $yz->tools->get_value($subscription, 'image');
        $title         = $yz->tools->get_value($subscription, 'title');
        $description   = $yz->tools->get_value($subscription, 'description');
        $price         = $yz->tools->get_value($subscription, 'price');
        $period        = $yz->tools->get_value($subscription, 'period');
        $interval      = $yz->tools->get_value($subscription, 'interval', 1);
        $signup_fee    = $yz->tools->get_value($subscription, 'signup_fee', 0);
        $virtual       = $yz->tools->get_value($subscription, 'virtual', true);
        $downloadable  = $yz->tools->get_value($subscription, 'downloadable', false);
        $manage_stock  = $yz->tools->get_value($subscription, 'manage_stock', false);
        $allow_reviews = $yz->tools->get_value($subscription, 'allow_reviews', false);

        $product = new WC_Product_Subscription();

        $product->set_price($price);
        $product->set_regular_price($price);
        $product->set_name($title);
        $product->set_description($description);
        $product->set_image_id($image);

        $product->set_virtual($virtual);
        $product->set_downloadable($downloadable);
        $product->set_manage_stock($manage_stock);
        $product->set_reviews_allowed($allow_reviews);

        $product->set_backorders('no');
        $product->set_catalog_visibility('hidden');
        $product->set_status('publish');
        $product->set_attributes([]);
        $product->set_default_attributes([]);

        $product_id = $product->save();

        $yz->posts->update_metadata($product_id, self::SUBSCRIPTION_PRICE, $price);
        $yz->posts->update_metadata($product_id, self::SUBSCRIPTION_PERIOD, $period);
        $yz->posts->update_metadata($product_id, self::SUBSCRIPTION_PERIOD_INTERVAL, $interval);
        $yz->posts->update_metadata($product_id, self::SUBSCRIPTION_SIGNUP_FEE, $signup_fee);

        return $product;
    }

    public function get_subscription_price(int $product_id): float {
        global $yz;
        return $yz->posts->get_metadata($product_id, self::SUBSCRIPTION_PRICE, true);
    }

    public function get_subscription_period(int $product_id): string {
        global $yz;
        return $yz->posts->get_metadata($product_id, self::SUBSCRIPTION_PERIOD, true);
    }

    public function get_subscription_period_interval(int $product_id): int {
        global $yz;
        return $yz->posts->get_metadata($product_id, self::SUBSCRIPTION_PERIOD_INTERVAL, true);
    }

    public function get_subscription_signup_fee(int $product_id): float | string {
        global $yz;
        return $yz->posts->get_metadata($product_id, self::SUBSCRIPTION_SIGNUP_FEE, true);
    }

    public function get_coupon(?string $code = ''): WC_Coupon | null {
        $coupon_id = wc_get_coupon_id_by_code($code);

        if (empty($coupon_id)) {
            return null;
        }

        return new WC_Coupon($coupon_id);
    }

    public function apply_coupon(string $code): void {
        $coupon = $this->get_coupon($code);

        if (isset($coupon) && !wc()->cart->has_discount($code)) {
            wc()->cart->apply_coupon($code);
        }
    }
}