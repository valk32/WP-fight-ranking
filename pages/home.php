<?php
/*
Template Name: HOME
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<?php
$args = array(
    'post_type' => 'vsrequest',
    'posts_per_page' => -1, // Retrieve all posts
    'orderby' => 'meta_value', // Order by meta value
    'meta_key' => 'requestmatchvote', // Custom field key for the date
    'order' => 'DESC', // Sort in ascending order
);

$custom_query = new WP_Query($args);

if ($custom_query->have_posts()) {
    // Initialize an empty array to store the grouped data
    $groupedData = array();

    while ($custom_query->have_posts()) {
        $custom_query->the_post();

        // Get the org value
        $orgID = get_post_meta(get_the_ID(), 'org', true);

        // Create an array with the post data
        $postArray = array(
            'ID' => get_the_ID(),
            'player1' => get_post_meta(get_the_ID(), 'player1', true),
            'player2' => get_post_meta(get_the_ID(), 'player2', true),
            'vote' => get_post_meta(get_the_ID(), 'vote', true),
        );

        // Check if the org value exists as a key in the groupedData array
        if (isset($groupedData[$orgID])) {
            // If the org key exists, add the post data to the content array for that org
            $groupedData[$orgID]['content'][] = $postArray;
        } else {
            // If the org key does not exist, create a new entry in the groupedData array
            $groupedData[$orgID] = array(
                'org' => $orgID,
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
    foreach ($output as $groupkey => $group) {
        echo '<div class="relative mt-9 px-2 py-6 bg-gray-200 bg-opacity-50 rounded-md shadow-md shadow-gray-700">';
        echo '<div id="' . $group['org'] . '" class="flex justify-center mx-auto"></div>';
        echo '<div class="flex flex-col gap-1">';
        foreach ($group['content'] as $key => $content) {
            if ($key > 2)
                continue;
            ?>
            <div
                class="flex flex-wrap gap-3 justify-around w-full items-center py-5 px-2 border border-gray-200 rounded-lg shadow hover:bg-white transition-all duration-500">
                <div class="w-full sm:w-auto">
                    <img class="mx-auto w-24 h-24 sm:w-16 sm:h-16" src="
                        <?php if ($key == 0) {
                            echo '/fight-ranking/wp-content/uploads/2024/03/badge1.png';
                        } elseif ($key == 1) {
                            echo '/fight-ranking/wp-content/uploads/2024/03/badge2.png';
                        } elseif ($key == 2) {
                            echo '/fight-ranking/wp-content/uploads/2024/03/badge3.png';
                        } else {
                            echo '';
                        }
                        ?>"></img>
                </div>
                <div class="flex flex-col items-center gap-4 order-1">
                    <a href="/fight-ranking/person?pid=<?php echo $content['player1'] ?>">
                        <img src="<?php echo wp_get_attachment_url(get_post_meta($content['player1'], 'img', true)) ?>"
                            class="w-[28vw] h-[30vw] sm:w-44 sm:h-48 object-cover rounded-md" alt="" />
                    </a>
                    <p class="font-bold ml-1 text-center break-all">
                        <?php echo get_post_meta($content['player1'], 'displayname', true) ? get_post_meta($content['player1'], 'displayname', true) : get_post_meta($content['player1'], 'name', true) ?>
                    </p>
                </div>
                <div class="flex flex-1 flex-col w-auto justify-between order-2">
                    <div class="flex flex-col flex-1 text-md md:text-2xl">
                        <div class="mx-auto text-red-500">
                            <img src="/fight-ranking/wp-content/uploads/2024/03/VS.png"
                                class="w-[20vw] h-[20vw] sm:w-24 sm:h-24" />
                        </div>
                    </div>
                </div>
                <div class="flex flex-col  items-center gap-4 justify-end order-3">
                    <a href="/fight-ranking/person?pid=<?php echo $content['player2'] ?>">
                        <img src="<?php echo wp_get_attachment_url(get_post_meta($content['player2'], 'img', true)) ?>"
                            class="w-[28vw] h-[30vw] sm:w-44 sm:h-48 object-cover rounded-md" alt="" />
                    </a>
                    <p class="font-bold mr-1 text-center break-all">
                        <?php echo get_post_meta($content['player2'], 'displayname', true) ? get_post_meta($content['player2'], 'displayname', true) : get_post_meta($content['player2'], 'name', true) ?>
                    </p>
                </div>
            </div>
            <?php
        }
        echo '</div>';
        echo '<a href="/fight-ranking/requestcard?orgt=' . $group['org'] . '"
        class="-top-5 left-4 rounded-3xl absolute bg-gray-200 py-2 px-8 shadow-md shadow-gray-700 hover:bg-white">【';
        echo get_post_meta($group['org'], 'orgname', true);
        echo '】<span class="inline">夢の対戦カード</span></a>';
        echo '<div class="flex justify-center">
        <button class="mt-4 p-2 bg-gray-900 text-white rounded-md hover:bg-gray-100 hover:text-gray-900 shadow-md shadow-gray-700">
            <a href="/fight-ranking/requestcard?orgt=' . $group['org'] . '" class="text-sm">もっと見る <i class="fa fa-arrow-circle-right"></i></a>
        </button>
    	</div>';
        echo '</div>';
        echo '<div class="flex justify-center">';
        if (function_exists('adinserter') && $groupkey == 1)
            echo adinserter(1);
        echo '</div>';
    }
    ?>
</section>
<div class="flex justify-center">
    <?php if (function_exists('adinserter'))
        echo adinserter(2); ?>
</div>
<section id="request" class="mt-4 shadow-md shadow-gray-700">
    <?php
    // the_content();
    echo do_shortcode('[mwform_formkey key="158"]');
    ?>
</section>
<div class="flex justify-center">
    <?php if (function_exists('adinserter'))
        echo adinserter(3); ?>
</div>
<section id=" comment" class="bg-gray-100 shadow-md shadow-gray-700">
    <h3 class="mt-3 p-3 text-white bg-gray-900 text-[3.8vw] sm:text-3xl ">
    みんなのコメント
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

            echo '<div class="flex items-center p-2 hover:cursor-pointer hover:bg-gray-200  " onclick="">';
            echo '<div class="mx-2">';
            echo '<img src="' . ($img_url? $img_url:"/fight-ranking/wp-content/uploads/2024/04/f5488743a4bacadebf963cb6d644128a.jpg") . '" class="w-[20vw] sm:w-24 rounded-md border-3 border-gray-100" />';
            echo '</div>';
            echo '<div class="p-1 mx-2 flex-1 break-all">';
            echo '<h3 class="font-bold"></h3>';
            echo '<p href="#">' . $comment->comment_content . '</p>';
            echo '<p class="text-sm"> ';
            echo $comment->comment_author;
            echo ' </p><p>' . $comment->comment_date . ' </p>';
            echo '</div>';
            echo '<hr class="mx-2 text-bg-gray-800">';
            echo '</div>';
        }
        ?>
    </div>
    <div class="flex justify-center">
        <button
            class="my-2 p-2 bg-gray-900 text-white rounded-md hover:bg-gray-100 hover:text-gray-900 shadow-md shadow-gray-700">
            <a href="/fight-ranking/comments" class="text-sm">もっと見る <i class="fa fa-arrow-circle-right"></i></a>
        </button>
    </div>
</section>

<?php
get_footer();
?>