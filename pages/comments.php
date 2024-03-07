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
$comments_args = array(
    'status' => 'approve', // Only approved comments
    'order' => 'DESC', // Sort in descending order
    'paged' => get_query_var('paged'), // Get the current page number
);

$comments_query = new WP_Comment_Query;
$comments = $comments_query->query($comments_args);
?>
<main class="px-4 py-28 bg-cover" style="background-image: url('assets/img/background4.jpg')">
    <div class="w-[800px] mx-auto">
        <section id="comment" class="bg-gray-100 shadow-md shadow-gray-700">
            <h3 class="mt-3 p-3 text-white bg-gray-900 text-3xl">全コメント</h3>
            <div id="commentSection" class="bg-opacity-50">

                <?php
foreach ($comments as $key => $comment) {
    $img_url = wp_get_attachment_url(get_post_meta($comment->comment_post_ID, 'img', true));

    echo '<div class="flex items-center p-2 hover:cursor-pointer hover:bg-gray-200 transition-all duration-500" onclick="">';
    echo '<div class="mx-2">';
    echo '<img src="' . $img_url . '" class="w-24 rounded-md border-3 border-gray-100" />';
    echo '</div>';
    echo '<div class="p-1 mx-2">';
    echo '<h3 class="font-bold"></h3>';
    echo '<p href="#">' . $comment->comment_content . '</p>';
    echo '<p class="text-sm"> ';
    echo $comment->comment_author_email;
    echo ' </p><p>' . $comment->comment_date . ' </p>';
    echo '</div>';
    echo '<hr class="mx-2 text-bg-gray-800">';
    echo '</div>';
}
?>

            </div>

            <!-- Manual Pagination -->
            <div class="pagination">
                <?php
$total_comments = $comments_query->found_comments;
$total_pages = ceil($total_comments / $comments_per_page);

if ($total_pages > 1) {
    echo '<div class="pagination-links">';

    for ($i = 1; $i <= $total_pages; $i++) {
        $current_class = ($i == $paged) ? 'current' : '';
        $pagination_link = get_permalink() . 'page/' . $i . '/';
        echo '<a href="' . $pagination_link . '" class="' . $current_class . '">' . $i . '</a>';
    }

    echo '</div>';
}
?>
            </div>
        </section>
    </div>
</main>

<?php
get_footer();
?>