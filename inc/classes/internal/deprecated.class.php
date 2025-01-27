<?php
/**
 * @package The_SEO_Framework\Classes\Internal\Deprecated
 * @subpackage The_SEO_Framework\Debug\Deprecated
 */

namespace The_SEO_Framework\Internal;

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

\defined( 'THE_SEO_FRAMEWORK_PRESENT' ) or die;

use function The_SEO_Framework\memo; // Precautionary.

/**
 * Class The_SEO_Framework\Internal\Deprecated
 *
 * Contains all deprecated functions.
 *
 * @since 2.8.0
 * @since 3.1.0 Removed all methods deprecated in 3.0.0.
 * @since 4.0.0 Removed all methods deprecated in 3.1.0.
 * @since 4.1.4 Removed all methods deprecated in 4.0.0.
 * @since 4.2.0 1. Changed namespace from \The_SEO_Framework to \The_SEO_Framework\Internal
 *              2. Removed all methods deprecated in 4.1.0.
 * @ignore
 */
final class Deprecated {

	/**
	 * Appends given query to given URL.
	 *
	 * @since 3.0.0
	 * @since 3.1.0 Now uses parse_str and add_query_arg, preventing duplicated entries.
	 * @since 4.1.4 Deprecated silently.
	 * @since 4.2.0 Hard deprecation.
	 * @deprecated
	 *
	 * @param string $url   A fully qualified URL.
	 * @param string $query A fully qualified query taken from parse_url( $url, PHP_URL_QUERY );
	 * @return string A fully qualified URL with appended $query.
	 */
	public function append_php_query( $url, $query = '' ) {
		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->append_php_query()', '4.2.0', 'the_seo_framework()->append_url_query()' );
		return $tsf->append_url_query( $url, $query );
	}

	/**
	 * Generates front-end HTMl output.
	 *
	 * @since 4.0.5
	 * @since 4.1.4 Deprecated silently.
	 * @since 4.2.0 Hard deprecation.
	 * @deprecated
	 *
	 * @return string The HTML output.
	 */
	public function get_html_output() {

		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->get_html_output()', '4.2.0' );

		$robots = $tsf->robots();

		/** @since 4.0.4 Added as WP 5.3 patch. */
		$tsf->set_timezone( 'UTC' );

		/**
		 * @since 2.6.0
		 * @param string $before The content before the SEO output.
		 */
		$before = (string) \apply_filters( 'the_seo_framework_pre', '' );

		$before_legacy = $tsf->get_legacy_header_filters_output( 'before' );

		// Limit processing and redundant tags on 404 and search.
		if ( $tsf->is_search() ) :
			$output = $tsf->og_locale()
					. $tsf->og_type()
					. $tsf->og_title()
					. $tsf->og_url()
					. $tsf->og_sitename()
					. $tsf->theme_color()
					. $tsf->shortlink()
					. $tsf->canonical()
					. $tsf->paged_urls()
					. $tsf->google_site_output()
					. $tsf->bing_site_output()
					. $tsf->yandex_site_output()
					. $tsf->baidu_site_output()
					. $tsf->pint_site_output();
		elseif ( $tsf->is_404() ) :
			$output = $tsf->theme_color()
					. $tsf->google_site_output()
					. $tsf->bing_site_output()
					. $tsf->yandex_site_output()
					. $tsf->baidu_site_output()
					. $tsf->pint_site_output();
		elseif ( $tsf->is_query_exploited() ) :
			// aqp = advanced query protection
			$output = '<meta name="tsf:aqp" value="1" />' . PHP_EOL;
		else :
			// Inefficient concatenation is inefficient. Improve this?
			$output = $tsf->the_description()
					. $tsf->og_image()
					. $tsf->og_locale()
					. $tsf->og_type()
					. $tsf->og_title()
					. $tsf->og_description()
					. $tsf->og_url()
					. $tsf->og_sitename()
					. $tsf->facebook_publisher()
					. $tsf->facebook_author()
					. $tsf->facebook_app_id()
					. $tsf->article_published_time()
					. $tsf->article_modified_time()
					. $tsf->twitter_card()
					. $tsf->twitter_site()
					. $tsf->twitter_creator()
					. $tsf->twitter_title()
					. $tsf->twitter_description()
					. $tsf->twitter_image()
					. $tsf->theme_color()
					. $tsf->shortlink()
					. $tsf->canonical()
					. $tsf->paged_urls()
					. $tsf->ld_json()
					. $tsf->google_site_output()
					. $tsf->bing_site_output()
					. $tsf->yandex_site_output()
					. $tsf->baidu_site_output()
					. $tsf->pint_site_output();
		endif;

		$after_legacy = $tsf->get_legacy_header_filters_output( 'after' );

		/**
		 * @since 2.6.0
		 * @param string $after The content after the SEO output.
		 */
		$after = (string) \apply_filters( 'the_seo_framework_pro', '' );

		/** @since 4.0.4 Added as WP 5.3 patch. */
		$tsf->reset_timezone();

		return "{$robots}{$before}{$before_legacy}{$output}{$after_legacy}{$after}";
	}

	/**
	 * Generates the `noindex` robots meta code array from arguments.
	 *
	 * This method is tailor-made for everything that relies on the noindex-state, as it's
	 * a very controlling and powerful feature.
	 *
	 * Note that the home-as-blog page can be used for this method.
	 *
	 * We deprecated this because in the real world, it barely mattered. We'd much rather
	 * have a proper and predictable API.
	 *
	 * @since 4.0.0
	 * @since 4.1.0 Now uses the new taxonomy robots settings.
	 * @since 4.1.4 Soft deprecated. Use 'robots_meta' instead.
	 * @since 4.2.0 Hard deprecation.
	 * @deprecated
	 *
	 * @param array|null $args   The query arguments. Accepts 'id' and 'taxonomy'.
	 * @param int <bit>  $ignore The ignore level. {
	 *    0 = 0b00: Ignore nothing.
	 *    1 = 0b01: Ignore protection. (\The_SEO_Framework\ROBOTS_IGNORE_PROTECTION)
	 *    2 = 0b10: Ignore post/term setting. (\The_SEO_Framework\ROBOTS_IGNORE_SETTINGS)
	 *    3 = 0b11: Ignore protection and post/term setting.
	 * }
	 * @return bool Whether noindex is set or not
	 */
	public function is_robots_meta_noindex_set_by_args( $args, $ignore = 0b00 ) {
		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->is_robots_meta_noindex_set_by_args()', '4.2.0', 'the_seo_framework()->generate_robots_meta()' );
		$meta = $tsf->generate_robots_meta( $args, null, $ignore );
		return isset( $meta['noindex'] ) && 'noindex' === $meta['noindex'];
	}

	/**
	 * Returns the `noindex`, `nofollow`, `noarchive` robots meta code array.
	 *
	 * @since 2.2.2
	 * @since 2.2.4 Added robots SEO settings check.
	 * @since 2.2.8 Added check for empty archives.
	 * @since 2.8.0 Added check for protected/private posts.
	 * @since 3.0.0 : 1. Removed noodp.
	 *                2. Improved efficiency by grouping if statements.
	 * @since 3.1.0 : 1. Simplified statements, often (not always) speeding things up.
	 *                2. Now checks for wc_shop and blog types for pagination.
	 *                3. Removed noydir.
	 * @since 4.0.0 : 1. Now tests for qubit metadata.
	 *                2. Added custom query support.
	 *                3. Added two parameters.
	 * @since 4.0.2 : 1. Added new copyright directive tags.
	 *                2. Now strictly parses the validity of robots directives via a boolean check.
	 * @since 4.0.3 : 1. Changed `max_snippet_length` to `max_snippet`
	 *                2. Changed the copyright directive's spacer from `=` to `:`.
	 * @since 4.0.5 : 1. Removed copyright directive bug workaround. <https://kb.theseoframework.com/kb/why-is-max-image-preview-none-purged/>
	 *                2. Now sets noindex and nofollow when queries are exploited (requires option enabled).
	 * @since 4.1.4 Deprecated silently. Use generate_robots_meta() instead.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param array|null $args   The query arguments. Accepts 'id' and 'taxonomy'.
	 * @param int <bit>  $ignore The ignore level. {
	 *    0 = 0b00: Ignore nothing.
	 *    1 = 0b01: Ignore protection. (\The_SEO_Framework\ROBOTS_IGNORE_PROTECTION)
	 *    2 = 0b10: Ignore post/term setting. (\The_SEO_Framework\ROBOTS_IGNORE_SETTINGS)
	 *    3 = 0b11: Ignore protection and post/term setting.
	 * }
	 * @return array Only values actualized for display: {
	 *    string index : string value
	 * }
	 */
	public function robots_meta( $args = null, $ignore = 0b00 ) {
		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->robots_meta()', '4.2.0', 'the_seo_framework()->generate_robots_meta()' );
		return $tsf->generate_robots_meta( $args, null, $ignore );
	}

	/**
	 * Determines whether to add a line within robots based by plugin detection, or sitemap output option.
	 *
	 * @since 2.6.0
	 * @since 2.8.0 Added check_option parameter.
	 * @since 2.9.0 Now also checks for subdirectory installations.
	 * @since 2.9.2 Now also checks for permalinks.
	 * @since 2.9.3 Now also checks for sitemap_robots option.
	 * @since 3.1.0 Removed Jetpack's sitemap check -- it's no longer valid.
	 * @since 4.0.0 : 1. Now uses has_robots_txt()
	 *              : 2. Now uses the get_robots_txt_url() to determine validity.
	 * FIXME This method also checks for file existence (and location...), but is only used when the file definitely doesn't exist.
	 * @since 4.1.4 Soft deprecated.
	 * @since 4.2.0 Hard deprecation.
	 * @deprecated
	 *
	 * @param bool $check_option Whether to check for sitemap option.
	 * @return bool True when no conflicting plugins are detected or when The SEO Framework's Sitemaps are output.
	 */
	public function can_do_sitemap_robots( $check_option = true ) {

		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->can_do_sitemap_robots()', '4.2.0' );

		if ( $check_option ) {
			if ( ! $tsf->get_option( 'sitemaps_output' )
			|| ! $tsf->get_option( 'sitemaps_robots' ) )
				return false;
		}

		return ! $tsf->has_robots_txt() && \strlen( $tsf->get_robots_txt_url() );
	}

	/**
	 * Setting nav tab wrappers.
	 * Outputs Tabs and settings content.
	 *
	 * @since 2.3.6
	 * @since 2.6.0 Refactored.
	 * @since 3.1.0 Now prefixes the IDs.
	 * @since 4.0.0 Deprecated third parameter, silently.
	 * @since 4.1.4 Deprecated silently. Use `\The_SEO_Framework\Bridges\SeoSettings::_nav_tab_wrapper()` instead.
	 * @since 4.2.0 Hard deprecation.
	 * @deprecated
	 *
	 * @param string $id      The nav-tab ID
	 * @param array  $tabs    The tab content {
	 *    string tab ID => array : {
	 *       string   name     : Tab name.
	 *       callable callback : Output function.
	 *       string   dashicon : The dashicon to use.
	 *       mixed    args     : Optional callback function args.
	 *    }
	 * }
	 * @param null   $depr     Deprecated.
	 * @param bool   $use_tabs Whether to output tabs, only works when $tabs count is greater than 1.
	 */
	public function nav_tab_wrapper( $id, $tabs = [], $depr = null, $use_tabs = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->nav_tab_wrapper()', '4.2.0', '\The_SEO_Framework\Bridges\SeoSettings::_nav_tab_wrapper' );
		\The_SEO_Framework\Bridges\SeoSettings::_nav_tab_wrapper( $id, $tabs, $use_tabs );
	}

	/**
	 * Outputs in-post flex navigational wrapper and its content.
	 *
	 * @since 2.9.0
	 * @since 3.0.0 Converted to view.
	 * @since 4.0.0 Deprecated third parameter, silently.
	 * @since 4.1.4 Deprecated silently. Use `\The_SEO_Framework\Bridges\PostSettings()` instead.
	 * @since 4.2.0 Hard deprecation.
	 * @deprecated
	 *
	 * @param string $id       The nav-tab ID
	 * @param array  $tabs     The tab content {
	 *    string tab ID => array : {
	 *       string   name     : Tab name.
	 *       callable callback : Output function.
	 *       string   dashicon : The dashicon to use.
	 *       mixed    args     : Optional callback function args.
	 *    }
	 * }
	 * @param null   $_depr    Deprecated.
	 * @param bool   $use_tabs Whether to output tabs, only works when $tabs count is greater than 1.
	 */
	public function inpost_flex_nav_tab_wrapper( $id, $tabs = [], $_depr = null, $use_tabs = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->inpost_flex_nav_tab_wrapper()', '4.2.0', '\The_SEO_Framework\Bridges\PostSettings::_flex_nav_tab_wrapper' );
		\The_SEO_Framework\Bridges\PostSettings::_flex_nav_tab_wrapper( $id, $tabs, $use_tabs );
	}

	/**
	 * Returns social image uploader form button.
	 * Also registers additional i18n strings for JS.
	 *
	 * @since 2.8.0
	 * @since 3.1.0 No longer prepares media l10n data.
	 * @since 4.0.0 Now adds a media preview dispenser.
	 * @since 4.1.2 No longer adds a redundant title to the selection button.
	 * @since 4.1.4 Deprecated. Use `get_image_uploader_form()` instead.
	 * @since 4.2.0 Hard deprecation.
	 * @deprecated
	 *
	 * @param string $input_id Required. The HTML input id to pass URL into.
	 * @return string The image uploader button.
	 */
	public function get_social_image_uploader_form( $input_id ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->get_social_image_uploader_form()', '4.2.0', 'The_SEO_Framework\Interpreters\Form::get_image_uploader_form()' );
		return \The_SEO_Framework\Interpreters\Form::get_image_uploader_form( [ 'id' => $input_id ] );
	}

	/**
	 * Returns logo uploader form buttons.
	 * Also registers additional i18n strings for JS.
	 *
	 * @since 3.0.0
	 * @since 3.1.0 No longer prepares media l10n data.
	 * @since 4.0.0 Now adds a media preview dispenser.
	 * @since 4.1.4 Deprecated silently. Use `get_image_uploader_form()` instead.
	 * @since 4.2.0 Hard deprecation.
	 * @deprecated
	 *
	 * @param string $input_id Required. The HTML input id to pass URL into.
	 * @return string The image uploader button.
	 */
	public function get_logo_uploader_form( $input_id ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->get_logo_uploader_form()', '4.2.0', 'The_SEO_Framework\Interpreters\Form::get_image_uploader_form()' );
		return \The_SEO_Framework\Interpreters\Form::get_image_uploader_form( [
			'id'   => $input_id,
			'data' => [
				'inputType' => 'logo',
				'width'     => 512,
				'height'    => 512,
				'minWidth'  => 112,
				'minHeight' => 112,
				'flex'      => true,
			],
			'i18n' => [
				'button_title' => '',
				'button_text'  => \__( 'Select Logo', 'autodescription' ),
			],
		] );
	}

	/**
	 * Proportionate dimensions based on Width and Height.
	 * AKA Aspect Ratio.
	 *
	 * @since 2.6.0
	 * @ignore Unused. The relying methods were yeeted off in 4.0.0.
	 *                 "We no longer automatically resize images when they’re deemed too large."
	 * @since 4.1.4 Deprecated silently. Marked for quick deletion.
	 * @TODO delete me, bypass deprecation? This method makes no sense to the outsider, anyway. -> 4.2.0
	 *
	 * @param int $i  The dimension to resize.
	 * @param int $r1 The dimension that determines the ratio.
	 * @param int $r2 The dimension to proportionate to.
	 * @return int The proportional dimension, rounded.
	 */
	public function proportionate_dimensions( $i, $r1, $r2 ) {
		return round( $i / ( $r1 / $r2 ) );
	}

	/**
	 * Returns the SEO Settings page URL.
	 *
	 * @since 2.6.0
	 * @since 4.1.4 Deprecated silently. Use `get_seo_settings_page_url()` instead.
	 * @since 4.2.0 Hard deprecation.
	 * @deprecated
	 *
	 * @return string The escaped SEO Settings page URL.
	 */
	public function seo_settings_page_url() {
		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->seo_settings_page_url()', '4.2.0', 'the_seo_framework()->get_seo_settings_page_url()' );
		return $tsf->get_seo_settings_page_url();
	}

	/**
	 * Returns default user meta.
	 *
	 * @since 3.0.0
	 * @since 4.1.4 Deprecated silently. Use `get_user_meta_defaults()` instead.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @return array The default user meta index and values.
	 */
	public function get_default_user_data() {
		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->get_default_user_data()', '4.2.0', 'the_seo_framework()->get_user_meta_defaults()' );
		return $tsf->get_user_meta_defaults();
	}

	/**
	 * Fetches user SEO user meta data by name.
	 * Memoizes all meta data per $user_id.
	 *
	 * If no $user_id is supplied, it will fetch the current logged in user ID.
	 * TODO supplement $default===null for $this->get_user_meta_defaults()[$option]?
	 *
	 * @since 2.7.0
	 * @since 3.0.0 1. Default is no longer cached.
	 *              2. Now always fallbacks to $default.
	 *              3. Added not-found cache.
	 * @since 4.1.4 Deprecated silently. Use `get_user_meta()` instead.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param int    $user_id The user ID. When empty, it will try to fetch the current user.
	 * @param string $option  The option name.
	 * @param mixed  $default The default value to return when the data doesn't exist.
	 * @return mixed The metadata value.
	 */
	public function get_user_option( $user_id = 0, $option = '', $default = null ) {
		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->get_user_option()', '4.2.0', 'the_seo_framework()->get_user_meta_item()' );
		return $tsf->get_user_meta_item( $user_id ?: $tsf->get_user_id(), $option ) ?: $default;
	}

	/**
	 * Returns current post author option.
	 *
	 * @since 3.0.0
	 * @since 4.1.4 Silently deprecated. use `get_current_post_author_id()` instead.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param int    $author_id The author ID. When empty, it will return $default.
	 * @param string $option    The option name. When empty, it will return $default.
	 * @param mixed  $default   The default value to return when the data doesn't exist.
	 * @return mixed The metadata value
	 */
	public function get_author_option( $author_id, $option, $default = null ) {
		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->get_author_option()', '4.2.0', 'the_seo_framework()->get_current_post_author_id()' );
		return $tsf->get_user_meta_item( $option, $author_id ?: $tsf->get_current_post_author_id() ) ?: $default;
	}

	/**
	 * Returns current post author option.
	 *
	 * @since 3.0.0
	 * @since 4.1.4 Silently deprecated. Use `get_current_post_author_meta_item()` instead.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $option  The option name.
	 * @param mixed  $default The default value to return when the data doesn't exist.
	 * @return mixed The metadata value
	 */
	public function get_current_author_option( $option, $default = null ) {
		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->get_current_author_option()', '4.2.0', 'the_seo_framework()->get_current_post_author_meta_item()' );
		return $tsf->get_current_post_author_meta_item( $option ) ?: $default;
	}

	/**
	 * Determines if the $post is the WooCommerce plugin shop page.
	 *
	 * @since 2.5.2
	 * @since 4.0.5 Now has a first parameter `$post`.
	 * @since 4.0.5 Soft deprecated.
	 * @since 4.1.4 1. Another silent deprecation. Use `is_shop()` instead.
	 *              2. Removed output memoization.
	 * @since 4.2.0 Hard deprecation.
	 * @deprecated
	 * @internal
	 *
	 * @param int|WP_Post|null $post (Optional) Post ID or post object.
	 * @return bool True if on the WooCommerce shop page.
	 */
	public function is_wc_shop( $post = null ) {

		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->is_wc_shop()', '4.2.0', 'the_seo_framework()->is_shop()' );

		if ( isset( $post ) ) {
			$post = \get_post( $post );
			$id   = $post ? $post->ID : 0;
		} else {
			$id = null;
		}

		if ( isset( $id ) ) {
			$is_shop = (int) \get_option( 'woocommerce_shop_page_id' ) === $id;
		} else {
			$is_shop = ! \is_admin() && \function_exists( 'is_shop' ) && \is_shop();
		}

		return $is_shop;
	}

	/**
	 * Determines if the page is the WooCommerce plugin Product page.
	 *
	 * @since 2.5.2
	 * @since 4.0.0 : 1. Added admin support.
	 *                2. Added parameter for the Post ID or post to test.
	 * @since 4.0.5 Soft deprecated.
	 * @since 4.1.4 1. Another silent deprecation. Use `is_product()` instead.
	 *              2. Removed output memoization.
	 * @since 4.2.0 Hard deprecation.
	 * @deprecated
	 * @internal
	 *
	 * @param int|\WP_Post $post When set, checks if the post is of type product.
	 * @return bool True if on a WooCommerce Product page.
	 */
	public function is_wc_product( $post = 0 ) {

		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->is_wc_product()', '4.2.0', 'the_seo_framework()->is_product()' );

		if ( \is_admin() )
			return $tsf->is_wc_product_admin();

		if ( $post ) {
			$is_product = 'product' === \get_post_type( $post );
		} else {
			$is_product = \function_exists( 'is_product' ) && \is_product();
		}

		return $is_product;
	}

	/**
	 * Detects products within the admin area.
	 *
	 * @since 4.0.0
	 * @see $this->is_wc_product()
	 * @since 4.0.5 Soft deprecated.
	 * @since 4.1.4 1. Another silent deprecation. Use `is_product_admin()` instead.
	 *              2. Removed output memoization.
	 * @since 4.2.0 Hard deprecation.
	 * @deprecated
	 * @internal
	 *
	 * @return bool
	 */
	public function is_wc_product_admin() {
		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->is_wc_product_admin()', '4.2.0', 'the_seo_framework()->is_product_admin()' );
		// Checks for "is_singular_admin()" because the post type is non-hierarchical.
		return $tsf->is_singular_admin() && 'product' === $tsf->get_admin_post_type();
	}

	/**
	 * Updates user SEO option.
	 *
	 * @since 2.7.0
	 * @since 2.8.0 New users now get a new array assigned.
	 * @since 4.1.4 Deprecated silently. Use `update_single_user_meta_item()` instead.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param int    $user_id The user ID.
	 * @param string $option  The user's SEO metadata option.
	 * @param mixed  $value   The escaped option value.
	 * @return bool True on success. False on failure.
	 */
	public function update_user_option( $user_id = 0, $option = '', $value = '' ) {

		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->update_user_option()', '4.2.0', 'the_seo_framework()->update_single_user_meta_item()' );

		if ( ! $option )
			return false;

		if ( empty( $user_id ) )
			$user_id = $tsf->get_user_id();

		if ( empty( $user_id ) )
			return false;

		$meta = $tsf->get_user_meta( $user_id, false );

		/**
		 * @since 2.8.0 initializes new array on empty values.
		 */
		\is_array( $meta ) or $meta = [];

		$meta[ $option ] = $value;

		return \update_user_meta( $user_id, THE_SEO_FRAMEWORK_USER_OPTIONS, $meta );
	}

	/**
	 * Helper function that constructs name attributes for use in form fields.
	 *
	 * Other page implementation classes may wish to construct and use a
	 * get_field_id() method, if the naming format needs to be different.
	 *
	 * @since 2.2.2
	 * @since 4.1.4 Deprecated silently.
	 * @since 4.2.0 Hard deprecation.
	 * @deprecated
	 *
	 * @param string $name Field name base
	 * @return string Full field name
	 */
	public function get_field_name( $name ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->get_field_name()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\Form::get_field_name( $name );
	}

	/**
	 * Echo constructed name attributes in form fields.
	 *
	 * @since 2.2.2
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 * @uses $this->get_field_name() Construct name attributes for use in form fields.
	 *
	 * @param string $name Field name base
	 */
	public function field_name( $name ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->field_name()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\Form::field_name( $name );
	}

	/**
	 * Helper function that constructs id attributes for use in form fields.
	 *
	 * @since 2.2.2
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $id Field id base
	 * @return string Full field id
	 */
	public function get_field_id( $id ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->get_field_id()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\Form::get_field_id( $id );
	}

	/**
	 * Echo constructed id attributes in form fields.
	 *
	 * @since 2.2.2
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 * @uses $this->get_field_id() Constructs id attributes for use in form fields.
	 *
	 * @param string  $id Field id base.
	 * @param boolean $echo Whether to escape echo or just return.
	 * @return string Full field id
	 */
	public function field_id( $id, $echo = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->field_id()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\Form::field_id( $id, $echo );
	}

	/**
	 * Mark up content with code tags.
	 * Escapes all HTML, so `<` gets changed to `&lt;` and displays correctly.
	 *
	 * @since 2.0.0
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $content Content to be wrapped in code tags.
	 * @return string Content wrapped in code tags.
	 */
	public function code_wrap( $content ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->code_wrap()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\HTML::code_wrap( $content );
	}

	/**
	 * Mark up content with code tags.
	 * Escapes no HTML.
	 *
	 * @since 2.2.2
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $content Content to be wrapped in code tags.
	 * @return string Content wrapped in code tags.
	 */
	public function code_wrap_noesc( $content ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->code_wrap_noesc()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\HTML::code_wrap_noesc( $content );
	}

	/**
	 * Mark up content in description wrap.
	 * Escapes all HTML, so `<` gets changed to `&lt;` and displays correctly.
	 *
	 * @since 2.7.0
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $content Content to be wrapped in the description wrap.
	 * @param bool   $block Whether to wrap the content in <p> tags.
	 */
	public function description( $content, $block = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->description()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\HTML::description( $content, $block );
	}

	/**
	 * Mark up content in description wrap.
	 *
	 * @since 2.7.0
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $content Content to be wrapped in the description wrap. Expected to be escaped.
	 * @param bool   $block Whether to wrap the content in <p> tags.
	 */
	public function description_noesc( $content, $block = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->description_noesc()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\HTML::description_noesc( $content, $block );
	}

	/**
	 * Mark up content in attention wrap.
	 * Escapes all HTML, so `<` gets changed to `&lt;` and displays correctly.
	 *
	 * @since 3.1.0
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $content Content to be wrapped in the attention wrap.
	 * @param bool   $block Whether to wrap the content in <p> tags.
	 */
	public function attention( $content, $block = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->attention()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\HTML::attention( $content, $block );
	}

	/**
	 * Mark up content in attention wrap.
	 *
	 * @since 3.1.0
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $content Content to be wrapped in the attention wrap. Expected to be escaped.
	 * @param bool   $block Whether to wrap the content in <p> tags.
	 */
	public function attention_noesc( $content, $block = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->attention_noesc()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\HTML::attention_noesc( $content, $block );
	}

	/**
	 * Mark up content in a description+attention wrap.
	 * Escapes all HTML, so `<` gets changed to `&lt;` and displays correctly.
	 *
	 * @since 3.1.0
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $content Content to be wrapped in the wrap. Expected to be escaped.
	 * @param bool   $block Whether to wrap the content in <p> tags.
	 */
	public function attention_description( $content, $block = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->attention_description()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\HTML::attention_description( $content, $block );
	}

	/**
	 * Mark up content in a description+attention wrap.
	 *
	 * @since 3.1.0
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $content Content to be wrapped in the wrap. Expected to be escaped.
	 * @param bool   $block Whether to wrap the content in <p> tags.
	 */
	public function attention_description_noesc( $content, $block = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->attention_description_noesc()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\HTML::attention_description_noesc( $content, $block );
	}

	/**
	 * Echo or return a chechbox fields wrapper.
	 *
	 * This method does NOT escape.
	 *
	 * @since 2.6.0
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $input The input to wrap. Should already be escaped.
	 * @param bool   $echo  Whether to escape echo or just return.
	 * @return string|void Wrapped $input.
	 */
	public function wrap_fields( $input = '', $echo = false ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->wrap_fields()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\HTML::wrap_fields( $input, $echo );
	}

	/**
	 * Return a wrapped question mark.
	 *
	 * @since 2.6.0
	 * @since 3.0.0 Links are now no longer followed, referred or bound to opener.
	 * @since 4.0.0 Now adds a tabindex to the span tag, so you can focus it using keyboard navigation.
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $description The descriptive on-hover title.
	 * @param string $link        The non-escaped link.
	 * @param bool   $echo        Whether to echo or return.
	 * @return string HTML checkbox output if $echo is false.
	 */
	public function make_info( $description = '', $link = '', $echo = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->make_info()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\HTML::make_info( $description, $link, $echo );
	}

	/**
	 * Makes either simple or JSON-encoded data-* attributes for HTML elements.
	 *
	 * @since 4.0.0
	 * @since 4.1.0 No longer adds an extra space in front of the return value when no data is generated.
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 * @internal
	 *
	 * @param array $data : {
	 *    string $k => mixed $v
	 * }
	 * @return string The HTML data attributes, with added space to the start.
	 */
	public function make_data_attributes( array $data ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->make_data_attributes()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\HTML::make_data_attributes( $data );
	}

	/**
	 * Returns a chechbox wrapper.
	 *
	 * @since 2.6.0
	 * @since 2.7.0 Added escape parameter. Defaults to true.
	 * @since 3.0.3 Added $disabled parameter. Defaults to false.
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $field_id    The option ID. Must be within the Autodescription settings.
	 * @param string $label       The checkbox description label.
	 * @param string $description Addition description to place beneath the checkbox.
	 * @param bool   $escape      Whether to escape the label and description.
	 * @param bool   $disabled    Whether to disable the input.
	 * @return string HTML checkbox output.
	 */
	public function make_checkbox( $field_id = '', $label = '', $description = '', $escape = true, $disabled = false ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->make_checkbox()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\Form::make_checkbox( [
			'id'          => $field_id,
			'index'       => '',
			'label'       => $label,
			'description' => $description,
			'escape'      => $escape,
			'disabled'    => $disabled,
		] );
	}

	/**
	 * Returns a HTML select form elements for qubit options: -1, 0, or 1.
	 * Does not support "multiple" field selections.
	 *
	 * @since 4.0.0
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param array $args : {
	 *    string     $id       The select field ID.
	 *    string     $class    The div wrapper class.
	 *    string     $name     The option name.
	 *    int|string $default  The current option value.
	 *    array      $options  The select option values : { value => name }
	 *    string     $label    The option label.
	 *    string     $required Whether the field must be required.
	 *    array      $data     The select field data. Sub-items are expected to be escaped if they're not an array.
	 *    array      $info     Extra info field data.
	 * }
	 * @return string The option field.
	 */
	public function make_single_select_form( array $args ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->make_single_select_form()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\Form::make_single_select_form( $args );
	}

	/**
	 * Returns the HTML class wrap for default Checkbox options.
	 *
	 * This function does nothing special. But is merely a simple wrapper.
	 * Just like code_wrap.
	 *
	 * @since 2.2.5
	 * @since 3.1.0 Deprecated second parameter.
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $key  The option name which returns boolean.
	 * @param string $depr Deprecated
	 * @param bool   $wrap Whether to wrap the class name in `class="%s"`
	 * @param bool   $echo Whether to echo or return the output.
	 * @return string Empty on echo or the class name with an optional wrapper.
	 */
	public function is_default_checked( $key, $depr = '', $wrap = true, $echo = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->is_default_checked()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\Form::is_default_checked( $key, $wrap, $echo );
	}
	/**
	 * Returns the HTML class wrap for warning Checkbox options.
	 *
	 * @since 2.3.4
	 * @since 3.1.0 Deprecated second parameter.
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $key  The option name which returns boolean.
	 * @param string $deprecated Deprecated.
	 * @param bool   $wrap Whether to wrap the class name in `class="%s"`
	 * @param bool   $echo Whether to echo or return the output.
	 * @return string Empty on echo or the class name with an optional wrapper.
	 */
	public function is_warning_checked( $key, $deprecated = '', $wrap = true, $echo = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->is_warning_checked()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\Form::is_warning_checked( $key, $wrap, $echo );
	}
	/**
	 * Returns the HTML class wrap for warning/default Checkbox options.
	 *
	 * @since 2.6.0
	 * @since 3.1.0 Added the $wrap parameter.
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $key  The option name which returns boolean.
	 * @param bool   $wrap Whether to wrap the class name in `class="%s"`
	 */
	public function get_is_conditional_checked( $key, $wrap = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->get_is_conditional_checked()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\Form::get_is_conditional_checked( $key, $wrap );
	}

	/**
	 * Returns the HTML class wrap for warning/default Checkbox options.
	 *
	 * @since 2.3.4
	 * @since 3.1.0 Deprecated second parameter.
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $key        The option name which returns boolean.
	 * @param string $deprecated Deprecated. Used to be the settings field.
	 * @param bool   $wrap       Whether to wrap the class name in `class="%s"`
	 * @param bool   $echo       Whether to echo or return the output.
	 * @return string Empty on echo or the class name with an optional wrapper.
	 */
	public function is_conditional_checked( $key, $deprecated = '', $wrap = true, $echo = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->is_conditional_checked()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\Form::is_conditional_checked( $key, $wrap, $echo );
	}

	/**
	 * Outputs character counter wrap for both JavaScript and no-Javascript.
	 *
	 * @since 3.0.0
	 * @since 3.1.0 : 1. Added an "what if you click" onhover-title.
	 *                2. Removed second parameter's usage. For passing the expected string.
	 *                3. The whole output is now hidden from no-js.
	 * @since 4.1.0 No longer marks up the counter with the `description` HTML class.
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $for     The input ID it's for.
	 * @param string $depr    The initial value for no-JS. Deprecated.
	 * @param bool   $display Whether to display the counter. (options page gimmick)
	 */
	public function output_character_counter_wrap( $for, $depr = '', $display = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->output_character_counter_wrap()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\Form::output_character_counter_wrap( $for, $display );
	}

	/**
	 * Outputs pixel counter wrap for javascript.
	 *
	 * @since 3.0.0
	 * @since 4.1.4 Deprecated silently. Alternative marked for deletion.
	 * @since 4.2.0 Hard deprecation.
	 *
	 * @param string $for  The input ID it's for.
	 * @param string $type Whether it's a 'title' or 'description' counter.
	 * @param bool   $display Whether to display the counter. (options page gimmick)
	 */
	public function output_pixel_counter_wrap( $for, $type, $display = true ) {
		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->output_pixel_counter_wrap()', '4.2.0' );
		return \The_SEO_Framework\Interpreters\Form::output_pixel_counter_wrap( $for, $type, $display );
	}

	/**
	 * Determines if WP is above or below a version
	 *
	 * @since 2.2.1
	 * @since 2.3.8 Added caching
	 * @since 2.8.0 No longer overwrites global $wp_version
	 * @since 3.1.0 1. No longer caches.
	 *              2. Removed redundant parameter checks.
	 *              3. Now supports x.yy.zz WordPress versions.
	 * @since 4.2.0 Deprecated. Use your own method instead.
	 *
	 * @param string $version the three part version to compare to WordPress
	 * @param string $compare the comparing operator, default "$version >= Current WP Version"
	 * @return bool True if the WordPress version comparison passes.
	 */
	public function wp_version( $version = '4.3.0', $compare = '>=' ) {

		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->wp_version()', '4.2.0' );

		$wp_version = $GLOBALS['wp_version'];

		/**
		 * Add a .0 if WP outputs something like 4.3 instead of 4.3.0
		 * Does consider 4.xx, which will become 4.xx.0.
		 * Does not consider 4.xx-dev, which will become 4.xx-dev.0. Oh well.
		 */
		if ( 1 === substr_count( $wp_version, '.' ) )
			$wp_version .= '.0';

		return (bool) version_compare( $wp_version, $version, $compare );
	}

	/**
	 * Checks for current theme support.
	 *
	 * Maintains detection cache, array and strings are mixed through foreach loops.
	 *
	 * @since 2.2.5
	 * @since 3.1.0 Removed caching
	 * @since 4.2.0 Deprecated. Use WP core `current_theme_supports()` instead.
	 *
	 * @param string|array required $features The features to check for.
	 * @return bool theme support.
	 */
	public function detect_theme_support( $features ) {

		\the_seo_framework()->_deprecated_function( 'the_seo_framework()->detect_theme_support()', '4.2.0', 'current_theme_supports' );

		foreach ( (array) $features as $feature ) {
			if ( \current_theme_supports( $feature ) ) {
				return true;
			}
			continue;
		}

		return false;
	}

	/**
	 * Detects presence of a page builder.
	 * Memoizes the return value.
	 *
	 * Detects the following builders:
	 * - Elementor by Elementor LTD
	 * - Divi Builder by Elegant Themes
	 * - Visual Composer by WPBakery
	 * - Page Builder by SiteOrigin
	 * - Beaver Builder by Fastline Media
	 *
	 * @since 4.0.0
	 * @since 4.0.6 The output is now filterable.
	 * @since 4.2.0 Deprecated
	 * @ignore unused.
	 * @deprecated
	 *
	 * @return bool
	 */
	public function detect_page_builder() {

		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->detect_page_builder()', '4.2.0' );

		static $detected = null;

		if ( isset( $detected ) ) return $detected;

		/**
		 * @since 4.0.6
		 * @param bool $detected Whether an active page builder is detected.
		 * @NOTE not to be confused with `the_seo_framework_detect_page_builder`, which tests
		 *       the page builder status for each post individually.
		 */
		return $detected = (bool) \apply_filters(
			'the_seo_framework_page_builder_active',
			$tsf->detect_plugin( [
				'constants' => [
					'ELEMENTOR_VERSION',
					'ET_BUILDER_VERSION',
					'WPB_VC_VERSION',
					'SITEORIGIN_PANELS_VERSION',
					'FL_BUILDER_VERSION',
				],
			] )
		);
	}

	/**
	 * Determines whether the post has a page builder attached to it.
	 * Doesn't use plugin detection features as some builders might be incorporated within themes.
	 *
	 * Detects the following builders:
	 * - Elementor by Elementor LTD
	 * - Divi Builder by Elegant Themes
	 * - Visual Composer by WPBakery
	 * - Page Builder by SiteOrigin
	 * - Beaver Builder by Fastline Media
	 *
	 * @since 2.6.6
	 * @since 3.1.0 Added Elementor detection
	 * @since 4.0.0 Now detects page builders before looping over the meta.
	 * @since 4.2.0 Deprecated.
	 * @TODO -> We may use this data for they have FSE builders. We may want to interface with those, some day.
	 *    -> We'd want to return the TYPE of pagebuilder used, if anything. Just deprecate this.
	 * @ignore unused.
	 * @deprecated
	 *
	 * @param int $post_id The post ID to check.
	 * @return bool
	 */
	public function uses_page_builder( $post_id ) {

		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->uses_page_builder()', '4.2.0' );

		$meta = \get_post_meta( $post_id );

		/**
		 * @since 2.6.6
		 * @since 3.1.0 : 1. Now defaults to `null`
		 *                2. Now, when a boolean (either true or false) is defined, it'll short-circuit this function.
		 * @param boolean|null $detected Whether a builder should be detected.
		 * @param int          $post_id The current Post ID.
		 * @param array        $meta The current post meta.
		 */
		$detected = \apply_filters( 'the_seo_framework_detect_page_builder', null, $post_id, $meta );

		if ( \is_bool( $detected ) )
			return $detected;

		if ( ! $tsf->detect_page_builder() )
			return false;

		if ( empty( $meta ) )
			return false;

		if ( isset( $meta['_elementor_edit_mode'][0] ) && '' !== $meta['_elementor_edit_mode'][0] && \defined( 'ELEMENTOR_VERSION' ) ) :
			// Elementor by Elementor LTD
			return true;
		elseif ( isset( $meta['_et_pb_use_builder'][0] ) && 'on' === $meta['_et_pb_use_builder'][0] && \defined( 'ET_BUILDER_VERSION' ) ) :
			// Divi Builder by Elegant Themes
			return true;
		elseif ( isset( $meta['_wpb_vc_js_status'][0] ) && 'true' === $meta['_wpb_vc_js_status'][0] && \defined( 'WPB_VC_VERSION' ) ) :
			// Visual Composer by WPBakery
			return true;
		elseif ( isset( $meta['panels_data'][0] ) && '' !== $meta['panels_data'][0] && \defined( 'SITEORIGIN_PANELS_VERSION' ) ) :
			// Page Builder by SiteOrigin
			return true;
		elseif ( isset( $meta['_fl_builder_enabled'][0] ) && '1' === $meta['_fl_builder_enabled'][0] && \defined( 'FL_BUILDER_VERSION' ) ) :
			// Beaver Builder by Fastline Media...
			return true;
		endif;

		return false;
	}

	/**
	 * Returns Facebook locales array values.
	 *
	 * @since 2.5.2
	 * @TODO deprecate me.
	 *
	 * @see https://www.facebook.com/translations/FacebookLocales.xml (deprecated)
	 * @see https://wordpress.org/support/topic/oglocale-problem/#post-11456346
	 * mirror: http://web.archive.org/web/20190601043836/https://wordpress.org/support/topic/oglocale-problem/
	 * @see $this->language_keys() for the associative array keys.
	 *
	 * @return array Valid Facebook locales
	 */
	public function fb_locales() {
		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->fb_locales()', '4.2.0', 'the_seo_framework()->supported_social_locales()' );
		return \array_keys( $tsf->supported_social_locales() );
	}

	/**
	 * Returns Facebook locales' associative array keys.
	 *
	 * This is apart from the fb_locales array since there are "duplicated" keys.
	 * Use this to compare the numeric key position.
	 *
	 * @since 2.5.2
	 * @TODO deprecate me.
	 * @see https://www.facebook.com/translations/FacebookLocales.xml (deprecated)
	 * @see https://wordpress.org/support/topic/oglocale-problem/#post-11456346
	 * mirror: http://web.archive.org/web/20190601043836/https://wordpress.org/support/topic/oglocale-problem/
	 *
	 * @return array Valid Facebook locale keys
	 */
	public function language_keys() {
		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->language_keys()', '4.2.0', 'the_seo_framework()->supported_social_locales()' );
		return \array_values( $tsf->supported_social_locales() );
	}

	/**
	 * Returns the PHP timezone compatible string.
	 * UTC offsets are unreliable.
	 *
	 * @since 2.6.0
	 * @since 4.2.0 Deprecated.
	 * @deprecated
	 *
	 * @param bool $guess If true, the timezone will be guessed from the
	 *                    WordPress core gmt_offset option.
	 * @return string PHP Timezone String. May be empty (thus invalid).
	 */
	public function get_timezone_string( $guess = false ) {

		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->get_timezone_string()', '4.2.0' );

		$tzstring = \get_option( 'timezone_string' );

		if ( false !== strpos( $tzstring, 'Etc/GMT' ) )
			$tzstring = '';

		if ( $guess && empty( $tzstring ) ) {
			$tzstring = timezone_name_from_abbr( '', round( \get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ), 1 );
		}

		return $tzstring;
	}

	/**
	 * Sets and resets the timezone.
	 *
	 * NOTE: Always call reset_timezone() ASAP. Don't let changes linger, as they can be destructive.
	 *
	 * This exists because WordPress's current_time() adds discrepancies between UTC and GMT.
	 * This is also far more accurate than WordPress's tiny time table.
	 *
	 * @TODO Note that WordPress 5.3 no longer requires this, and that we should rely on wp_date() instead.
	 *       So, we should remove this dependency ASAP.
	 *
	 * @since 2.6.0
	 * @since 3.0.6 Now uses the old timezone string when a new one can't be generated.
	 * @since 4.0.4 Now also unsets the stored timezone string on reset.
	 * @since 4.2.0 Deprecated.
	 * @link http://php.net/manual/en/timezones.php
	 * @deprecated
	 *
	 * @param string $tzstring Optional. The PHP Timezone string. Best to leave empty to always get a correct one.
	 * @param bool   $reset Whether to reset to default. Ignoring first parameter.
	 * @return bool True on success. False on failure.
	 */
	public function set_timezone( $tzstring = '', $reset = false ) {

		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->set_timezone()', '4.2.0' );

		static $old_tz = null;

		$old_tz = $old_tz ?: date_default_timezone_get() ?: 'UTC';

		if ( $reset ) {
			$_revert_tz = $old_tz;
			$old_tz     = null;
			// phpcs:ignore, WordPress.DateTime.RestrictedFunctions.timezone_change_date_default_timezone_set
			return date_default_timezone_set( $_revert_tz );
		}

		if ( empty( $tzstring ) )
			$tzstring = $tsf->get_timezone_string( true ) ?: $old_tz;

		// phpcs:ignore, WordPress.DateTime.RestrictedFunctions.timezone_change_date_default_timezone_set
		return date_default_timezone_set( $tzstring );
	}

	/**
	 * Resets the timezone to default or UTC.
	 *
	 * @since 2.6.0
	 * @since 4.2.0 Deprecated.
	 * @deprecated
	 *
	 * @return bool True on success. False on failure.
	 */
	public function reset_timezone() {
		$tsf = \the_seo_framework();
		$tsf->_deprecated_function( 'the_seo_framework()->reset_timezone()', '4.2.0' );
		return $tsf->set_timezone( '', true );
	}
}
