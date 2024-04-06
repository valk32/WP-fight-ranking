<?php
/*
Template Name: Match Request
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$orgtype = isset ($_GET['orgt']) ? intval($_GET['orgt']) : 0;
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
    <div class="p-3 w-full text-white text-3xl bg-gray-900">
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
                class="relative mt-8 p-3 w-full bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 transition-all duration-500">
                <div class="absolute top-4 left-1 w-full sm:w-auto">
                <?php if ($key <= 2) { ?>    
                    <img class="rounded-full w-16 h-16 text-center text-white" src="
                        <?php if ($key == 0) {
                            echo '/fight-ranking/wp-content/uploads/2024/03/badge1.png';
                        } elseif ($key == 1) {
                            echo '/fight-ranking/wp-content/uploads/2024/03/badge2.png';
                        } elseif ($key == 2) {
                            echo '/fight-ranking/wp-content/uploads/2024/03/badge3.png';
                        }
                        ?>"></img>
                    <?php } else {
                        echo '<div class="w-16 h-16 block text-center text-4xl text-gray-100 text-shadow-md text-shadow-gray-400 font-semibold">' . ($key + 1) . '</div>';
                    }?>
                </div>
                <div style="background-image: url('/fight-ranking/wp-content/uploads/2024/03/VS1.jpg')"
                    class="px-1 pt-2 pb-4 w-full max-h-full bg-cover bg-center rounded-lg shadow">
                    <div class="mt-4 flex flex-col justify-around items-center">
                        <div class="w-full flex justify-around">
                            <div class="sm:w-48">
                                <img data-modal-target="modal1<?php echo $matchID ?>"
                                    data-modal-toggle="modal1<?php echo $matchID ?>" src="<?php echo $img1 ?>"
                                    class="mx-auto w-32 h-32 sm:w-48 sm:h-48 rounded-full border-4 border-gray-400 border-solid"
                                    alt="">
                            </div>
                            <div class="sm:w-48">
                                <img data-modal-target="modal2<?php echo $matchID ?>"
                                    data-modal-toggle="modal2<?php echo $matchID ?>" src="<?php echo $img2 ?>"
                                    class="mx-auto w-32 h-32 sm:w-48 sm:h-48 rounded-full border-4 border-gray-400 border-solid"
                                    alt="">
                            </div>
                        </div>
                        <div class="w-full flex justify-around">
                            <h5 class="text-2xl text-center text-gray-100 font-semibold">
                                <?php echo $displayname1 ? $displayname1 : $name1 ?>
                            </h5>
                            <h5 class="text-2xl text-center text-gray-100 font-semibold">
                                <?php echo $displayname2 ? $displayname2 : $name2 ?>
                            </h5>
                        </div>
                    </div>
                    
                </div>
                <form method="POST" action="/fight-ranking/vote">
                    <input type="hidden" name="player_id" value="<?php echo $matchID ?>">
                    <button
                        class="w-full sm:w-48 p-4 mt-3 mx-auto text-center text-xl rounded-md text-gray-100 bg-gray-900 p-2 shadow-md shadow-gray-700 hover:bg-gray-100 hover:text-gray-900 block">
                        見たい <i class="fa fa-thumbs-up" aria-hidden="true"></i> (
                        <?php echo $vote ?>)
                    </button>
                </form>
                <div class="w-full text-center flex">
                    <form method="POST" action="/fight-ranking/vote" class="w-[calc(<?php echo $percent1 ?>%<?php if ($percent1 == 100) {
                                 echo '-8rem';
                             }?>)] min-w-32">
                        <input type="hidden" name="match_id" value="<?php echo $matchID ?>">
                        <input type="hidden" name="vote_hand" value="0">
                        <button type="submit" class="w-full h-full block text-center rounded-md text-gray-100 bg-red-500 p-2 shadow-md shadow-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <?php echo $displayname1 ? $displayname1 : $name1 ?>(
                            <?php echo number_format($percent1, 1) ?>%)
                        </button>
                    </form>
                    <form method="POST" action="/fight-ranking/vote" class="w-[calc(<?php echo $percent2 ?>%<?php if ($percent2 == 100) {
                                 echo '-8rem';
                             }?>)] min-w-32">
                        <input type="hidden" name="match_id" value="<?php echo $matchID ?>">
                        <input type="hidden" name="vote_hand" value="1">
                        <button type="submit" class="w-full h-full block text-center rounded-md text-gray-100 bg-blue-900 p-2 shadow-md shadow-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <?php echo $displayname2 ? $displayname2 : $name2 ?>(
                            <?php echo number_format($percent2, 1) ?>%)
                        </button>
                    </form>
                </div>
                <div class="text-center mt-2 font-semibold flex justify-evenly items-center">
                    <i class="fa fa-arrow-up" arria-hidden=true></i><span>勝敗予想をタップで投票!</span><i class="fa fa-arrow-up"
                        arria-hidden=true></i>
                </div>
            </div>
            <?php
            $key++;
        }

        // Pagination
        $total_pages = $custom_query->max_num_pages;
        if ($total_pages > 1) {
            $current_page = max(1, get_query_var('paged'));
            echo '<div class="text-xl font-semibold text-shadow-lg">';
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
        <div class="p-4 text-gray-900 text-2xl font-bold text-shadow-md text-center ">
            対戦なし
        </div>
        <?php
    }
    // Restore the global post data
    wp_reset_postdata();
    ?>

</section>

<?php
get_footer();
?>