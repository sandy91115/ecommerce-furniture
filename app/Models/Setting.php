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

    // Payment Methods
    const RAZORPAY_KEY = 'razorpay_key';
    const RAZORPAY_SECRET = 'razorpay_secret';
    const RAZORPAY_WEBHOOK = 'razorpay_webhook';
    const RAZORPAY_ENABLED = 'razorpay_enabled';
    const RAZORPAY_SHOW_CHECKOUT = 'razorpay_show_checkout';
    const CURRENCY = 'currency';
    const CURRENCY_SYMBOL = 'currency_symbol';

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
