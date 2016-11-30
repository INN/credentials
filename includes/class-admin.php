<?php
/**
 * Subject Expertise Bios Admin
 *
 * @since NEXT
 * @package Subject Expertise Bios
 */

/**
 * Subject Expertise Bios Admin.
 *
 * @since NEXT
 */
class SEB_Admin {
	/**
	 * Parent plugin class
	 *
	 * @var   Subject_Expertise_Bios
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  Subject_Expertise_Bios $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Display Subject Expertise
	 *
	 * @since    1.0.0
	 */
	public function profile_expertise_display( $user ) {
		global $wpdb;
		$taxonomy_bios = $wpdb->get_results( "SELECT * FROM $wpdb->usermeta WHERE meta_key LIKE 'term_%_bio'" );

		?>
		<h3>Subject Expertise</h3>
		<table class="wp-list-table widefat fixed striped users">
			<thead>
				<tr>
					<th scope="col" id="category" class="manage-column column-category">Category</th>
					<th scope="col" id="biography" class="manage-column column-biography">Biography</th>
				</tr>
			</thead>

			<tbody id="the-list" data-wp-lists="list:user">

				<?php
				$count = 0;
				foreach ( $taxonomy_bios as $bio ) {
					$category_id = filter_var( $bio->meta_key, FILTER_SANITIZE_NUMBER_INT );
					$category = get_category( $category_id );

					if ( 0 == $count % 2 ) {
						$row_class = 'even';
					}
					else {
						$row_class = 'odd';
					}

					echo '<tr class="' . $row_class .'">';
						echo '<td class="category column-category" data-colname="Category" name="' . $category_id . '">';
							echo '<strong><a href="/wp-admin/term.php?taxonomy=category&tag_ID=' . $category_id . '&post_type=post">' . $category->name . '</a></strong>';
							echo '<div class="row-actions">';
							echo '<span class="trash"><a href="' . @TODO . '" class="submitdelete" aria-label="Delete This Data">Remove</a></span>';
							echo '</div>';
						echo '</td>';
						echo '<td class="biography column-biography" data-colname="Biography">' . $bio->meta_value . '</td>';
					echo '</tr>';
				} ?>
				<tr class="odd">
					<td>
						<select name="category" id="category">
							<?php
							foreach ( get_categories() as $category ) {
								echo '<option value=' . $category->term_id . '>' . $category->name . '</option>';
							}
							?>
						</select>
					</td>
					<td>
						<textarea name="topic_bio" id="topic_bio" rows="5" cols="30"></textarea>
						<p class="description">Please enter background information and credentials regarding the author's expertise on this subject.</p>
					</td>
				</tr>
			</tbody>

			<tfoot>
				<tr>
					<th scope="col" id="category" class="manage-column column-category">Category</th>
					<th scope="col" id="biography" class="manage-column column-biography">Biography</th>
				</tr>
			</tfoot>
		</table>

	<?php }

	/**
	 * Save Subject Expertise
	 *
	 * @since    1.0.0
	 */
	public function profile_expertise_save( $user_id ) {

		if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

		update_usermeta( absint( $user_id ), 'term_' . $_POST['category'] . '_bio', wp_kses_post( $_POST['topic_bio'] ) );
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {
		add_action( 'show_user_profile', array( $this, 'profile_expertise_display' ) );
		add_action( 'edit_user_profile', array( $this, 'profile_expertise_display' ) );
		add_action( 'personal_options_update', array( $this, 'profile_expertise_save' ) );
		add_action( 'edit_user_profile_update', array( $this, 'profile_expertise_save' ) );
	}
}
