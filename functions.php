<?php
/**
 * Neve functions.php file
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      17/08/2018
 *
 * @package Neve
 */

define('NEVE_VERSION', '3.8.2');
define('NEVE_INC_DIR', trailingslashit(get_template_directory()) . 'inc/');
define('NEVE_ASSETS_URL', trailingslashit(get_template_directory_uri()) . 'assets/');
define('NEVE_MAIN_DIR', get_template_directory() . '/');
define('NEVE_BASENAME', basename(NEVE_MAIN_DIR));
define('NEVE_PLUGINS_DIR', plugin_dir_path(dirname(__DIR__)) . 'plugins/');

define('HOME', home_url('/'));
define('TITLE', get_option('blogname'));

// 状態
define('IS_ADMIN', is_admin());
define('IS_LOGIN', is_user_logged_in());
define('IS_CUSTOMIZER', is_customize_preview());

// テーマディレクトリパス
define('T_DIRE', get_template_directory());
define('S_DIRE', get_stylesheet_directory());
define('T_DIRE_URI', get_template_directory_uri());
define('S_DIRE_URI', get_stylesheet_directory_uri());

define('THEME_NOTE', 'trustrate');

if (!defined('NEVE_DEBUG')) {
    define('NEVE_DEBUG', false);
}
define('NEVE_NEW_DYNAMIC_STYLE', true);
/**
 * Buffer which holds errors during theme inititalization.
 *
 * @var WP_Error $_neve_bootstrap_errors
 */
global $_neve_bootstrap_errors;

$_neve_bootstrap_errors = new WP_Error();

if (version_compare(PHP_VERSION, '7.0') < 0) {
    $_neve_bootstrap_errors->add(
        'minimum_php_version',
        sprintf(
            /* translators: %s message to upgrade PHP to the latest version */
            __("Hey, we've noticed that you're running an outdated version of PHP which is no longer supported. Make sure your site is fast and secure, by %1\$s. Neve's minimal requirement is PHP%2\$s.", 'neve'),
            sprintf(
                /* translators: %s message to upgrade PHP to the latest version */
                '<a href="https://wordpress.org/support/upgrade-php/">%s</a>',
                __('upgrading PHP to the latest version', 'neve')
            ),
            '7.0'
        )
    );
}
/**
 * A list of files to check for existance before bootstraping.
 *
 * @var array Files to check for existance.
 */

$_files_to_check = defined('NEVE_IGNORE_SOURCE_CHECK') ? [] : [
    NEVE_MAIN_DIR . 'vendor/autoload.php',
    NEVE_MAIN_DIR . 'style-main-new.css',
    NEVE_MAIN_DIR . 'assets/js/build/modern/frontend.js',
    NEVE_MAIN_DIR . 'assets/apps/dashboard/build/dashboard.js',
    NEVE_MAIN_DIR . 'assets/apps/customizer-controls/build/controls.js',
];
foreach ($_files_to_check as $_file_to_check) {
    if (!is_file($_file_to_check)) {
        $_neve_bootstrap_errors->add(
            'build_missing',
            sprintf(
                /* translators: %s: commands to run the theme */
                __('You appear to be running the Neve theme from source code. Please finish installation by running %s.', 'neve'), // phpcs:ignore WordPress.Security.EscapeOutput
                '<code>composer install --no-dev &amp;&amp; yarn install --frozen-lockfile &amp;&amp; yarn run build</code>'
            )
        );
        break;
    }
}
/**
 * Adds notice bootstraping errors.
 *
 * @internal
 * @global WP_Error $_neve_bootstrap_errors
 */
function _neve_bootstrap_errors()
{
    global $_neve_bootstrap_errors;
    printf('<div class="notice notice-error"><p>%1$s</p></div>', $_neve_bootstrap_errors->get_error_message()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

if ($_neve_bootstrap_errors->has_errors()) {
    /**
     * Add notice for PHP upgrade.
     */
    add_filter('template_include', '__return_null', 99);
    switch_theme(WP_DEFAULT_THEME);
    unset($_GET['activated']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    add_action('admin_notices', '_neve_bootstrap_errors');

    return;
}

/**
 * Themeisle SDK filter.
 *
 * @param array $products products array.
 *
 * @return array
 */
function neve_filter_sdk($products)
{
    $products[] = get_template_directory() . '/style.css';

    return $products;
}

add_filter('themeisle_sdk_products', 'neve_filter_sdk');
add_filter(
    'themeisle_sdk_compatibilities/' . NEVE_BASENAME,
    function ($compatibilities) {

        $compatibilities['NevePro'] = [
            'basefile' => defined('NEVE_PRO_BASEFILE') ? NEVE_PRO_BASEFILE : '',
            'required' => '2.4',
            'tested_up' => '2.8',
        ];

        return $compatibilities;
    }
);
require_once 'globals/migrations.php';
require_once 'globals/utilities.php';
require_once 'globals/hooks.php';
require_once 'globals/sanitize-functions.php';
require_once get_template_directory() . '/start.php';

/**
 * If the new widget editor is available,
 * we re-assign the widgets to hfg_footer
 */
if (neve_is_new_widget_editor()) {
    /**
     * Re-assign the widgets to hfg_footer
     *
     * @param array  $section_args The section arguments.
     * @param string $section_id The section ID.
     * @param string $sidebar_id The sidebar ID.
     *
     * @return mixed
     */
    function neve_customizer_custom_widget_areas($section_args, $section_id, $sidebar_id)
    {
        if (strpos($section_id, 'widgets-footer')) {
            $section_args['panel'] = 'hfg_footer';
        }

        return $section_args;
    }

    add_filter('customizer_widgets_section_args', 'neve_customizer_custom_widget_areas', 10, 3);
}

require_once get_template_directory() . '/header-footer-grid/loader.php';

add_filter(
    'neve_welcome_metadata',
    function () {
        return [
            'is_enabled' => !defined('NEVE_PRO_VERSION'),
            'pro_name' => 'Neve Pro Addon',
            'logo' => get_template_directory_uri() . '/assets/img/dashboard/logo.svg',
            'cta_link' => tsdk_utmify('https://themeisle.com/themes/neve/upgrade/?discount=LOYALUSER582&dvalue=50', 'neve-welcome', 'notice'),
        ];
    }
);

add_filter('themeisle_sdk_enable_telemetry', '__return_true');

//Custom Comment Form

function custom_comment_form_fields($fields)
{
    // Remove the author, email, and URL fields
    unset($fields['author']);
    unset($fields['email']);
    unset($fields['url']);

    // Modify the comment field attributes as needed
    $fields['comment_field'] = '<textarea name="comment" id="comment" cols="45" rows="3" class="p-3 bg-gray-300 border-3 border-gray-800" required></textarea>';

    return $fields;
}
add_filter('comment_form_default_fields', 'custom_comment_form_fields');

function custom_comment_form_submit_button($submit_button)
{
    // Customize the submit button as needed
    $submit_button = '<button type="submit"
                            class="p-3 py-2 mt-2 float-right bg-gray-900 hover:bg-gray-800 text-white rounded-md text-sm">
                            <i class="fa fa-send"></i> 転送</button>';

    return $submit_button;
}
add_filter('comment_form_submit_button', 'custom_comment_form_submit_button');

function custom_comment_form_defaults($defaults)
{
    $defaults['title_reply'] = '<div class="p-3 mb-3 text-white text-xl bg-gray-900">
                コメントを残す
            </div>';
    $defaults['fields']['redirect_to'] = '<input type="hidden" name="redirect_to" value="' . esc_url(get_permalink()) . '" />';

    return $defaults;
}
add_filter('comment_form_defaults', 'custom_comment_form_defaults');

function custom_comment_form_logged_in($message)
{
    $message = '';

    return $message;
}
add_filter('comment_form_logged_in', 'custom_comment_form_logged_in');

// Modify the comment form defaults
// Redirect to the current page after comment submission
function custom_comment_redirect($location)
{
    $referer = wp_get_referer();
    if ($referer) {
        return $referer;
    }
    return $location;
}
add_filter('comment_post_redirect', 'custom_comment_redirect');

// Register a shortcode to display the form
function custom_contact_form_shortcode()
{
    ob_start();
    ?>
<form method="POST">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
    <br>
    <input type="submit" value="Submit">
</form>
<?php
return ob_get_clean();
}
add_shortcode('custom_contact_form', 'custom_contact_form_shortcode');

// Handle form submission
function custom_contact_form_submit()
{
    if (isset($_POST['name']) && isset($_POST['email'])) {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);

        // Set email headers
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
        );

        // Set email subject and content
        $subject = 'New Form Submission';
        $message = 'Name: ' . $name . '<br>';
        $message .= 'Email: ' . $email;

        // Set the recipient email address
        $to = 'valkyrie12240@gmail.com';

        // Send the email
        wp_mail($to, $subject, $message, $headers);
    }
}
add_action('init', 'custom_contact_form_submit');