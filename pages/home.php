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
$args = array(
    'post_type' => 'playerinfo',
    'posts_per_page' => -1, // Retrieve all posts
    'orderby' => 'rank', // Sort by the meta value
    'order' => 'DESC', // Sort in descending order
);

$custom_query = new WP_Query($args);

if ($custom_query->have_posts()) {
    // Initialize an empty array to store the grouped data
    $groupedData = array();

    while ($custom_query->have_posts()) {
        $custom_query->the_post();

        // Get the org value
        $org = get_post_meta(get_the_ID(), 'org', true);

        // Create an array with the post data
        $postArray = array(
            'ID' => get_the_ID(),
            'name' => get_post_meta(get_the_ID(), 'name', true),
            'age' => get_post_meta(get_the_ID(), 'age', true),
            'rank' => get_post_meta(get_the_ID(), 'rank', true),
            'vote' => get_post_meta(get_the_ID(), 'vote', true),
            'weight' => get_post_meta(get_the_ID(), 'weight', true),
            'record' => get_post_meta(get_the_ID(), 'record', true),
            'img' => wp_get_attachment_url(get_post_meta(get_the_ID(), 'img', true)),
        );

        // Check if the org value exists as a key in the groupedData array
        if (isset($groupedData[$org])) {
            // If the org key exists, add the post data to the content array for that org
            $groupedData[$org]['content'][] = $postArray;
        } else {
            // If the org key does not exist, create a new entry in the groupedData array
            $groupedData[$org] = array(
                'org' => $org,
                'content' => array($postArray),
            );
        }
    }

    // Output the grouped data
    $output = array_values($groupedData); // Reset array keys to ensure numeric indexing
}

// Restore the global post data
wp_reset_postdata();
?>

<section id="rankBox">

    <?php

foreach ($output as $group) {
    echo '<div class="relative mt-9 p-8 bg-gray-200 bg-opacity-50 rounded-md shadow-md shadow-gray-700">';
    echo '<div id="' . $group['org'] . '" class="flex justify-center mx-auto"></div>';
    echo '<div class="flex justify-center">';
    foreach ($group['content'] as $key => $content) {
        echo '<a href="/fight-ranking/person?pid=' . $content['ID'] . '" class="mx-1 w-48 relative text-white hover:opacity-70 hover:cursor-pointer shadow-md shadow-gray-400 rounded-md transition-all duration-500">';
        echo '<img src="' . $content['img'] . '" class="h-[180px] w-full object-cover" alt="" />';
        echo '<div class="w-full bg-blue-600 text-white text-center"><i class="fa fa-thumbs-up"></i>' . $content['vote'] . '</div>';
        echo '<div class="w-full bg-gray-900 text-white p-2 text-center">' . $content['name'] . ' (' . $content['age'] . ')</div>';
        echo '<div class="absolute top-2 left-2 rounded-full w-7 h-7 text-center ';
        if ($key == 0) {
            echo 'bg-yellow-400';
        } elseif ($key == 1) {
            echo 'bg-gray-400';
        } elseif ($key == 2) {
            echo 'bg-yellow-800';
        } else {
            echo 'bg-gray-800';
        }
        echo ' text-white border-2 border-white">' . $key + 1 . '</div>';
        echo '</a>';
    }

    echo '</div>';
    echo '<a href="/fight-ranking/rank?ranktype=' . $group['org'] . '"  class="-top-5 left-4 rounded-3xl absolute bg-gray-200 py-2 px-8 shadow-md shadow-gray-700 hover:bg-white  transition-all duration-500">';
    echo '夢の対戦カード 総合ランキング1〜3位 ' . $group['org'];
    echo '</a>';
    echo '</div>';
}
?>
</section>
<section id="comment" class="bg-gray-100 shadow-md shadow-gray-700">
    <h3 class="mt-3 p-3 text-white bg-gray-900 text-3xl">
        最近のコメント
    </h3>
    <div id="commentSection" class="bg-opacity-50">
        <?php
$comments_per_page = 3; // Number of comments per page
$comments_args = array(
    'status' => 'approve', // Only approved comments
    'order' => 'DESC', // Sort in descending order
    'paged' => get_query_var('paged'), // Get the current page number
);

$comments_query = new WP_Comment_Query;
$comments = $comments_query->query($comments_args);
?>
        <?php
foreach ($comments as $key => $comment) {

    if ($key >= 3) {
        continue;
    }

    $img_url = wp_get_attachment_url(get_post_meta($comment->comment_post_ID, 'img', true));

    echo '<div class="flex items-center p-2 hover:cursor-pointer hover:bg-gray-200 transition-all duration-500" onclick="">';
    echo '<div class="mx-2">';
    echo '<img src="' . $img_url . '" class="w-24 rounded-md border-3 border-gray-100" />';
    echo '</div>';
    echo '<div class="p-1 mx-2 flex-1 break-all">';
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
    <div class="flex justify-center">
        <button class="my-2 p-2 bg-gray-900 text-white rounded-md hover:opacity-80">
            <a href="/fight-ranking/comments" class="text-sm">もっと見る <i class="fa fa-arrow-circle-right"></i></a>
        </button>
    </div>
</section>
<section id="request" class="mt-4 shadow-md shadow-gray-700">
    <h3 class="mt-3 p-3 text-white bg-gray-900 text-3xl">
    </h3>
    <div class="px-3 py-2 bg-white text-2xl text-center">
        <div>
            <input type="text"
                class="w-28 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                placeholder="おくのたかし" required />
            vs
            <input type="text"
                class="w-28 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                placeholder="中岸風太" required />が見たい！
        </div>
        <button class="px-3 py-2 mt-2 bg-gray-900 text-white rounded-md text-sm">
            <i class="fa fa-send"></i> 転送
        </button>
    </div>
</section>

<?php
get_footer();
?>