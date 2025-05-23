<?php
/**
 * Give Settings Page/Tab
 *
 * @package     Give
 * @subpackage  Classes/Give_Settings_Advanced
 * @copyright   Copyright (c) 2016, GiveWP
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.8
 */

use Give\FeatureFlags\OptionBasedFormEditor\OptionBasedFormEditor;
use Give\Onboarding\Setup\Page as SetupPage;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'Give_Settings_Advanced' ) ) :

    /**
     * Give_Settings_Advanced.
     *
     * @sine 1.8
     */
    class Give_Settings_Advanced extends Give_Settings_Page
    {

        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->id = 'advanced';
            $this->label = __('Advanced', 'give');

            $this->default_tab = 'advanced-options';

            if ($this->id === give_get_current_setting_tab()) {
                add_action(
                    'give_admin_field_remove_cache_button',
                    [
                        $this,
                        'render_remove_cache_button',
                    ],
                    10,
                    1
                );
                add_action('give_save_settings_give_settings', [$this, 'validate_settngs']);
                add_filter( "give_admin_settings_sanitize_option_donor_default_user_role", [$this, 'sanitize_option_donor_default_user_role']);
                add_action('give_admin_field_give_option_based_form_editor_notice',
                    [$this, '_render_give_based_form_editor_notice'], 10, 2);
            }

            parent::__construct();
        }

        /**
         * Get settings array.
         *
         * @since 4.1.0 Added Donation Forms section
         * @since  1.8
         * @return array
         */
        public function get_settings()
        {
            $settings = [];

            $current_section = give_get_current_setting_section();
            $setupPage = esc_url(admin_url('edit.php?post_type=give_forms&page=give-setup'));

            switch ($current_section) {
                case 'advanced-options':
                    $settings = [
                        [
                            'id' => 'give_title_data_control_2',
                            'type' => 'title',
                        ],
                        [
                            'name' => __('Option-Based Form Editor', 'give'),
                            'desc' => __('If enabled, you\'ll gain access to the legacy settings and can create forms using the Option-Based Editor. Disabling this option will not affect existing forms created with the Option-Based Editor.',
                                'give'),
                            'id' => 'option_based_form_editor',
                            'type' => 'radio_inline',
                            'default' => (OptionBasedFormEditor::existOptionBasedFormsOnDb()) ? 'enabled' : 'disabled',
                            'options' => [
                                'enabled' => __('Enabled', 'give'),
                                'disabled' => __('Disabled', 'give'),
                            ],
                        ],
                        [
                            'id' => 'option_based_form_editor_notice',
                            'type' => 'give_option_based_form_editor_notice',
                        ],
                        [
                            'name' => __('Default GiveWP Styles', 'give'),
                            'desc' => __('This controls GiveWP\'s default styles for legacy donation forms and other front end elements. Disabling this option means that you\'ll need to supply your own styles.',
                                'give'),
                            'id' => 'css',
                            'type' => 'radio_inline',
                            'default' => 'enabled',
                            'options' => [
                                'enabled' => __('Enabled', 'give'),
                                'disabled' => __('Disabled', 'give'),
                            ],
                        ],
                        [
                            'name' => __('Remove Data on Uninstall', 'give'),
                            'desc' => __('When the plugin is deleted, completely remove all GiveWP data. This includes all GiveWP settings, forms, form meta, donor, donor data, donations. Everything.',
                                'give'),
                            'id' => 'uninstall_on_delete',
                            'type' => 'radio_inline',
                            'default' => 'disabled',
                            'options' => [
                                'enabled' => __('Yes, Remove all data', 'give'),
                                'disabled' => __('No, keep my GiveWP settings and donation data', 'give'),
                            ],
                        ],
                        [
                            'name' => __('Default User Role', 'give'),
                            'desc' => __('Users are given this user role when they opt into creating a WordPress/site account along with their donation.',
                                'give'),
                            'id' => 'donor_default_user_role',
                            'type' => 'select',
                            'default' => 'give_donor',
                            'options' => give_get_user_roles(),
                        ],
                        [
                            /* translators: %s: the_content */
                            'name' => sprintf(__('%s filter', 'give'), '<code>the_content</code>'),
                            /* translators: 1: https://codex.wordpress.org/Plugin_API/Filter_Reference/the_content 2: the_content */
                            'desc' => sprintf(__('This controls whether or not GiveWP legacy form content is treated like WordPress content. Disabling this option means that things like social sharing and other theme- or plugin-added functionality to enhance or append things to content will not be applied to GiveWP legacy form content. <a href="%1$s" target="_blank">Learn more</a> about %2$s filter.',
                                'give'), esc_url('https://docs.givewp.com/thecontent-filter'),
                                '<code>the_content</code>'),
                            'id' => 'the_content_filter',
                            'default' => 'enabled',
                            'type' => 'radio_inline',
                            'options' => [
                                'enabled' => __('Enabled', 'give'),
                                'disabled' => __('Disabled', 'give'),
                            ],
                        ],
                        [
                            'name' => __('Script Loading Location', 'give'),
                            'desc' => __('This allows you to load your GiveWP scripts either in the <code>&lt;head&gt;</code> or footer of your website.',
                                'give'),
                            'id' => 'scripts_footer',
                            'type' => 'radio_inline',
                            'default' => 'disabled',
                            'options' => [
                                'disabled' => __('Head', 'give'),
                                'enabled' => __('Footer', 'give'),
                            ],
                        ],
                        [
                            'name' => __('Setup Page', 'give'),
                            /* translators: %s: about page URL */
                            'desc' => sprintf(
                            wp_kses(
                                    __(
                                        'This option controls the display of the %s when GiveWP is first installed.',
                                        'give'
                                    ),
                                    [
                                        'a' => [
                                            'href' => [],
                                            'target' => [],
                                        ],
                                    ]
                                ),
                                SetupPage::getSetupPageEnabledOrDisabled(
                                ) === SetupPage::ENABLED ? "<a href='$setupPage' target='_blank'>GiveWP Setup page</a>" : 'GiveWP Setup page'
                            ),
                            'id' => 'setup_page_enabled',
                            'type' => 'radio_inline',
                            'default' => give_is_setting_enabled(
                                SetupPage::getSetupPageEnabledOrDisabled()
                            )
                                ? SetupPage::ENABLED
                                : SetupPage::DISABLED,
                            'options' => [
                                SetupPage::ENABLED => __('Enabled', 'give'),
                                SetupPage::DISABLED => __('Disabled', 'give'),
                            ],
                            'wrapper_class' => version_compare(get_bloginfo('version'), '5.0',
                                '<=') ? 'give-hidden' : null,
                        ],
                        [
                            'name' => __('Form Page URL Prefix', 'give'),
                            'desc' => sprintf(
                                __('This slug is used as a base for the (invisible to users/site visitors) iframe URL that contains all form templates besides the legacy form template. The URL currently looks like this: %1$s. This option allows you to modify that URL to avoid conflicts that might exist with other pages and URLs on the site.',
                                    'give'),
                                '<code>' . trailingslashit(home_url()) . Give()->routeForm->getBase() . '/{form-slug}</code>'
                            ),
                            'id' => Give()->routeForm->getOptionName(),
                            'type' => 'text',
                            'default' => Give()->routeForm->getBase(),
                        ],
                        [
                            'name' => __('Advanced Database Updates', 'give'),
                            'desc' => __('This option is only for advanced users and/or those directed by GiveWP support. Once you enable this, you\'ll have the ability to override the run order and to force re-running for database updates at Donations > Tools > Data. If you don\'t know what you are doing, you can easily break things with this option enabled. Do not leave this option enabled after you\'re done troubleshooting.',
                                'give'),
                            'id' => 'enable_database_updates',
                            'type' => 'radio_inline',
                            'default' => 'disabled',
                            'options' => [
                                'enabled' => __('Enabled', 'give'),
                                'disabled' => __('Disabled', 'give'),
                            ],
                        ],
                        [
                            'name' => __('Orphaned Donation Forms', 'give'),
                            'desc' => __('Show orphaned donation forms list-table. Tools > Data > Orphaned forms', 'give'),
                            'id' => 'show_orphaned_forms_table',
                            'type' => 'radio_inline',
                            'default' => 'disabled',
                            'options' => [
                                'enabled' => __('Enabled', 'give'),
                                'disabled' => __('Disabled', 'give'),
                            ],
                        ],
                        [
                            'name' => __('GiveWP Cache', 'give'),
                            'id' => 'give-clear-cache',
                            'buttonTitle' => __('Clear Cache', 'give'),
                            'desc' => __('Click this button if you want to clear GiveWP\'s cache. The plugin stores common settings and queries in cache to optimize performance. Clearing cache will remove and begin rebuilding these saved queries.',
                                'give'),
                            'type' => 'remove_cache_button',
                        ],
                        [
                            'name' => __('Advanced Settings Docs Link', 'give'),
                            'id' => 'advanced_settings_docs_link',
                            'url' => esc_url('http://docs.givewp.com/settings-advanced'),
                            'title' => __('Advanced Settings', 'give'),
                            'type' => 'give_docs_link',
                        ],
                        [
                            'id' => 'give_title_data_control_2',
                            'type' => 'sectionend',
                        ],
                    ];
                    break;

                case 'donation-forms':
                    $settings = [
                        [
                            'id' => 'give_setting_advanced_section_donation_forms',
                            'type' => 'title',
                        ],
                        [
                            'name' => __('Custom Styles', 'give'),
                            'desc' => __(
                                'Add your own custom CSS styles here to customize the appearance of all donation forms across your site. These styles will be applied globally, allowing you to maintain consistent design without editing each form individually.',
                                'give'
                            ),
                            'id' => 'custom_form_styles',
                            'type' => 'code_editor',
                            'css' => 'width: 100%;',
                            'editor_attributes' => [
                                'mode' => 'css',
                            ],
                        ],
                        [
                            'id' => 'give_setting_advanced_section_donation_forms',
                            'type' => 'sectionend',
                        ],
                    ];
                    break;

                case 'akismet-spam-protection':
                    $settings = [
                        [
                            'id' => 'give_setting_advanced_section_akismet_spam_protection',
                            'type' => 'title',
                        ],
                        [
                            'name' => __('Akismet SPAM Protection', 'give'),
                            'desc' => __('Add a layer of SPAM protection to your donation submissions with Akismet. When enabled, donation submissions will be first sent through Akismet\'s SPAM check API if you have the plugin activated and configured.',
                                'give'),
                            'id' => 'akismet_spam_protection',
                            'type' => 'radio_inline',
                            'default' => (give_check_akismet_key()) ? 'enabled' : 'disabled',
                            'options' => [
                                'enabled' => __('Enabled', 'give'),
                                'disabled' => __('Disabled', 'give'),
                            ],
                        ],
                        [
                            'name' => __('Whitelist by Email', 'give'),
                            'desc' => sprintf(
                                '%1$s %2$s',
                                __('Add emails one at a time to ensure that donations using that email bypass GiveWP\'s Akismet SPAM filtering. Emails added to the list here are always allowed to donate, even if they\'ve been flagged by Akismet.',
                                    'give'),
                                sprintf(
                                    __('To permanently prevent emails from being flagged as SPAM by Akismet <a href="%1$s" target="_blank">contact their team here</a>.',
                                        'give'),
                                    esc_url('https://docs.givewp.com/akismet-contact')
                                )
                            ),
                            'id' => 'akismet_whitelisted_email_addresses',
                            'type' => 'email',
                            'attributes' => [
                                'placeholder' => 'test@example.com',
                            ],
                            'repeat' => true,
                            'repeat_btn_title' => esc_html__('Add Email', 'give'),
                        ],
                        [
                            'id' => 'give_setting_advanced_section_akismet_spam_protection',
                            'type' => 'sectionend',
                        ],
                    ];
                    break;
            }

            /**
             * Hide caching setting by default.
             *
             * @since 2.0
             */
            if (apply_filters('give_settings_advanced_show_cache_setting', false)) {
                array_splice(
                    $settings,
                    1,
                    0,
                    [
                        [
                            'name' => __('Cache', 'give'),
                            'desc' => __('If caching is enabled the plugin will start caching custom post type related queries and reduce the overall load time.',
                                'give'),
                            'id' => 'cache',
                            'type' => 'radio_inline',
                            'default' => 'enabled',
                            'options' => [
                                'enabled' => __('Enabled', 'give'),
                                'disabled' => __('Disabled', 'give'),
                            ],
                        ],
                    ]
                );
            }

            /**
             * Filter the advanced settings.
             * Backward compatibility: Please do not use this filter. This filter is deprecated in 1.8
             */
            $settings = apply_filters('give_settings_advanced', $settings);

            /**
             * Filter the settings.
             *
             * @since  1.8
             *
             * @param array $settings
             *
             */
            $settings = apply_filters('give_get_settings_' . $this->id, $settings);

            // Output.
            return $settings;
        }

        /**
         * Get sections.
         *
         * @since 1.8
         * @return array
         */
        public function get_sections()
        {
            $sections = [
                'advanced-options' => __('Advanced Options', 'give'),
                'donation-forms' => __('Donation Forms', 'give'),
                'akismet-spam-protection' => __('Akismet SPAM Protection', 'give'),
            ];

            return apply_filters('give_get_sections_' . $this->id, $sections);
        }


        /**
         *  Render remove_cache_button field type
         *
         * @since  2.25.2  add nonce field
         * @since  2.1
         * @access public
         *
         * @param array $field
         *
         */
        public function render_remove_cache_button($field)
        {
            ?>
            <tr valign="top" <?php
            echo ! empty($field['wrapper_class']) ? 'class="' . $field['wrapper_class'] . '"' : ''; ?>>
                <th scope="row" class="titledesc">
                    <label
                        for="<?php
                        echo esc_attr($field['id']); ?>"><?php
                        echo esc_html($field['name']); ?></label>
                </th>
                <td class="give-forminp">
                    <button type="button" id="<?php
                    echo esc_attr($field['id']); ?>"
                            class="button button-secondary"><?php
                        echo esc_html($field['buttonTitle']); ?></button>
                    <?php
                    echo Give_Admin_Settings::get_field_description($field ); ?>
                    <?php wp_nonce_field('give_cache_flush', 'give_cache_flush_nonce'); ?>
                </td>
            </tr>
            <?php
        }


        /**
         * Validate setting
         *
         * @since  2.2.0
         * @access public
         *
         * @param array $options
         *
         */
        public function validate_settngs($options)
        {
            // Sanitize data.
            $akismet_spam_protection = isset($options['akismet_spam_protection'])
                ? $options['akismet_spam_protection']
                : (give_check_akismet_key() ? 'enabled' : 'disabled');

            // Show error message if Akismet not configured and Admin try to save 'enabled' option.
            if (
                give_is_setting_enabled($akismet_spam_protection)
                && ! give_check_akismet_key()
            ) {
                Give_Admin_Settings::add_error(
                    'give-akismet-protection',
                    __('Please properly configure Akismet to enable SPAM protection.', 'give')
                );

                give_update_option('akismet_spam_protection', 'disabled' );
            }
        }

        public function sanitize_option_donor_default_user_role($value) {
            $baseRole = ( ( $give_donor = wp_roles()->is_role( 'give_donor' ) ) && ! empty( $give_donor ) ? 'give_donor' : 'subscriber' );
            $defaultUserRoles = (array) give_get_option( 'donor_default_user_role', get_option( 'default_role', $baseRole ) );
            if(!current_user_can('manage_options')){
                if('administrator' === $value) {
                    if(!in_array('administrator', $defaultUserRoles)) {
                        $value = $baseRole;
                    }
                }
            }

            return $value;
        }

        /**
         * @since 3.18.0
         */
        public function _render_give_based_form_editor_notice($field, $value)
        {
            if (OptionBasedFormEditor::isEnabled()) {
                ?>
                <tr valign="top" <?php
                echo ! empty($field['wrapper_class']) ? 'class="' . $field['wrapper_class'] . '"' : ''; ?>>
                    <th scope="row" class="titledesc">
                    </th>
                    <td class="give-forminp">
                        <div class="give_option_based_form_editor_notice">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M10.678 1.39a1.667 1.667 0 0 0-1.355 0c-.333.148-.549.409-.7.621-.147.21-.306.483-.48.784l-6.89 11.9c-.174.301-.333.576-.441.809-.11.237-.228.555-.19.918.048.47.295.898.677 1.176.295.214.63.271.89.295.256.023.573.023.922.023H16.89c.349 0 .666 0 .922-.023.26-.024.594-.08.89-.295.382-.278.628-.706.677-1.176.038-.363-.08-.681-.19-.918a10.943 10.943 0 0 0-.442-.81l-6.89-11.9a10.856 10.856 0 0 0-.48-.783c-.15-.212-.367-.473-.7-.621zm.156 6.11a.833.833 0 0 0-1.667 0v3.333a.833.833 0 0 0 1.667 0V7.5zM10 13.333A.833.833 0 0 0 10 15h.009a.833.833 0 0 0 0-1.667H10z"
                                      fill="#F29718" />
                            </svg>
                            <p>
                                <?php
                                echo esc_html__('We recommend moving away from Legacy settings and the Option-Based Editor as they are not compatible with newer features and will eventually be removed.',
                                    'give'); ?>
                            </p>
                        </div>
                    </td>
                </tr>
                <?php
            }
        }
    }

endif;

return new Give_Settings_Advanced();
