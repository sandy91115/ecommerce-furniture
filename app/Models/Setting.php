<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    const ADMIN_LOGO_PATH = 'admin_logo_path';
    const ADMIN_FAVICON_PATH = 'admin_favicon_path';
    const ADMIN_THEME_MODE = 'admin_theme_mode';
    const SITE_NAME = 'site_name';
    const ADMIN_EMAIL = 'admin_email';
    const WHATSAPP_NUMBER = 'whatsapp_number';

    const SITE_LOGO_PATH = 'site_logo_path';
    const SITE_FAVICON_PATH = 'site_favicon_path';
<<<<<<< HEAD
    const HOME_FEATURED_PROMO_ENABLED = 'home_featured_promo_enabled';
    const HOME_FEATURED_PROMO_LABEL = 'home_featured_promo_label';
    const HOME_FEATURED_PROMO_TITLE = 'home_featured_promo_title';
    const HOME_FEATURED_PROMO_DESCRIPTION = 'home_featured_promo_description';
    const HOME_FEATURED_PROMO_BUTTON_TEXT = 'home_featured_promo_button_text';
    const HOME_FEATURED_PROMO_BUTTON_URL = 'home_featured_promo_button_url';
    const HOME_FEATURED_PROMO_IMAGE_PATH = 'home_featured_promo_image_path';

    const SEO_TITLE_SUFFIX = 'seo_title_suffix';
    const SEO_DEFAULT_DESCRIPTION = 'seo_default_description';
    const SEO_DEFAULT_OG_IMAGE_PATH = 'seo_default_og_image_path';
    const SEO_ORGANIZATION_NAME = 'seo_organization_name';
    const SEO_ORGANIZATION_LOGO_PATH = 'seo_organization_logo_path';
    const SEO_ORGANIZATION_PHONE = 'seo_organization_phone';
    const SEO_ORGANIZATION_EMAIL = 'seo_organization_email';
    const SEO_SOCIAL_LINKS = 'seo_social_links';
    const SEO_SEARCH_ACTION_ENABLED = 'seo_search_action_enabled';
    const SEO_DEFAULT_ROBOTS_INDEX = 'seo_default_robots_index';
    const SEO_DEFAULT_ROBOTS_FOLLOW = 'seo_default_robots_follow';
    const SEO_SITEMAP_PRODUCTS_PRIORITY = 'seo_sitemap_products_priority';
    const SEO_SITEMAP_CATEGORIES_PRIORITY = 'seo_sitemap_categories_priority';
    const SEO_SITEMAP_PAGES_PRIORITY = 'seo_sitemap_pages_priority';
    const SEO_ROBOTS_EXTRA_DISALLOW = 'seo_robots_extra_disallow';
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646

    // Payment Methods
    const RAZORPAY_KEY = 'razorpay_key';
    const RAZORPAY_SECRET = 'razorpay_secret';
    const RAZORPAY_WEBHOOK = 'razorpay_webhook';
    const RAZORPAY_ENABLED = 'razorpay_enabled';
    const RAZORPAY_SHOW_CHECKOUT = 'razorpay_show_checkout';
<<<<<<< HEAD
    const STRIPE_KEY = 'stripe_key';
    const STRIPE_SECRET = 'stripe_secret';
    const STRIPE_WEBHOOK = 'stripe_webhook';
    const STRIPE_ENABLED = 'stripe_enabled';
    const STRIPE_SHOW_CHECKOUT = 'stripe_show_checkout';
    const PHONEPE_MERCHANT_ID = 'phonepe_merchant_id';
    const PHONEPE_SALT_KEY = 'phonepe_salt_key';
    const PHONEPE_SALT_INDEX = 'phonepe_salt_index';
    const PHONEPE_WEBHOOK = 'phonepe_webhook';
    const PHONEPE_ENABLED = 'phonepe_enabled';
    const PHONEPE_SHOW_CHECKOUT = 'phonepe_show_checkout';
    const COD_ENABLED = 'cod_enabled';
    const COD_SHOW_CHECKOUT = 'cod_show_checkout';
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    const CURRENCY = 'currency';
    const CURRENCY_SYMBOL = 'currency_symbol';

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
    ];

<<<<<<< HEAD
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? static::normalizeStoredValue($setting->getRawOriginal('value')) : $default;
    }

    public static function bool(string $key, bool $default = false): bool
    {
        $value = static::get($key, $default ? '1' : '0');

        return filter_var($value, FILTER_VALIDATE_BOOL);
    }

    public static function normalizeStoredValue($value)
    {
        if (! is_string($value)) {
            return $value;
        }

        $normalized = $value;

        for ($i = 0; $i < 5; $i++) {
            $decoded = json_decode($normalized, true);

            if (json_last_error() !== JSON_ERROR_NONE || ! is_string($decoded)) {
                break;
            }

            $normalized = $decoded;
        }

        return $normalized;
=======
    protected $casts = [
        'value' => 'array',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }
}
