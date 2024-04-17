<?php
/*
Template Name: Comments
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<?php
$comments_per_page = 10; // Number of comments per page
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // Get the current page number
$args = array(
    'status' => 'approve', // Only approved comments
    'order' => 'DESC', // Sort in descending order
    'number' => $comments_per_page, // Number of comments per page
    'paged' => $paged, // Current page number
);

$comments_query = new WP_Comment_Query;
$comments = $comments_query->query($args);
$total_comments = wp_count_comments(
    null,
    array(
        'status' => 'approve',
    )
)->approved;
$total_pages = ceil($total_comments / $comments_per_page);
?>
<section id="comment" class="bg-gray-100 shadow-md shadow-gray-700">
    <h3 class="mt-3 p-3 text-white bg-gray-900 text-[3.8vw] sm:text-3xl ">みんなのコメント</h3>
    <div id="commentSection" class="bg-opacity-50">

        <?php
        foreach ($comments as $key => $comment) {
            $img_url = wp_get_attachment_url(get_post_meta($comment->comment_post_ID, 'img', true));

            echo '<div class="flex items-center p-2 hover:cursor-pointer hover:bg-gray-200 transition-all duration-500" onclick="">';
            echo '<div class="mx-2">';
            echo '<img src="' . ($img_url?$img_url:"/fight-ranking/wp-content/uploads/2024/04/f5488743a4bacadebf963cb6d644128a.jpg") . '" class="w-24 rounded-md border-3 border-gray-100" />';
            echo '</div>';
            echo '<div class="p-1 mx-2 flex-1">';
            echo '<h3 class="font-bold"></h3>';
            echo '<p href="#" class="break-all">' . $comment->comment_content . '</p>';
            echo '<p class="text-sm"> ';
            echo $comment->comment_author;
            echo ' </p><p>' . $comment->comment_date . ' </p>';
            echo '</div>';
            echo '<hr class="mx-2 text-bg-gray-800">';
            echo '</div>';
        }

        if (!$total_comments) {
            echo '<div class="p-4 text-gray-900 text-[3vw] sm:text-2xl font-bold text-shadow-md text-center ">
    コメントなし
</div>';
        }
        ?>

    </div>

</section>
<!-- Pagination -->
<?php
if ($total_pages > 1) {
    echo '<div class="mt-4 text-[2.6vw] sm: text-[2.6vw] sm:text-xl  font-semibold text-shadow-lg">';
    echo paginate_links(
        array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => 'page/%#%',
            'current' => $paged,
            'total' => $total_pages,
            'prev_text' => __('&laquo; Previous'),
            'next_text' => __('Next &raquo;'),
            'before_page_number' => '<span class="page-number">',
            'after_page_number' => '</span>',
        )
    );
    echo '</div>';
}
?>
<?php
get_footer();
?>