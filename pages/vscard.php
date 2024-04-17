<?php
/*
Template Name: Match Request
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$orgtype = isset($_GET['orgt']) ? intval($_GET['orgt']) : 0;
$current_date = date('Y-m-d'); // Get the current date in the 'YYYY-MM-DD' format
$args = array(
    'post_type' => 'vsrequest',
    'posts_per_page' => 10, // Number of posts to retrieve
    'paged' => get_query_var('paged') ? get_query_var('paged') : 1, // Current page number
    'orderby' => 'meta_value', // Order by meta value
    'meta_key' => 'requestmatchvote', // Custom field key for the date
    'order' => 'DESC', // Sort in ascending order
    'meta_query' => array(
        array(
            'key' => 'org',
            'value' => $orgtype, // Filter by the value of 'org' field
        )
    ),
);
?>

<section id="vscardsBox">
    <div class="p-3 w-full text-white text-[3.8vw] sm:text-3xl  bg-gray-900">
        【<?php echo get_post_meta($orgtype, 'orgname', true) ?>】夢の対戦カード
    </div>
    <?php

    $custom_query = new WP_Query($args);

    if ($custom_query->have_posts()) {
        $key = 0;
        while ($custom_query->have_posts()) {
            $custom_query->the_post();
            $matchID = get_the_ID();
            $player1 = get_post_meta($matchID, 'player1', true);
            $player2 = get_post_meta($matchID, 'player2', true);
            $vote = get_post_meta($matchID, 'vote', true);
            $vote1 = get_post_meta($matchID, 'vote1', true);
            $vote2 = get_post_meta($matchID, 'vote2', true);

            if ($vote1 + $vote2 == 0) {
                $percent1 = $percent2 = 50;
            } else {
                $percent1 = $vote1 * 100 / ($vote1 + $vote2);
                $percent2 = $vote2 * 100 / ($vote1 + $vote2);
            }

            $name1 = get_post_meta($player1, 'name', true);
            $displayname1 = get_post_meta($player1, 'displayname', true);
            $img1 = wp_get_attachment_url(get_post_meta($player1, 'img', true));
            $record1 = get_post_meta($player1, 'record', true);
            $rank1 = get_post_meta($player1, 'rank', true);

            $name2 = get_post_meta($player2, 'name', true);
            $displayname2 = get_post_meta($player2, 'name', true);
            $img2 = wp_get_attachment_url(get_post_meta($player2, 'img', true));
            $record2 = get_post_meta($player2, 'record', true);
            $rank2 = get_post_meta($player2, 'rank', true);
            ?>
            <div
                class="relative mt-8 p-3 w-full bg-gray-800 border border-gray-200 rounded-lg shadow hover:bg-gray-100 transition-all duration-500">
                <div class="absolute top-4 left-1 w-full sm:w-auto">
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
                        echo '<div class="w-[10vw] h-[10vw] sm:w-16 sm:h-16 block text-center text-4xl text-gray-100 text-shadow-md text-shadow-gray-400 font-semibold">' . ($key + 1) . '</div>';
                    } ?>
                </div>
                <div style="background-image: url('/fight-ranking/wp-content/uploads/2024/03/VS1.jpg')"
                    class="px-1 pt-2 pb-4 w-full max-h-full bg-cover bg-center rounded-lg shadow">
                    <div class="mt-4 flex flex-col justify-around items-center">
                        <div class="w-full flex justify-around">
                            <div class="sm:w-48">
                                <img data-modal-target="modal1<?php echo $matchID ?>"
                                    data-modal-toggle="modal1<?php echo $matchID ?>" src="<?php echo $img1 ?>"
                                    class="mx-auto w-[28vw] h-[28vw] sm:w-48 sm:h-48 rounded-full border-4 border-gray-400 border-solid"
                                    alt="">
                            </div>
                            <div class="sm:w-48">
                                <img data-modal-target="modal2<?php echo $matchID ?>"
                                    data-modal-toggle="modal2<?php echo $matchID ?>" src="<?php echo $img2 ?>"
                                    class="mx-auto w-[28vw] h-[28vw] sm:w-48 sm:h-48 rounded-full border-4 border-gray-400 border-solid"
                                    alt="">
                            </div>
                        </div>
                        <div class="w-full flex justify-around">
                            <h5 class="text-[3vw] sm:text-2xl text-center text-gray-100 font-semibold">
                                <?php echo $displayname1 ? $displayname1 : $name1 ?>
                            </h5>
                            <h5 class="text-[3vw] sm:text-2xl text-center text-gray-100 font-semibold">
                                <?php echo $displayname2 ? $displayname2 : $name2 ?>
                            </h5>
                        </div>
                    </div>

                </div>
                <form method="POST" action="/fight-ranking/vote">
                    <input type="hidden" name="player_id" value="<?php echo $matchID ?>">
                    <button
                        class="w-full sm:w-48 p-4 mt-3 mx-auto text-center text-[3.2vw] sm:text-xl  rounded-md text-gray-100 bg-red-500 p-2 shadow-md shadow-gray-700 hover:bg-gray-100 hover:text-gray-900 block"><!--style="text-shadow: 0px 0px 1px #000, 1px 0px 1px #000, -1px 0px 1px #000, 0px 1px 1px #000, 0px -1px 1px #000;"-->
                        見たい <i class="fa fa-thumbs-up" aria-hidden="true"></i> (<?php echo $vote ?>)
                    </button>
                </form>
                <div class="w-full text-center flex text-[3vw] sm:text-base">
                    <form method="POST" action="/fight-ranking/vote" class="w-[calc(<?php echo $percent1 ?>%<?php if ($percent1 == 100) {
                           echo '-8rem';
                       } ?>)] min-w-32">
                        <input type="hidden" name="match_id" value="<?php echo $matchID ?>">
                        <input type="hidden" name="vote_hand" value="0">
                        <button type="submit"
                            class="w-full h-full block text-center rounded-md text-gray-100 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-2 sm:p-4 shadow-md shadow-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <?php echo $displayname1 ? $displayname1 : $name1 ?>(
                            <?php echo number_format($percent1, 1) ?>%)
                        </button>
                    </form>
                    <form method="POST" action="/fight-ranking/vote" class="w-[calc(<?php echo $percent2 ?>%<?php if ($percent2 == 100) {
                           echo '-8rem';
                       } ?>)] min-w-32">
                        <input type="hidden" name="match_id" value="<?php echo $matchID ?>">
                        <input type="hidden" name="vote_hand" value="1">
                        <button type="submit"
                            class="w-full h-full block text-center rounded-md text-gray-100 bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 p-2 sm:p-4 shadow-md shadow-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <?php echo $displayname2 ? $displayname2 : $name2 ?>(
                            <?php echo number_format($percent2, 1) ?>%)
                        </button>
                    </form>
                </div>
                <div class="text-center mt-2 text-gray-100 font-semibold flex justify-evenly items-center">
                    <!--style="text-shadow: 0px 0px 1px #000, 1px 0px 1px #000, -1px 0px 1px #000, 0px 1px 1px #000, 0px -1px 1px #000;">-->
                    <i class="fa fa-arrow-up" arria-hidden=true></i><span>勝敗予想をタップで投票!</span><i class="fa fa-arrow-up"
                        arria-hidden=true></i>
                </div>
            </div>
            <div class="flex justify-center">
                <?php if ($key != $custom_query->found_posts - 1 && $key == 2 && function_exists('adinserter'))
                    echo adinserter(4); ?>
                <?php if ($key != $custom_query->found_posts - 1 && $key == 9 && function_exists('adinserter'))
                    echo adinserter(10); ?>
                <?php if ($key != $custom_query->found_posts - 1 && $key == 19 && function_exists('adinserter'))
                    echo adinserter(11); ?>
            </div>
            <?php
            $key++;
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
            対戦なし
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