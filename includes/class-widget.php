<?php
/**
 * Credentials Author Credentials
 *
 * @since 1.0.0
 * @package Credentials
 */

/**
 * Credentials Author Credentials class.
 *
 * @since 1.0.0
 */
class C_Author_Credentials_Widget extends WP_Widget {

	/**
	 * Unique identifier for this widget.
	 *
	 * Will also serve as the widget class.
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $widget_slug = 'author-credentials';


	/**
	 * Widget name displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $widget_name = '';


	/**
	 * Default widget title displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $default_widget_title = '';


	/**
	 * Shortcode name for this widget
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected static $shortcode = 'author-credentials';


	/**
	 * Construct widget class.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {

		$this->widget_name          = esc_html__( 'Author Credentials', 'credentials' );
		$this->default_widget_title = esc_html__( 'Author Credentials', 'credentials' );

		parent::__construct(
			$this->widget_slug,
			$this->widget_name,
			array(
				'classname'   => $this->widget_slug,
				'description' => esc_html__( 'A widget boilerplate description.', 'credentials' ),
			)
		);

		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
		add_shortcode( self::$shortcode, array( __CLASS__, 'get_widget' ) );
	}


	/**
	 * Delete this widget's cache.
	 *
	 * Note: Could also delete any transients
	 * delete_transient( 'some-transient-generated-by-this-widget' );
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function flush_widget_cache() {
		wp_cache_delete( $this->widget_slug, 'widget' );
	}


	/**
	 * Front-end display of widget.
	 *
	 * @since  1.0.0
	 * @param  array $args     The widget arguments set up when a sidebar is registered.
	 * @param  array $instance The widget settings as set by user.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		echo self::get_widget( array(
			'before_widget' => $args['before_widget'],
			'after_widget'  => $args['after_widget'],
			'before_title'  => $args['before_title'],
			'after_title'   => $args['after_title'],
			'title'         => $instance['title'],
			'text'          => $instance['text'],
		) );
	}


	/**
	 * Return the widget/shortcode output
	 *
	 * @since  1.0.0
	 * @param  array $atts Array of widget/shortcode attributes/args.
	 * @return string       Widget output
	 */
	public static function get_widget( $atts ) {
		$widget = '';

		// Set up default values for attributes.
		$atts = shortcode_atts(
			array(
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '',
				'after_title'   => '',
				'title'         => '',
				'text'          => '',
			),
			(array) $atts,
			self::$shortcode
		);

		// Before widget hook.
		$widget .= $atts['before_widget'];

		// Title.
		$widget .= ( $atts['title'] ) ? $atts['before_title'] . esc_html( $atts['title'] ) . $atts['after_title'] : '';

		if ( is_author() ) {
			// Author Archive.
			global $post;

			$args = array( "hide_empty" => 0 );
			foreach ( get_categories( $args ) as $category ) {
				$meta = get_user_meta( $post->post_author, 'term_' . $category->term_id . '_bio', true );
				if ( $meta ) {
					$bios[$category->term_id] = array(
						'term_id' => $category->term_id,
						'term_name' => $category->name,
						'bio' => $meta
					);
				}
			}

			if ( empty( count( $bios ) ) ) {
				return;
			}

			$author = get_userdata( $post->post_author );
			$widget .= '<div itemscope itemtype="http://schema.org/Person">';
			$widget .= '<div itemprop="name">' . $author->first_name . ' ' . $author->last_name . ' has experience in the following areas:</div>';
				$widget .= '<ul>';
				foreach ( $bios as $bio ) {
					$widget .= '<li><strong>' . $bio['term_name'] . '</strong><br /><div itemprop="description">' . $bio['bio'] . '</div></li>';
				}
				$widget .= '</ul>';

		} elseif ( is_category() ) {
			// Category Archive.

		} elseif ( is_single() ) {
			// Single Post.
			global $post;

			$post_categories =  wp_get_post_categories( $post->ID );

			foreach ( $post_categories as $category ) {
				$meta = get_user_meta( $post->post_author, 'term_' . $category . '_bio', true );
				if ( $meta ) {
					$bios[] = $meta;
				}
			}

			if ( empty( count( $bios ) ) ) {
				return;
			}

			$author = get_userdata( $post->post_author );
			$widget .= '<div itemscope itemtype="http://schema.org/Person">';
			$widget .= '<div itemprop="name"><strong>' . $author->first_name . ' ' . $author->last_name . '</strong></div>';
			foreach ( $bios as $bio ) {
				$widget .= '<div itemprop="description">' . $bio . '</div>';
			}
		}

		$widget .= '</div>';

		// After widget hook.
		$widget .= $atts['after_widget'];

		return $widget;
	}


	/**
	 * Update form values as they are saved.
	 *
	 * @since  1.0.0
	 * @param  array $new_instance New settings for this instance as input by the user.
	 * @param  array $old_instance Old settings for this instance.
	 * @return array               Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {

		// Previously saved values.
		$instance = $old_instance;

		// Sanitize title before saving to database.
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		// Sanitize text before saving to database.
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = force_balance_tags( $new_instance['text'] );
		} else {
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['text'] ) ) );
		}

		// Flush cache.
		$this->flush_widget_cache();

		return $instance;
	}


	/**
	 * Back-end widget form with defaults.
	 *
	 * @since  1.0.0
	 * @param  array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance,
			array(
				'title' => $this->default_widget_title,
			)
		);

		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'credentials' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" placeholder="optional" /></p>
		<?php
	}
}


/**
 * Register this widget with WordPress. Can also move this function to the parent plugin.
 *
 * @since  1.0.0
 * @return void
 */
function credentials_register_undefined() {
	register_widget( 'C_Author_Credentials_Widget' );
}
add_action( 'widgets_init', 'credentials_register_undefined' );
