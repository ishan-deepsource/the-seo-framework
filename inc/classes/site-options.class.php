<?php
/**
 * @package The_SEO_Framework\Classes\Facade\Site_Options
 * @subpackage The_SEO_Framework\Data
 */

namespace The_SEO_Framework;

\defined( 'THE_SEO_FRAMEWORK_PRESENT' ) or die;

/**
 * The SEO Framework plugin
 * Copyright (C) 2015 - 2021 Sybre Waaijer, CyberWire B.V. (https://cyberwire.nl/)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 3 as published
 * by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Class The_SEO_Framework\Site_Options
 *
 * Handles Site Options for the plugin.
 *
 * @since 2.8.0
 */
class Site_Options extends Sanitize {

	/**
	 * Hold the SEO Settings Page ID for this plugin.
	 *
	 * @since 2.2.2
	 * @since 2.7.0 Renamed var from page_id and made public.
	 *
	 * @var string The page ID
	 */
	public $seo_settings_page_slug = 'theseoframework-settings';

	/**
	 * Holds default site options.
	 *
	 * @since 2.6.0
	 * @since 3.1.0 Now applies filters 'the_seo_framework_default_site_options'
	 * @since 4.0.0 `home_title_location` is now switched from right to left, or vice-versa.
	 *
	 * @return array Default site options.
	 */
	public function get_default_site_options() {

		if ( \is_rtl() ) {
			$titleloc   = 'left';
			$h_titleloc = 'left';
		} else {
			$titleloc   = 'right';
			$h_titleloc = 'right';
		}

		// phpcs:disable, WordPress.Arrays.MultipleStatementAlignment -- precision alignment OK.
		/**
		 * @since 2.2.7
		 * @param array $options The default site options.
		 */
		return (array) \apply_filters(
			'the_seo_framework_default_site_options',
			[
				// General. Performance.
				'alter_search_query'  => 1, // Search query adjustments.
				'alter_archive_query' => 1, // Archive query adjustments.

				'alter_archive_query_type' => 'in_query', // Archive query type.
				'alter_search_query_type'  => 'in_query', // Search query type.

				'cache_sitemap' => 1, // Sitemap transient cache.

				// General. Layout.
				'display_seo_bar_tables'  => 1, // SEO Bar post-list tables.
				'display_seo_bar_metabox' => 0, // SEO Bar post SEO Settings.
				'seo_bar_symbols'         => 0, // SEO Bar symbolic display settings.

				'display_pixel_counter'     => 1, // Pixel counter.
				'display_character_counter' => 1, // Character counter.

				// General. Canonical.
				'canonical_scheme' => 'automatic', // Canonical URL scheme.

				// General. Timestamps.
				'timestamps_format' => '1', // Timestamp format, numeric string

				// General. Exclusions.
				'disabled_post_types' => [], // Post Type support.
				'disabled_taxonomies' => [], // Taxonomy support.

				// Title.
				'title_separator'     => 'hyphen',  // Title separator, dropdown
				'title_location'      => $titleloc, // Title separation location
				'title_rem_additions' => 0,         // Remove title additions
				'title_rem_prefixes'  => 0,         // Remove title prefixes from archives.
				'title_strip_tags'    => 1,         // Apply 'strip tags' on titles.

				// Description.
				'auto_description' => 1, // Enables auto description.

				// Robots index.
				'author_noindex' => 0, // Author Archive robots noindex
				'date_noindex'   => 1, // Date Archive robots noindex
				'search_noindex' => 1, // Search Page robots noindex
				'site_noindex'   => 0, // Site Page robots noindex
				$this->get_robots_post_type_option_id( 'noindex' ) => [
					'attachment' => 1,
				], // Post Type support.
				$this->get_robots_taxonomy_option_id( 'noindex' ) => [
					'post_format' => 1,
				], // Taxonomy support.

				// Robots follow.
				'author_nofollow' => 0, // Author Archive robots nofollow
				'date_nofollow'   => 0, // Date Archive robots nofollow
				'search_nofollow' => 0, // Search Page robots nofollow
				'site_nofollow'   => 0, // Site Page robots nofollow
				$this->get_robots_post_type_option_id( 'nofollow' ) => [], // Post Type support.
				$this->get_robots_taxonomy_option_id( 'nofollow' ) => [], // Taxonomy support.

				// Robots archive.
				'author_noarchive' => 0, // Author Archive robots noarchive
				'date_noarchive'   => 0, // Date Archive robots noarchive
				'search_noarchive' => 0, // Search Page robots noarchive
				'site_noarchive'   => 0, // Site Page robots noarchive
				$this->get_robots_post_type_option_id( 'noarchive' ) => [], // Post Type support.
				$this->get_robots_taxonomy_option_id( 'noarchive' ) => [], // Taxonomy support.

				// Robots query protection.
				'advanced_query_protection' => 1,

				// Robots pagination index.
				'paged_noindex'      => 0, // Every second or later page noindex
				'home_paged_noindex' => 0, // Every second or later homepage noindex

				// Robots copyright.
				'set_copyright_directives' => 1,          // Allow copyright directive settings.
				'max_snippet_length'       => -1,         // Max text-snippet length. -1 = unlimited, 0 = disabled, R>0 = characters.
				'max_image_preview'        => 'standard', // Max image-preview size. 'none', 'standard', 'large'.
				'max_video_preview'        => -1,         // Max video-preview size. -1 = unlimited, 0 = disabled, R>0 = seconds.

				// Robots home.
				'homepage_noindex'   => 0, // Homepage robots noindex
				'homepage_nofollow'  => 0, // Homepage robots noarchive
				'homepage_noarchive' => 0, // Homepage robots nofollow

				// Home meta.
				'homepage_title'         => '', // Homepage Title string
				'homepage_tagline'       => 1,  // Homepage add blog Tagline
				'homepage_description'   => '', // Homepage Description string
				'homepage_title_tagline' => '', // Homepage Tagline string
				'home_title_location'    => $h_titleloc, // Title separation location

				// Homepage Social.
				'homepage_og_title'            => '',
				'homepage_og_description'      => '',
				'homepage_twitter_title'       => '',
				'homepage_twitter_description' => '',

				'homepage_social_image_url' => '',
				'homepage_social_image_id'  => 0,

				// Relationships.
				'shortlink_tag'       => 0, // Adds shortlink tag
				'prev_next_posts'     => 1, // Adds next/prev tags
				'prev_next_archives'  => 1, // Adds next/prev tags
				'prev_next_frontpage' => 1, // Adds next/prev tags

				// Facebook.
				'facebook_publisher' => '', // Facebook Business URL
				'facebook_author'    => '', // Facebook User URl
				'facebook_appid'     => '', // Facebook App ID

				// Dates.
				'post_publish_time' => 1, // Article Published Time
				'post_modify_time'  => 1, // Article Modified Time

				// Twitter.
				'twitter_card'    => 'summary_large_image', // Twitter Card layout. If no twitter:image image is found, it'll change to 'summary', radio
				'twitter_site'    => '', // Twitter business @username
				'twitter_creator' => '', // Twitter user @username

				// oEmbed.
				'oembed_use_og_title'     => 0, // Use custom meta titles in oEmbeds
				'oembed_use_social_image' => 1, // Use social images in oEmbeds
				'oembed_remove_author'    => 1, // Remove author from oEmbeds

				// Social on/off.
				'og_tags'        => 1, // Output of Open Graph meta tags
				'facebook_tags'  => 1, // Output the Facebook meta tags
				'twitter_tags'   => 1, // Output the Twitter meta tags
				'oembed_scripts' => 1, // Enable WordPress's oEmbed scripts

				// Social title settings.
				'social_title_rem_additions' => 1, // Remove social title additions

				// Social image settings.
				'multi_og_image' => 0, // Allow multiple images to be generated

				// Theme color settings.
				'theme_color' => '', // Theme color metatag, default none

				// Social FallBack images (fb = fallback)
				'social_image_fb_url' => '', // Fallback image URL
				'social_image_fb_id'  => 0,  // Fallback image ID

				// Webmasters.
				'google_verification' => '', // Google Verification Code
				'bing_verification'   => '', // Bing Verification Code
				'yandex_verification' => '', // Yandex Verification Code
				'baidu_verification'  => '', // Baidu Verification Code
				'pint_verification'   => '', // Pinterest Verification Code

				// Knowledge general. https://developers.google.com/structured-data/customize/contact-points - This is extremely extended and valuable. Expect a premium version.
				'knowledge_output' => 1,              // Default for outputing the Knowledge SEO.
				'knowledge_type'   => 'organization', // Organization or Person, dropdown

				// Knowledge business. https://developers.google.com/structured-data/customize/logos
				'knowledge_logo' => 1,  // Use Knowledge Logo from anywhere.
				'knowledge_name' => '', // Person or Organization name

				// Knowledge Logo image.
				'knowledge_logo_url'   => '',
				'knowledge_logo_id'    => 0,

				// Knowledge sameas locations.
				'knowledge_facebook'   => '', // Facebook Account
				'knowledge_twitter'    => '', // Twitter Account
				'knowledge_gplus'      => '', // Google Plus Account
				'knowledge_instagram'  => '', // Instagram Account
				'knowledge_youtube'    => '', // Youtube Account
				'knowledge_linkedin'   => '', // Linkedin Account
				'knowledge_pinterest'  => '', // Pinterest Account
				'knowledge_soundcloud' => '', // SoundCloud Account
				'knowledge_tumblr'     => '', // Tumblr Account

				// Sitemaps.
				'sitemaps_output'     => 1,    // Output of sitemap
				'sitemap_query_limit' => 1000, // Sitemap post limit.

				'sitemaps_modified' => 1, // Add sitemap modified time.
				'sitemaps_priority' => 0, // Add sitemap priorities.

				'sitemaps_robots' => 1, // Add sitemap location to robots.txt

				'ping_use_cron'           => 1, // Ping using cron
				'ping_google'             => 1, // Ping Google
				'ping_bing'               => 1, // Ping Bing
				'ping_use_cron_prerender' => 0, // Sitemap cron-ping prerender

				'sitemap_styles'       => 1,        // Whether to style the sitemap
				'sitemap_logo'         => 1,        // Whether to add logo to sitemap
				'sitemap_logo_url'     => '',       // Sitemap logo URL
				'sitemap_logo_id'      => 0,        // Sitemap logo ID
				'sitemap_color_main'   => '222222', // Sitemap main color
				'sitemap_color_accent' => '00a0d2', // Sitemap accent color

				// Feed.
				'excerpt_the_feed' => 1, // Generate feed Excerpts
				'source_the_feed'  => 1, // Add backlink at the end of the feed
				'index_the_feed'   => 0, // Add backlink at the end of the feed

				// Schema.
				'ld_json_searchbox'   => 1, // LD+Json Sitelinks Searchbox
				'ld_json_breadcrumbs' => 1, // LD+Json Breadcrumbs
			]
		);
		// phpcs:enable, WordPress.Arrays.MultipleStatementAlignment
	}

	/**
	 * Holds warned site options array.
	 *
	 * @since 2.6.0
	 * @since 2.9.0 Removed all non-warned settings.
	 * @since 3.1.0 Now applies the "the_seo_framework_warned_site_options" filter.
	 * @since 4.1.0 Added robots' post type setting warnings.
	 * @since 4.1.2 Added `ping_use_cron_prerender`.
	 *
	 * @return array $options.
	 */
	public function get_warned_site_options() {
		/**
		 * Warned site settings. Only accepts checkbox options.
		 * When listed as 1, it's a feature which can destroy your website's SEO value when checked.
		 *
		 * Unchecking a box is simply "I'm not active." - Removing features generally do not negatively impact SEO value.
		 * Since it's all about the content.
		 *
		 * Only used within the SEO Settings page.
		 *
		 * @since 2.3.4
		 * @param array $options The warned site options.
		 */
		return (array) \apply_filters(
			'the_seo_framework_warned_site_options',
			[
				'title_rem_additions'     => 1, // Title remove additions.
				'site_noindex'            => 1, // Site Page robots noindex.
				'site_nofollow'           => 1, // Site Page robots nofollow.
				'homepage_noindex'        => 1, // Homepage robots noindex.
				'homepage_nofollow'       => 1, // Homepage robots noarchive.
				$this->get_robots_post_type_option_id( 'noindex' ) => [
					'post' => 1,
					'page' => 1,
				],
				$this->get_robots_post_type_option_id( 'nofollow' ) => [
					'post' => 1,
					'page' => 1,
				],
				'ping_use_cron_prerender' => 1, // Sitemap cron-ping prerender.
			]
		);
	}

	/**
	 * Return SEO options from the SEO options database.
	 *
	 * @since 2.2.2
	 * @since 2.8.2 No longer decodes entities on request.
	 * @since 3.1.0 Now uses the filterable call when caching is disabled.
	 * @uses THE_SEO_FRAMEWORK_SITE_OPTIONS
	 *
	 * @param string  $key       Option name.
	 * @param boolean $use_cache Optional. Whether to use the cache value or not. Defaults to true.
	 * @return mixed The value of this $key in the database. Empty string when not set.
	 */
	public function get_option( $key, $use_cache = true ) {

		if ( ! $use_cache ) {
			$options = $this->get_all_options( THE_SEO_FRAMEWORK_SITE_OPTIONS, true );
			return isset( $options[ $key ] ) ? \stripslashes_deep( $options[ $key ] ) : '';
		}

		static $cache = [];

		// PHP 7.4: null coalesce equal operator: ??=
		if ( ! isset( $cache[ THE_SEO_FRAMEWORK_SITE_OPTIONS ] ) )
			$cache[ THE_SEO_FRAMEWORK_SITE_OPTIONS ] = \stripslashes_deep( $this->get_all_options( THE_SEO_FRAMEWORK_SITE_OPTIONS ) );

		// TODO fall back to default if not registered? This means we no longer have to rely on upgrading. Or, array merge (recursive) at get_all_options?
		return $cache[ THE_SEO_FRAMEWORK_SITE_OPTIONS ][ $key ] ?? '';
	}

	/**
	 * Return current option array.
	 * Memoizes the return value, can be bypassed and reset with second parameter.
	 *
	 * @since 2.6.0
	 * @since 2.9.2 Added $use_current parameter.
	 *
	 * @param string $setting The setting key.
	 * @param bool   $reset   Whether to use WordPress's version and update the cache
	 *                        or use the locally cached version.
	 * @return array Options.
	 */
	public function get_all_options( $setting = null, $reset = false ) {

		static $cache = [];

		if ( ! $reset && isset( $cache[ $setting ] ) )
			return $cache[ $setting ];

		if ( ! $setting )
			$setting = THE_SEO_FRAMEWORK_SITE_OPTIONS;

		/**
		 * @since 2.0.0
		 * @since 4.1.4 1. Now considers headlessness.
		 *              2. Now returns a 3rd parameter: boolean $headless.
		 *
		 * @param array  $settings The settings
		 * @param string $setting  The settings field.
		 * @param bool   $headless Whether the options are headless.
		 */
		return $cache[ $setting ] = \apply_filters_ref_array(
			'the_seo_framework_get_options',
			[
				$this->is_headless['settings'] && THE_SEO_FRAMEWORK_SITE_OPTIONS === $setting
					? $this->get_default_site_options()
					: \get_option( $setting ),
				$setting,
				$this->is_headless['settings'],
			]
		);
	}

	/**
	 * Return Default SEO options from the SEO options array.
	 *
	 * @since 2.2.5
	 * @uses $this->get_default_settings() Return option from the options table and cache result.
	 * @uses THE_SEO_FRAMEWORK_SITE_OPTIONS
	 *
	 * @param string  $key       Required. The option name.
	 * @param boolean $use_cache Optional. Whether to use the cache value or not.
	 * @return mixed The value of this $key in the database.
	 */
	public function get_default_option( $key, $use_cache = true ) {
		return $this->get_default_settings( $key, '', $use_cache );
	}

	/**
	 * Register the database settings for storage.
	 *
	 * @since 2.2.2
	 * @since 2.9.0 Removed reset options check, see check_options_reset().
	 * @since 3.1.0 Removed settings field existence check.
	 * @since 4.0.0 Now checks if the option exists before adding it. Shaves 20μs...
	 * @thanks StudioPress (http://www.studiopress.com/) for some code.
	 *
	 * @return void Early if settings can't be registered.
	 */
	public function register_settings() {

		\register_setting( THE_SEO_FRAMEWORK_SITE_OPTIONS, THE_SEO_FRAMEWORK_SITE_OPTIONS );
		\get_option( THE_SEO_FRAMEWORK_SITE_OPTIONS )
			or \add_option( THE_SEO_FRAMEWORK_SITE_OPTIONS, $this->get_default_site_options() );

		// Check whether the Options Reset initialization has been added.
		$this->check_options_reset();

		// Handle post-update actions. Must be initialized on admin_init and is initalized on options.php.
		if ( 'options.php' === $GLOBALS['pagenow'] )
			$this->process_settings_submission();
	}

	/**
	 * Retrieves a single caching option.
	 *
	 * @since 3.1.0
	 *
	 * @param string $key     The option key. Required.
	 * @param string $default The default cache value.
	 * @return mixed Cache value on success, $default if non-existent.
	 */
	public function get_static_cache( $key, $default = false ) {
		return \get_option( THE_SEO_FRAMEWORK_SITE_CACHE, [] )[ $key ] ?? $default;
	}

	/**
	 * Updates a single caching option.
	 *
	 * Can return false if option is unchanged.
	 *
	 * @since 3.1.0
	 *
	 * @param string $key   The cache key. Required.
	 * @param string $value The cache value.
	 * @return bool True on success, false on failure.
	 */
	public function update_static_cache( $key, $value = '' ) {

		if ( ! $key ) {
			$this->_doing_it_wrong( __METHOD__, 'No valid cache key has been specified.', '3.1.0' );
			return false;
		}

		return $this->update_settings( [ $key => $value ], THE_SEO_FRAMEWORK_SITE_CACHE );
	}

	/**
	 * Checks for options reset, and reset them.
	 *
	 * @since 2.9.0
	 *
	 * @return void Early if not on SEO settings page.
	 */
	protected function check_options_reset() {

		// Check if we're already dealing with the settings. Buggy cache might interfere, otherwise.
		if ( ! $this->is_seo_settings_page( false ) || ! $this->can_access_settings() )
			return;

		if ( $this->get_option( 'tsf-settings-reset', false ) ) {
			if ( \update_option( THE_SEO_FRAMEWORK_SITE_OPTIONS, $this->get_default_site_options() ) ) {
				$this->update_static_cache( 'settings_notice', 'reset' );
			} else {
				$this->update_static_cache( 'settings_notice', 'error' );
			}
			$this->admin_redirect( $this->seo_settings_page_slug );
			exit;
		}
	}

	/**
	 * Updates a single SEO option.
	 *
	 * Can return false if option is unchanged.
	 *
	 * @since 2.9.0
	 *
	 * @param string $key   The option key.
	 * @param string $value The option value.
	 * @return bool True on success, false on failure.
	 */
	public function update_option( $key = '', $value = '' ) {

		if ( ! $key ) {
			$this->_doing_it_wrong( __METHOD__, 'No option key has been specified.', '2.9.0' );
			return false;
		}

		return $this->update_settings( [ $key => $value ] );
	}

	/**
	 * Allows bulk-updating of the SEO settings.
	 *
	 * @since 2.7.0
	 *
	 * @param string|array $new_option : {
	 *      if string: The string will act as a key for a new empty string option, e.g. : {
	 *           'sitemap_index' becomes ['sitemap_index' => '']
	 *      }
	 *      if array: The option name(s) and value(s), e.g. : {
	 *            ['sitemap_index' => 1]
	 *      }
	 * }
	 * @param string       $settings_field The Settings Field to update. Defaults
	 *                                     to The SEO Framework settings field.
	 * @return bool True on success. False on failure.
	 */
	public function update_settings( $new_option = '', $settings_field = '' ) {

		if ( ! $settings_field ) {
			$settings_field = THE_SEO_FRAMEWORK_SITE_OPTIONS;
			$this->init_sanitizer_filters();
		}

		$settings = \wp_parse_args( $new_option, \get_option( $settings_field ) );

		return \update_option( $settings_field, $settings );
	}

	/**
	 * Get the default of any of the The SEO Framework settings.
	 *
	 * @since 2.2.4
	 * @since 2.8.2 No longer decodes entities on request.
	 * @since 3.1.0 : 1. Now returns null if the option doesn't exist, instead of -1.
	 *                2. Is now influenced by filters.
	 *                3. Now also strips slashes when using cache.
	 *                4. The second parameter is deprecated.
	 * @uses $this->get_default_site_options()
	 *
	 * @param string $key       Required. The option name.
	 * @param string $depr      Deprecated. Leave empty.
	 * @param bool   $use_cache Optional. Whether to use the options cache or bypass it.
	 * @return mixed default option
	 *         null If option doesn't exist.
	 */
	public function get_default_settings( $key, $depr = '', $use_cache = true ) {

		if ( ! $key ) return false;

		if ( $depr )
			$this->_doing_it_wrong( __METHOD__, 'The second parameter is deprecated.', '3.1.0' );

		// If we need to bypass the cache
		if ( ! $use_cache ) {
			$defaults = $this->get_default_site_options();
			return isset( $defaults[ $key ] ) ? \stripslashes_deep( $defaults[ $key ] ) : null;
		}

		static $cache;

		if ( ! isset( $cache ) )
			$cache = \stripslashes_deep( $this->get_default_site_options() );

		return $cache[ $key ] ?? null;
	}

	/**
	 * Get the warned setting of any of the The SEO Framework settings.
	 *
	 * @since 2.3.4
	 * @since 3.1.0 Now returns 0 if the option doesn't exist, instead of -1.
	 * @uses THE_SEO_FRAMEWORK_SITE_OPTIONS
	 * @uses $this->get_warned_site_options()
	 *
	 * @param string $key       Required. The option name.
	 * @param string $depr      Deprecated. Leave empty.
	 * @param bool   $use_cache Optional. Whether to use the options cache or bypass it.
	 * @return int 0|1 Whether the option is flagged as dangerous for SEO.
	 */
	public function get_warned_settings( $key, $depr = '', $use_cache = true ) {

		if ( empty( $key ) )
			return false;

		if ( $depr )
			$this->_doing_it_wrong( __METHOD__, 'The second parameter is deprecated.', '3.1.0' );

		// If we need to bypass the cache
		if ( ! $use_cache ) {
			$warned = $this->get_warned_site_options();
			return $this->s_one_zero( ! empty( $warned[ $key ] ) );
		}

		static $cache;

		if ( ! isset( $cache ) )
			$cache = $this->get_warned_site_options();

		return $this->s_one_zero( ! empty( $cache[ $key ] ) );
	}

	/**
	 * Returns the option value for Post Type robots settings.
	 *
	 * @since 3.1.0
	 *
	 * @param string $type Accepts 'noindex', 'nofollow', 'noarchive'.
	 * @return string
	 */
	public function get_robots_post_type_option_id( $type ) {
		return $this->s_field_id( "{$type}_post_types" );
	}

	/**
	 * Returns the option value for Taxonomy robots settings.
	 *
	 * @since 4.1.0
	 *
	 * @param string $type Accepts 'noindex', 'nofollow', 'noarchive'.
	 * @return string
	 */
	public function get_robots_taxonomy_option_id( $type ) {
		return $this->s_field_id( "{$type}_taxonomies" );
	}

	/**
	 * Returns supported social site locales.
	 *
	 * @since 4.2.0
	 * @see https://www.facebook.com/translations/FacebookLocales.xml (deprecated)
	 * @see https://wordpress.org/support/topic/oglocale-problem/#post-11456346
	 * mirror: http://web.archive.org/web/20190601043836/https://wordpress.org/support/topic/oglocale-problem/
	 *
	 * @return array Valid social locales
	 */
	public function supported_social_locales() {
		return [
			'af_ZA' => 'af',  // Afrikaans
			'ak_GH' => 'ak',  // Akan
			'am_ET' => 'am',  // Amharic
			'ar_AR' => 'ar',  // Arabic
			'as_IN' => 'as',  // Assamese
			'ay_BO' => 'ay',  // Aymara
			'az_AZ' => 'az',  // Azerbaijani
			'be_BY' => 'be',  // Belarusian
			'bg_BG' => 'bg',  // Bulgarian
			'bn_IN' => 'bn',  // Bengali
			'br_FR' => 'br',  // Breton
			'bs_BA' => 'bs',  // Bosnian
			'ca_ES' => 'ca',  // Catalan
			'cb_IQ' => 'cb',  // Sorani Kurdish
			'ck_US' => 'ck',  // Cherokee
			'co_FR' => 'co',  // Corsican
			'cs_CZ' => 'cs',  // Czech
			'cx_PH' => 'cx',  // Cebuano
			'cy_GB' => 'cy',  // Welsh
			'da_DK' => 'da',  // Danish
			'de_DE' => 'de',  // German
			'el_GR' => 'el',  // Greek
			'en_GB' => 'en',  // English (UK)
			'en_IN' => 'en',  // English (India)
			'en_PI' => 'en',  // English (Pirate)
			'en_UD' => 'en',  // English (Upside Down)
			'en_US' => 'en',  // English (US)
			'eo_EO' => 'eo',  // Esperanto
			'es_CL' => 'es',  // Spanish (Chile)
			'es_CO' => 'es',  // Spanish (Colombia)
			'es_ES' => 'es',  // Spanish (Spain)
			'es_LA' => 'es',  // Spanish
			'es_MX' => 'es',  // Spanish (Mexico)
			'es_VE' => 'es',  // Spanish (Venezuela)
			'et_EE' => 'et',  // Estonian
			'eu_ES' => 'eu',  // Basque
			'fa_IR' => 'fa',  // Persian
			'fb_LT' => 'fb',  // Leet Speak
			'ff_NG' => 'ff',  // Fulah
			'fi_FI' => 'fi',  // Finnish
			'fo_FO' => 'fo',  // Faroese
			'fr_CA' => 'fr',  // French (Canada)
			'fr_FR' => 'fr',  // French (France)
			'fy_NL' => 'fy',  // Frisian
			'ga_IE' => 'ga',  // Irish
			'gl_ES' => 'gl',  // Galician
			'gn_PY' => 'gn',  // Guarani
			'gu_IN' => 'gu',  // Gujarati
			'gx_GR' => 'gx',  // Classical Greek
			'ha_NG' => 'ha',  // Hausa
			'he_IL' => 'he',  // Hebrew
			'hi_IN' => 'hi',  // Hindi
			'hr_HR' => 'hr',  // Croatian
			'hu_HU' => 'hu',  // Hungarian
			'hy_AM' => 'hy',  // Armenian
			'id_ID' => 'id',  // Indonesian
			'ig_NG' => 'ig',  // Igbo
			'is_IS' => 'is',  // Icelandic
			'it_IT' => 'it',  // Italian
			'ja_JP' => 'ja',  // Japanese
			'ja_KS' => 'ja',  // Japanese (Kansai)
			'jv_ID' => 'jv',  // Javanese
			'ka_GE' => 'ka',  // Georgian
			'kk_KZ' => 'kk',  // Kazakh
			'km_KH' => 'km',  // Khmer
			'kn_IN' => 'kn',  // Kannada
			'ko_KR' => 'ko',  // Korean
			'ku_TR' => 'ku',  // Kurdish (Kurmanji)
			'ky_KG' => 'ky',  // Kyrgyz
			'la_VA' => 'la',  // Latin
			'lg_UG' => 'lg',  // Ganda
			'li_NL' => 'li',  // Limburgish
			'ln_CD' => 'ln',  // Lingala
			'lo_LA' => 'lo',  // Lao
			'lt_LT' => 'lt',  // Lithuanian
			'lv_LV' => 'lv',  // Latvian
			'mg_MG' => 'mg',  // Malagasy
			'mi_NZ' => 'mi',  // Māori
			'mk_MK' => 'mk',  // Macedonian
			'ml_IN' => 'ml',  // Malayalam
			'mn_MN' => 'mn',  // Mongolian
			'mr_IN' => 'mr',  // Marathi
			'ms_MY' => 'ms',  // Malay
			'mt_MT' => 'mt',  // Maltese
			'my_MM' => 'my',  // Burmese
			'nb_NO' => 'nb',  // Norwegian (bokmal)
			'nd_ZW' => 'nd',  // Ndebele
			'ne_NP' => 'ne',  // Nepali
			'nl_BE' => 'nl',  // Dutch (België)
			'nl_NL' => 'nl',  // Dutch
			'nn_NO' => 'nn',  // Norwegian (nynorsk)
			'ny_MW' => 'ny',  // Chewa
			'or_IN' => 'or',  // Oriya
			'pa_IN' => 'pa',  // Punjabi
			'pl_PL' => 'pl',  // Polish
			'ps_AF' => 'ps',  // Pashto
			'pt_BR' => 'pt',  // Portuguese (Brazil)
			'pt_PT' => 'pt',  // Portuguese (Portugal)
			'qu_PE' => 'qu',  // Quechua
			'rm_CH' => 'rm',  // Romansh
			'ro_RO' => 'ro',  // Romanian
			'ru_RU' => 'ru',  // Russian
			'rw_RW' => 'rw',  // Kinyarwanda
			'sa_IN' => 'sa',  // Sanskrit
			'sc_IT' => 'sc',  // Sardinian
			'se_NO' => 'se',  // Northern Sámi
			'si_LK' => 'si',  // Sinhala
			'sk_SK' => 'sk',  // Slovak
			'sl_SI' => 'sl',  // Slovenian
			'sn_ZW' => 'sn',  // Shona
			'so_SO' => 'so',  // Somali
			'sq_AL' => 'sq',  // Albanian
			'sr_RS' => 'sr',  // Serbian
			'sv_SE' => 'sv',  // Swedish
			'sy_SY' => 'sy',  // Swahili
			'sw_KE' => 'sw',  // Syriac
			'sz_PL' => 'sz',  // Silesian
			'ta_IN' => 'ta',  // Tamil
			'te_IN' => 'te',  // Telugu
			'tg_TJ' => 'tg',  // Tajik
			'th_TH' => 'th',  // Thai
			'tk_TM' => 'tk',  // Turkmen
			'tl_PH' => 'tl',  // Filipino
			'tl_ST' => 'tl',  // Klingon
			'tr_TR' => 'tr',  // Turkish
			'tt_RU' => 'tt',  // Tatar
			'tz_MA' => 'tz',  // Tamazight
			'uk_UA' => 'uk',  // Ukrainian
			'ur_PK' => 'ur',  // Urdu
			'uz_UZ' => 'uz',  // Uzbek
			'vi_VN' => 'vi',  // Vietnamese
			'wo_SN' => 'wo',  // Wolof
			'xh_ZA' => 'xh',  // Xhosa
			'yi_DE' => 'yi',  // Yiddish
			'yo_NG' => 'yo',  // Yoruba
			'zh_CN' => 'zh',  // Simplified Chinese (China)
			'zh_HK' => 'zh',  // Traditional Chinese (Hong Kong)
			'zh_TW' => 'zh',  // Traditional Chinese (Taiwan)
			'zu_ZA' => 'zu',  // Zulu
			'zz_TR' => 'zz',  // Zazaki
		];
	}
}
