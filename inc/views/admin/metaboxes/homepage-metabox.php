<?php
/**
 * @package The_SEO_Framework\Views\Admin\Metaboxes
 * @subpackage The_SEO_Framework\Admin\Settings
 */

// phpcs:disable, VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable -- includes.
// phpcs:disable, WordPress.WP.GlobalVariablesOverride -- This isn't the global scope.

use The_SEO_Framework\Bridges\SeoSettings,
	The_SEO_Framework\Interpreters\HTML,
	The_SEO_Framework\Interpreters\Form;

defined( 'THE_SEO_FRAMEWORK_PRESENT' ) and the_seo_framework()->_verify_include_secret( $_secret ) or die;

// Fetch the required instance within this file.
$instance = $this->get_view_instance( 'the_seo_framework_homepage_metabox', $instance );

$home_id = $this->get_the_front_page_ID();

$_generator_args = [
	'id'       => $home_id,
	'taxonomy' => '',
];

switch ( $instance ) :
	case 'the_seo_framework_homepage_metabox_main':
		HTML::description( __( 'These settings will take precedence over the settings set within the homepage edit screen, if any.', 'autodescription' ) );
		?>
		<hr>
		<?php

		$default_tabs = [
			'general'   => [
				'name'     => __( 'General', 'autodescription' ),
				'callback' => SeoSettings::class . '::_homepage_metabox_general_tab',
				'dashicon' => 'admin-generic',
			],
			'additions' => [
				'name'     => __( 'Additions', 'autodescription' ),
				'callback' => SeoSettings::class . '::_homepage_metabox_additions_tab',
				'dashicon' => 'plus',
			],
			'social'    => [
				'name'     => __( 'Social', 'autodescription' ),
				'callback' => SeoSettings::class . '::_homepage_metabox_social_tab',
				'dashicon' => 'share',
			],
			'robots'    => [
				'name'     => __( 'Robots', 'autodescription' ),
				'callback' => SeoSettings::class . '::_homepage_metabox_robots_tab',
				'dashicon' => 'visibility',
			],
		];

		/**
		 * @since 2.6.0
		 * @param array $defaults The default tabs.
		 * @param array $args     The args added on the callback.
		 */
		$defaults = (array) apply_filters( 'the_seo_framework_homepage_settings_tabs', $default_tabs, $args );

		$tabs = wp_parse_args( $args, $defaults );

		SeoSettings::_nav_tab_wrapper( 'homepage', $tabs );
		break;

	case 'the_seo_framework_homepage_metabox_general':
		?>
		<p>
			<label for="<?php Form::field_id( 'homepage_title' ); ?>" class="tsf-toblock">
				<strong><?php esc_html_e( 'Meta Title', 'autodescription' ); ?></strong>
				<?php
					echo ' ';
					HTML::make_info(
						__( 'The meta title can be used to determine the title used on search engine result pages.', 'autodescription' ),
						'https://developers.google.com/search/docs/advanced/appearance/good-titles-snippets#page-titles'
					);
				?>
			</label>
		</p>
		<?php
		// Output these unconditionally, with inline CSS attached to allow reacting on settings.
		Form::output_character_counter_wrap( Form::get_field_id( 'homepage_title' ), (bool) $this->get_option( 'display_character_counter' ) );
		Form::output_pixel_counter_wrap( Form::get_field_id( 'homepage_title' ), 'title', (bool) $this->get_option( 'display_pixel_counter' ) );
		?>
		<p class=tsf-title-wrap>
			<input type="text" name="<?php Form::field_name( 'homepage_title' ); ?>" class="large-text" id="<?php Form::field_id( 'homepage_title' ); ?>" value="<?php echo $this->esc_attr_preserve_amp( $this->get_option( 'homepage_title' ) ); ?>" autocomplete=off />
			<?php
			$this->output_js_title_elements(); // legacy
			$this->output_js_title_data(
				Form::get_field_id( 'homepage_title' ),
				[
					'state' => [
						'refTitleLocked'    => false,
						'defaultTitle'      =>
							( $home_id ? $this->get_post_meta_item( '_genesis_title', $home_id ) : '' )
							?: $this->get_filtered_raw_generated_title( $_generator_args ),
						'addAdditions'      => $this->use_title_branding( $_generator_args ),
						'useSocialTagline'  => $this->use_title_branding( $_generator_args, true ),
						'additionValue'     => $this->get_home_title_additions(),
						'additionPlacement' => 'left' === $this->get_home_title_seplocation() ? 'before' : 'after',
						'hasLegacy'         => true,
					],
				]
			);
			?>
		</p>
		<?php
		HTML::description( __( 'Note: The input value of this field may be used to describe the name of the site elsewhere.', 'autodescription' ) );

		if ( $home_id && $this->get_post_meta_item( '_genesis_title', $home_id ) ) {
			HTML::description( __( 'Note: The title placeholder is fetched from the Page SEO Settings on the homepage.', 'autodescription' ) );
		}

		/**
		 * @since 2.8.0
		 * @param bool $warn Whether to warn that there's a plugin active with multiple homepages.
		 */
		if ( $home_id && apply_filters( 'the_seo_framework_warn_homepage_global_title', false ) ) {
			HTML::attention_noesc(
				// Markdown escapes.
				$this->convert_markdown(
					sprintf(
						/* translators: %s = Homepage URL markdown */
						esc_html__( 'A plugin has been detected that suggests to maintain this option on the [homepage](%s).', 'autodescription' ),
						esc_url( admin_url( 'post.php?post=' . $home_id . '&action=edit#tsf-inpost-box' ) )
					),
					[ 'a' ],
					[ 'a_internal' => false ] // opens in new tab.
				)
			);
		}
		?>
		<hr>

		<p>
			<label for="<?php Form::field_id( 'homepage_description' ); ?>" class="tsf-toblock">
				<strong><?php esc_html_e( 'Meta Description', 'autodescription' ); ?></strong>
				<?php
					echo ' ';
					HTML::make_info(
						__( 'The meta description can be used to determine the text used under the title on search engine results pages.', 'autodescription' ),
						'https://developers.google.com/search/docs/advanced/appearance/good-titles-snippets#meta-descriptions'
					);
				?>
			</label>
		</p>
		<?php
		// Output these unconditionally, with inline CSS attached to allow reacting on settings.
		Form::output_character_counter_wrap( Form::get_field_id( 'homepage_description' ), (bool) $this->get_option( 'display_character_counter' ) );
		Form::output_pixel_counter_wrap( Form::get_field_id( 'homepage_description' ), 'description', (bool) $this->get_option( 'display_pixel_counter' ) );
		?>
		<p>
			<textarea name="<?php Form::field_name( 'homepage_description' ); ?>" class="large-text" id="<?php Form::field_id( 'homepage_description' ); ?>" rows="3" cols="70"><?php echo esc_attr( $this->get_option( 'homepage_description' ) ); ?></textarea>
			<?php
			$this->output_js_description_elements(); // legacy
			$this->output_js_description_data(
				Form::get_field_id( 'homepage_description' ),
				[
					'state' => [
						'defaultDescription' =>
							( $home_id ? $this->get_post_meta_item( '_genesis_description', $home_id ) : '' )
							?: $this->get_generated_description( $_generator_args ),
						'hasLegacy'          => true,
					],
				]
			);
			?>
		</p>
		<?php

		if ( $home_id && $this->get_post_meta_item( '_genesis_description', $home_id ) ) {
			HTML::description(
				__( 'Note: The description placeholder is fetched from the Page SEO Settings on the homepage.', 'autodescription' )
			);
		}

		/**
		 * @since 2.8.0
		 * @param bool $warn Whether to warn that there's a plugin active with multiple homepages.
		 */
		if ( $home_id && apply_filters( 'the_seo_framework_warn_homepage_global_description', false ) ) {
			HTML::attention_noesc(
				// Markdown escapes.
				$this->convert_markdown(
					sprintf(
						/* translators: %s = Homepage URL markdown */
						esc_html__( 'A plugin has been detected that suggests to maintain this option on the [homepage](%s).', 'autodescription' ),
						esc_url( admin_url( 'post.php?post=' . $home_id . '&action=edit#tsf-inpost-box' ) )
					),
					[ 'a' ],
					[ 'a_internal' => false ] // opens in new tab.
				)
			);
		}
		break;

	case 'the_seo_framework_homepage_metabox_additions':
		$tagline_placeholder = $this->s_title_raw( $this->get_blogdescription() );

		// Fetches escaped title parts.
		$_example_title = $this->escape_title(
			$this->get_filtered_raw_custom_field_title( $_generator_args ) ?: $this->get_filtered_raw_generated_title( $_generator_args )
		);
		// On JS: The 'Untitled' title will disappear, this is intentional. On no-JS one will see 'Untitled'.
		// TODO: Deprecate no-JS support? WordPress doesn't function without JS since 5.0 anyway...
		$_example_blogname  = $this->escape_title( $this->get_home_title_additions() ?: $this->get_static_untitled_title() );
		$_example_separator = esc_html( $this->get_separator( 'title' ) );

		// TODO very readable.
		$example_left  = '<em><span class="tsf-custom-blogname-js"><span class="tsf-custom-tagline-js">' . $_example_blogname . '</span><span class="tsf-sep-js"> ' . $_example_separator . ' </span></span><span class="tsf-custom-title-js">' . $_example_title . '</span></em>';
		$example_right = '<em><span class="tsf-custom-title-js">' . $_example_title . '</span><span class="tsf-custom-blogname-js"><span class="tsf-sep-js"> ' . $_example_separator . ' </span><span class="tsf-custom-tagline-js">' . $_example_blogname . '</span></span></em>';

		?>

		<p>
			<label for="<?php Form::field_id( 'homepage_title_tagline' ); ?>" class="tsf-toblock">
				<strong><?php esc_html_e( 'Meta Title Additions', 'autodescription' ); ?></strong>
			</label>
		</p>
		<p>
			<input type="text" name="<?php Form::field_name( 'homepage_title_tagline' ); ?>" class="large-text" id="<?php Form::field_id( 'homepage_title_tagline' ); ?>" placeholder="<?php echo esc_attr( $tagline_placeholder ); ?>" value="<?php echo $this->esc_attr_preserve_amp( $this->get_option( 'homepage_title_tagline' ) ); ?>" autocomplete=off />
		</p>

		<div id="tsf-title-tagline-toggle">
		<?php
			HTML::wrap_fields(
				Form::make_checkbox( [
					'id'    => 'homepage_tagline',
					'label' => __( 'Add Meta Title Additions to the homepage title?', 'autodescription' ),
				] ),
				true
			);
		?>
		</div>

		<hr>

		<fieldset>
			<legend><?php Form::header_title( __( 'Meta Title Additions Location', 'autodescription' ) ); ?></legend>

			<p id="tsf-home-title-location" class="tsf-fields">
				<span class="tsf-toblock">
					<input type="radio" name="<?php Form::field_name( 'home_title_location' ); ?>" id="<?php Form::field_id( 'home_title_location_left' ); ?>" value="left" <?php checked( $this->get_option( 'home_title_location' ), 'left' ); ?> />
					<label for="<?php Form::field_id( 'home_title_location_left' ); ?>">
						<span><?php esc_html_e( 'Left:', 'autodescription' ); ?></span>
						<?php
						// phpcs:ignore, WordPress.Security.EscapeOutput -- $example_left is already escaped.
						echo HTML::code_wrap_noesc( $example_left );
						?>
					</label>
				</span>
				<span class="tsf-toblock">
					<input type="radio" name="<?php Form::field_name( 'home_title_location' ); ?>" id="<?php Form::field_id( 'home_title_location_right' ); ?>" value="right" <?php checked( $this->get_option( 'home_title_location' ), 'right' ); ?> />
					<label for="<?php Form::field_id( 'home_title_location_right' ); ?>">
						<span><?php esc_html_e( 'Right:', 'autodescription' ); ?></span>
						<?php
						// phpcs:ignore, WordPress.Security.EscapeOutput -- $example_right is already escaped.
						echo HTML::code_wrap_noesc( $example_right );
						?>
					</label>
				</span>
			</p>
		</fieldset>
		<?php
		break;

	case 'the_seo_framework_homepage_metabox_social':
		// Gets custom fields from page.
		$custom_og_title = $home_id ? $this->get_post_meta_item( '_open_graph_title', $home_id ) : '';
		$custom_og_desc  = $home_id ? $this->get_post_meta_item( '_open_graph_description', $home_id ) : '';
		$custom_tw_title = $home_id ? $this->get_post_meta_item( '_twitter_title', $home_id ) : '';
		$custom_tw_desc  = $home_id ? $this->get_post_meta_item( '_twitter_description', $home_id ) : '';

		$social_placeholders = $this->_get_social_placeholders( $_generator_args, 'settings' );

		?>
		<p>
			<label for="<?php Form::field_id( 'homepage_og_title' ); ?>" class="tsf-toblock">
				<strong>
					<?php
					esc_html_e( 'Open Graph Title', 'autodescription' );
					?>
				</strong>
			</label>
		</p>
		<?php
		// Output this unconditionally, with inline CSS attached to allow reacting on settings.
		Form::output_character_counter_wrap( Form::get_field_id( 'homepage_og_title' ), (bool) $this->get_option( 'display_character_counter' ) );
		?>
		<p>
			<input type="text" name="<?php Form::field_name( 'homepage_og_title' ); ?>" class="large-text" id="<?php Form::field_id( 'homepage_og_title' ); ?>" placeholder="<?php echo esc_attr( $social_placeholders['title']['og'] ); ?>" value="<?php echo $this->esc_attr_preserve_amp( $this->get_option( 'homepage_og_title' ) ); ?>" autocomplete=off />
		</p>
		<?php
		if ( $this->has_page_on_front() && $custom_og_title ) {
			HTML::description(
				__( 'Note: The title placeholder is fetched from the Page SEO Settings on the homepage.', 'autodescription' )
			);
		}
		?>

		<p>
			<label for="<?php Form::field_id( 'homepage_og_description' ); ?>" class="tsf-toblock">
				<strong>
					<?php
					esc_html_e( 'Open Graph Description', 'autodescription' );
					?>
				</strong>
			</label>
		</p>
		<?php
		// Output this unconditionally, with inline CSS attached to allow reacting on settings.
		Form::output_character_counter_wrap( Form::get_field_id( 'homepage_og_description' ), (bool) $this->get_option( 'display_character_counter' ) );
		?>
		<p>
			<textarea name="<?php Form::field_name( 'homepage_og_description' ); ?>" class="large-text" id="<?php Form::field_id( 'homepage_og_description' ); ?>" rows="3" cols="70" placeholder="<?php echo esc_attr( $social_placeholders['description']['og'] ); ?>" autocomplete=off><?php echo esc_attr( $this->get_option( 'homepage_og_description' ) ); ?></textarea>
		</p>
		<?php
		if ( $this->has_page_on_front() && $custom_og_desc ) {
			HTML::description(
				__( 'Note: The description placeholder is fetched from the Page SEO Settings on the homepage.', 'autodescription' )
			);
		}
		?>
		<hr>

		<p>
			<label for="<?php Form::field_id( 'homepage_twitter_title' ); ?>" class="tsf-toblock">
				<strong>
					<?php
					esc_html_e( 'Twitter Title', 'autodescription' );
					?>
				</strong>
			</label>
		</p>
		<?php
		// Output this unconditionally, with inline CSS attached to allow reacting on settings.
		Form::output_character_counter_wrap( Form::get_field_id( 'homepage_twitter_title' ), (bool) $this->get_option( 'display_character_counter' ) );
		?>
		<p>
			<input type="text" name="<?php Form::field_name( 'homepage_twitter_title' ); ?>" class="large-text" id="<?php Form::field_id( 'homepage_twitter_title' ); ?>" placeholder="<?php echo esc_attr( $social_placeholders['title']['twitter'] ); ?>" value="<?php echo $this->esc_attr_preserve_amp( $this->get_option( 'homepage_twitter_title' ) ); ?>" autocomplete=off />
		</p>
		<?php
		if ( $this->has_page_on_front() && ( $custom_og_title || $custom_tw_title ) ) {
			HTML::description(
				__( 'Note: The title placeholder is fetched from the Page SEO Settings on the homepage.', 'autodescription' )
			);
		}
		?>

		<p>
			<label for="<?php Form::field_id( 'homepage_twitter_description' ); ?>" class="tsf-toblock">
				<strong>
					<?php
					esc_html_e( 'Twitter Description', 'autodescription' );
					?>
				</strong>
			</label>
		</p>
		<?php
		// Output this unconditionally, with inline CSS attached to allow reacting on settings.
		Form::output_character_counter_wrap( Form::get_field_id( 'homepage_twitter_description' ), (bool) $this->get_option( 'display_character_counter' ) );
		?>
		<p>
			<textarea name="<?php Form::field_name( 'homepage_twitter_description' ); ?>" class="large-text" id="<?php Form::field_id( 'homepage_twitter_description' ); ?>" rows="3" cols="70" placeholder="<?php echo esc_attr( $social_placeholders['description']['twitter'] ); ?>" autocomplete=off><?php echo esc_attr( $this->get_option( 'homepage_twitter_description' ) ); ?></textarea>
		</p>
		<?php
		if ( $this->has_page_on_front() && ( $custom_og_desc || $custom_tw_desc ) ) {
			HTML::description(
				__( 'Note: The description placeholder is fetched from the Page SEO Settings on the homepage.', 'autodescription' )
			);
		}
		?>
		<hr>
		<?php
		Form::header_title( __( 'Social Image Settings', 'autodescription' ) );
		HTML::description( __( 'A social image can be displayed when your homepage is shared. It is a great way to grab attention.', 'autodescription' ) );
		?>
		<p>
			<label for="tsf_homepage_socialimage-url">
				<strong><?php esc_html_e( 'Social Image URL', 'autodescription' ); ?></strong>
				<?php
				HTML::make_info(
					__( "The social image URL can be used by search engines and social networks alike. It's best to use an image with a 1.91:1 aspect ratio that is at least 1200px wide for universal support.", 'autodescription' ),
					'https://developers.facebook.com/docs/sharing/best-practices#images'
				);
				?>
			</label>
		</p>
		<p>
			<input class="large-text" type="url" name="<?php Form::field_name( 'homepage_social_image_url' ); ?>" id="tsf_homepage_socialimage-url" placeholder="<?php echo esc_url( current( $this->get_generated_image_details( $_generator_args, true, 'social', true ) )['url'] ?? '' ); ?>" value="<?php echo esc_url( $this->get_option( 'homepage_social_image_url' ) ); ?>" />
			<input type="hidden" name="<?php Form::field_name( 'homepage_social_image_id' ); ?>" id="tsf_homepage_socialimage-id" value="<?php echo absint( $this->get_option( 'homepage_social_image_id' ) ); ?>" disabled class="tsf-enable-media-if-js" />
		</p>
		<p class="hide-if-no-tsf-js">
			<?php
			// phpcs:ignore, WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped.
			echo Form::get_image_uploader_form( [ 'id' => 'tsf_homepage_socialimage' ] );
			?>
		</p>
		<?php
		break;

	case 'the_seo_framework_homepage_metabox_robots':
		$noindex_post   = $home_id ? $this->get_post_meta_item( '_genesis_noindex', $home_id ) : '';
		$nofollow_post  = $home_id ? $this->get_post_meta_item( '_genesis_nofollow', $home_id ) : '';
		$noarchive_post = $home_id ? $this->get_post_meta_item( '_genesis_noarchive', $home_id ) : '';

		$checked_home = '';
		/**
		 * Shows user that the setting is checked on the homepage.
		 * Adds starting - with space to maintain readability.
		 *
		 * @since 2.2.4
		 */
		if ( $noindex_post || $nofollow_post || $noarchive_post ) {
			$checked_home = sprintf(
				'- %s',
				vsprintf(
					'<a href="%s" title="%s" target=_blank class=attention>%s</a>',
					[
						esc_url( admin_url( 'post.php?post=' . $home_id . '&action=edit#tsf-inpost-box' ) ),
						esc_attr_x( 'Edit homepage page settings', 'Bear with me: the homepage can be edited globally, or via its page. Thus "homepage page".', 'autodescription' ),
						esc_html__( 'Overwritten by page settings', 'autodescription' ),
					]
				)
			);
		}

		Form::header_title( __( 'Robots Meta Settings', 'autodescription' ) );

		$i_label = sprintf(
			/* translators: 1: Option label, 2: [?] option info note, 3: Optional warning */
			esc_html_x( '%1$s %2$s %3$s', 'robots setting', 'autodescription' ),
			$this->convert_markdown(
				/* translators: the backticks are Markdown! Preserve them as-is! */
				esc_html__( 'Apply `noindex` to the homepage?', 'autodescription' ),
				[ 'code' ]
			),
			HTML::make_info(
				__( 'This tells search engines not to show this page in their search results.', 'autodescription' ),
				'https://developers.google.com/search/docs/advanced/crawling/block-indexing',
				false
			),
			$noindex_post ? $checked_home : ''
		);

		$f_label = sprintf(
			/* translators: 1: Option label, 2: [?] option info note, 3: Optional warning */
			esc_html_x( '%1$s %2$s %3$s', 'robots setting', 'autodescription' ),
			$this->convert_markdown(
				/* translators: the backticks are Markdown! Preserve them as-is! */
				esc_html__( 'Apply `nofollow` to the homepage?', 'autodescription' ),
				[ 'code' ]
			),
			HTML::make_info(
				__( 'This tells search engines not to follow links on this page.', 'autodescription' ),
				'https://developers.google.com/search/docs/advanced/guidelines/qualify-outbound-links',
				false
			),
			$nofollow_post ? $checked_home : ''
		);

		$a_label = sprintf(
			/* translators: 1: Option label, 2: [?] option info note, 3: Optional warning */
			esc_html_x( '%1$s %2$s %3$s', 'robots setting', 'autodescription' ),
			$this->convert_markdown(
				/* translators: the backticks are Markdown! Preserve them as-is! */
				esc_html__( 'Apply `noarchive` to the homepage?', 'autodescription' ),
				[ 'code' ]
			),
			HTML::make_info(
				__( 'This tells search engines not to save a cached copy of this page.', 'autodescription' ),
				'https://developers.google.com/search/docs/advanced/robots/robots_meta_tag#directives',
				false
			),
			$noarchive_post ? $checked_home : ''
		);

		HTML::attention_description( __( 'Warning: No public site should ever apply "noindex" or "nofollow" to the homepage.', 'autodescription' ) );

		HTML::wrap_fields(
			[
				Form::make_checkbox( [
					'id'     => 'homepage_noindex',
					'label'  => $i_label,
					'escape' => false,
				] ),
				Form::make_checkbox( [
					'id'     => 'homepage_nofollow',
					'label'  => $f_label,
					'escape' => false,
				] ),
				Form::make_checkbox( [
					'id'     => 'homepage_noarchive',
					'label'  => $a_label,
					'escape' => false,
				] ),
			],
			true
		);

		if ( $this->has_page_on_front() ) {
			HTML::description_noesc(
				$this->convert_markdown(
					sprintf(
						/* translators: %s = Homepage URL markdown */
						esc_html__( 'Note: These options may be overwritten by the [page settings](%s).', 'autodescription' ),
						esc_url( admin_url( 'post.php?post=' . $home_id . '&action=edit#tsf-inpost-box' ) )
					),
					[ 'a' ],
					[ 'a_internal' => false ]
				)
			);
		}
		?>

		<hr>
		<?php
		Form::header_title( __( 'Homepage Pagination Robots Settings', 'autodescription' ) );
		HTML::description( __( "If your homepage is paginated and outputs content that's also found elsewhere on the website, enabling this option may prevent duplicate content.", 'autodescription' ) );

		// Echo checkbox.
		HTML::wrap_fields(
			Form::make_checkbox( [
				'id'     => 'home_paged_noindex',
				'label'  => $this->convert_markdown(
					/* translators: the backticks are Markdown! Preserve them as-is! */
					esc_html__( 'Apply `noindex` to every second or later page on the homepage?', 'autodescription' ),
					[ 'code' ]
				),
				'escape' => false,
			] ),
			true
		);
		break;

	default:
		break;
endswitch;
