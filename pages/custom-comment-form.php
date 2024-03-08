<?php
// Ensure the comment form is loaded only on single post pages
if (is_single() && comments_open()) :
?>

<section id="comments" class="comments-area">
    <h2 class="comments-title">むらけん選手の個人情報fwefwefewf</h2>

    <form id="comment-form" class="comment-form" action="<?php echo esc_url(site_url('/wp-comments-post.php')); ?>" method="post">
        <div class="comment-form-row">
            <label for="comment" class="screen-reader-text">Comment</label>
            <textarea name="comment" id="comment" cols="45" rows="8" required></textarea>
        </div>

        <?php comment_id_fields(); ?>
        <?php do_action('comment_form', get_the_ID()); ?>
    </form>
</section>

<?php
endif; // End check for single post and open comments
?>