<?php
/*
Template Name: Rank
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$ranktype = isset($_GET['ranktype']) ? intval($_GET['ranktype']) : 0;
$weighttype = isset($_GET['weightt']) ? intval($_GET['weightt']) : 0;
$favorite = isset($_GET['favorite']) ? $_GET['favorite'] : '';
?>

<div id="title" class="p-3 text-white text-[3.8vw] sm:text-3xl  bg-gray-900">
    <?php if ($favorite != '') {
        echo '人気選手ランキング';
    } else {
        echo '【'.get_post_meta($ranktype, 'orgname', true).'】'.get_post_meta($weighttype, 'weightname', true);
    }
    ?>
</div>
<section id="rankBox" class="px-2 py-2 bg-gray-100 shadow-md shadow-gray-700">
    <?php
    $args = array(
        'post_type' => 'playerinfo',
        'posts_per_page' => 10, // Number of posts to retrieve
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1, // Current page number
        'meta_key' => $favorite != '' ? 'vote' : 'rank', // Custom field key to order by
        'orderby' => 'meta_value_num', // Order by numeric value
        'order' => $favorite != '' ? 'DESC' : 'ASC', // Sort in ascending order
        'meta_query' => array(
            array(
                'key' => 'org',
                'value' => $ranktype, // Filter by the value of 'org' field
                'compare' => $ranktype ? '=' : '!=',
            ),
            array(
                'key' => 'weight',
                'value' => $weighttype, // Filter by the value of 'org' field
                'compare' => $weighttype ? '=' : '!=',
            ),
        ),
    );

    $custom_query = new WP_Query($args);

    if ($custom_query->have_posts()) {
        $key = 0;
        while ($custom_query->have_posts()) {
            $custom_query->the_post();
            $playerID = get_the_ID();
            $name = get_post_meta($playerID, 'name', true);
            $displayname = get_post_meta($playerID, 'displayname', true);
            $catchcopy = get_post_meta($playerID, 'catchcopy', true);
            $age = get_post_meta($playerID, 'age', true);
            $rank = get_post_meta($playerID, 'rank', true);
            $vote = get_post_meta($playerID, 'vote', true);
            $weight = get_post_meta(get_post_meta($playerID, 'weight', true), 'weightname', true);
            $org = get_post_meta(get_post_meta($playerID, 'org', true), 'orgname', true);
            $record = get_post_meta($playerID, 'record', true);
            $img = wp_get_attachment_url(get_post_meta($playerID, 'img', true));
            ?>

            <div class="mx-1 my-3 flex items-center gap-2 items-stretch transition-all duration-500">
                <div class="flex flex-1 flex-grow items-center">
                    <div class="flex items-center mx-auto mx-none">
                        <div>
                            <?php if ($key <= 2) { ?>
                                <img class="rounded-full w-[10vw] h-[10vw] sm:w-16 sm:h-16 text-center text-white" src="
        <?php if ($key == 0) {
                    echo '/fight-ranking/wp-content/uploads/2024/03/badge1.png';
                } elseif ($key == 1) {
                    echo '/fight-ranking/wp-content/uploads/2024/03/badge2.png';
                } elseif ($key == 2) {
                    echo '/fight-ranking/wp-content/uploads/2024/03/badge3.png';
                }
                ?>"></img>
                            <?php } else {
                                echo '<div class="w-[10vw] h-[10vw] sm:w-16 sm:h-16 text-center text-[6vw] sm:text-4xl text-gray-900 text-shadow-md text-shadow-gray-400 font-semibold">' . ($key + 1) . '</div>';
                            }
                            ?>
                        </div>
                        <div class="p-1">
                            <a href="/fight-ranking/person?pid=<?php echo $playerID ?>">
                                <img src="<?php echo $img ?>" class="h-[19vw] w-[16vw] sm:h-[120px] sm:w-[100px] object-cover rounded-md" alt="">
                            </a>
                        </div>
                    </div>
                    <div
                        class="text-[3vw] sm:text-md p-3 flex flex-1 items-center bg-gray-200 bg-opacity-70 rounded-xl shadow-md shadow-gray-400 min-w-[207px]">
                        <div class="flex-1 text-left">
                            <h4 class="text-[3vw] sm:text-2xl font-semibold">
                                <p class="text-[2.6vw] sm: text-[2.6vw] sm:text-xl  inline font-normal">
                                    <?php echo $catchcopy; ?>
                                </p>
                                <p>
                                    <?php echo $name; ?>
                                </p>
                            </h4>
                            <button class="p-3 py-2 mt-2 bg-gray-900 hover:bg-gray-100 hover:text-gray-900 text-white rounded-md text-[2vw] sm:text-sm shadow-md shadow-gray-700">
                                <a href="/fight-ranking/person?pid=<?php echo $playerID ?>">
                                    <?php echo $displayname ? $displayname : $name ?>の評判 <i class="fa fa-arrow-right"
                                        aria-hidden="true"></i>
                                </a>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="w-[13vw] sm:w-24 h-[20vw] sm:h-[130px] my-auto">
                    <form method="POST" action="/fight-ranking/vote">
                        <input type="hidden" name="player_id" value="<?php echo $playerID ?>">
                        <button type="submit"
                            class="w-full h-full text-[2.6vw] sm: text-[2.6vw] sm:text-xl  p-3 py-2 bg-red-500 hover:bg-gray-100 hover:text-gray-900 text-white shadow-md rounded-md overflow-hidden shadow-gray-700">
                            <p class="block"><i class="fa fa-thumbs-up"></i></p>
                            <p class="block">
                                <?php echo $vote ?>
                            </p>
                        </button>
                    </form>
                </div>
            </div>
            <div class="flex justify-center">
                <?php if ($favorite != '') {
                    if($key != $custom_query->found_posts-1 && $key==2 && function_exists ('adinserter') ) echo adinserter (6);
                    if($key != $custom_query->found_posts-1 && $key==9 && function_exists ('adinserter') ) echo adinserter (12);
                }
                else {
                    if($key != $custom_query->found_posts-1 && $key==2 && function_exists ('adinserter')) echo adinserter (9);
                    if($key != $custom_query->found_posts-1 && $key==9 && function_exists ('adinserter')) echo adinserter (13);
                }?>
            </div>
            <?php
            $key += 1;
        }

        // Pagination
        $total_pages = $custom_query->max_num_pages;
        if ($total_pages > 1) {
            $current_page = max(1, get_query_var('paged'));
            echo '<div class=" text-[2.6vw] sm:text-xl  font-semibold text-shadow-lg">';
            echo paginate_links(
                array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '', // URL structure for pagination links
                    'current' => $current_page,
                    'total' => $total_pages,
                )
            );
            echo '</div>';
        }
    } else {
        ?>
        <div class="p-4 text-gray-900 text-[2.6vw] sm:text-2xl font-bold text-shadow-md text-center ">
            該当選手がいません
        </div>
        <?php
    }

    // Restore the global post data
    wp_reset_postdata();
    ?>

</section>
<section id=" comment" class="bg-gray-100 shadow-md shadow-gray-700">
    <h3 class="mt-3 p-3 text-white bg-gray-900 text-[3.8vw] sm:text-3xl ">
        最近のコメント
    </h3>
    <div id="commentSection" class="bg-opacity-50">
        <?php
        $comments_per_page = 10; // Number of comments per page
        $comments_args = array(
            'post_id' => get_the_ID(),
            'status' => 'approve', // Only approved comments
            'order' => 'DESC', // Sort in descending order
            'paged' => get_query_var('paged'), // Get the current page number
        );

        $comments_query = new WP_Comment_Query;
        $comments = $comments_query->query($comments_args);
        ?>
        <?php
        foreach ($comments as $key => $comment) {

            if ($key >= 10) {
                continue;
            }

            $img_url = wp_get_attachment_url(get_post_meta($comment->comment_post_ID, 'img', true));

            echo '<div class="flex items-center p-2 hover:cursor-pointer hover:bg-gray-200  " onclick="">';
            echo '<div class="mx-2">';
            echo '<img src="' . ($img_url?$img_url:"/fight-ranking/wp-content/uploads/2024/04/f5488743a4bacadebf963cb6d644128a.jpg") . '" class="w-[20vw] sm:w-24 rounded-md border-3 border-gray-100" />';
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
<div class="mt-6">
    <?php
    comment_form();
    ?>
</div>
<?php
get_footer();
?>