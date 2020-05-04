<?php
/**
 * @package Watson
 */
?>
<?php if ( post_password_required() ) : ?>
	<section id="comments">
		<p class="protected-comments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'watson' ); ?></p>
	</section>
	<?php return; ?>
<?php endif; ?>

<?php if ( have_comments() ) : ?>
	<section id="comments">
		<h4 class="comment-headline"><?php printf( _n( 'One Response to <em>&ldquo;%2$s&rdquo;</em>', '%1$s Responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'watson' ), number_format_i18n( get_comments_number() ), get_the_title() ); ?></h4>

		<ol>
			<?php wp_list_comments( array( 'callback' => 'watson_comment' ) ); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav id="comments-nav">
				<div class="comments-previous"><?php previous_comments_link( __( '&larr; Older comments', 'watson' ) ); ?></div>
				<div class="comments-next"><?php next_comments_link( __( 'Newer comments &rarr;', 'watson' ) ); ?></div>
			</nav>
		<?php endif; ?>

		<?php if ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
			<p class="comments-closed"><em><?php _e( 'Comments are closed.', 'watson' ); ?></em></p>
		<?php endif; ?>
	</section>
<?php endif; ?>

<?php
$req = get_option( 'require_name_email' );
$field = '<p><label for="%1$s" class="comment-field">%2$s</label><input class="text-input" type="text" name="%1$s" id="%1$s" value="%3$s" size="36" tabindex="%4$d" /></p>';
comment_form( array(
	'comment_field' => '<fieldset><label for="comment" class="comment-field">' . _x( 'Comment', 'noun', 'watson' ) . '</label><textarea id="comment" class="blog-textarea" name="comment" rows="10" aria-required="true" tabindex="4"></textarea></fieldset>',
	'comment_notes_before' => '',
	'comment_notes_after' => sprintf(
		'<p class="guidelines">%3$s</p>' . "\n" . '<p class="comments-rss"><a href="%1$s">%2$s</a></p>',
		esc_attr( get_post_comments_feed_link() ),
		__( 'Subscribe to this comment feed via <abbr title="Really Simple Syndication">RSS<abbr>', 'watson' ),
		__( 'Basic <abbr title="Hypertext Markup Language">HTML</abbr> is allowed. Your email address will not be published.', 'watson' )
	),
	'fields' => array(
		'author' => sprintf(
			$field,
			'author',
			(
				$req ?
				__( 'Name: <span class="required">&#42;</span>', 'watson' ) :
				__( 'Name:', 'watson' )
			),
			esc_attr( $comment_author ),
			1
		),
		'email' => sprintf(
			$field,
			'email',
			(
				$req ?
				__( 'Email: <span class="required">&#42;</span>', 'watson' ) :
				__( 'Email:', 'watson' )
			),
			esc_attr( $comment_author_email ),
			2
		),
		'url' => sprintf(
			$field,
			'url',
			__( 'Website:', 'watson' ),
			esc_attr( $comment_author_url ),
			3
		),
	)
) );