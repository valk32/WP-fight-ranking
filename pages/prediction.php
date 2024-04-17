<?php
/*
Template Name: Prediction
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$competitionID = isset($_GET['id']) ? intval($_GET['id']) : 0;

?>

<section id="vscardsBox">
    <div>
        <?php
        $args = array(
            'post_type' => 'match',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'key' => 'isheader',
                    'value' => true,
                ),
                array(
                    'key' => 'type',
                    'value' => $competitionID,
                ),
            ),
        );

        $custom_query = new WP_Query($args);
        if ($custom_query->have_posts()) {
            $custom_query->the_post();
            $matchID = get_the_ID();
            $competition_name = get_post_meta($competitionID, 'competition_name', true);
            $competition_date = get_post_meta($competitionID, 'date', true);
            $org_name = get_post_meta($competitionID, 'org_name', true);
            $competition_date = DateTime::createFromFormat('Ymd', $competition_date)->format('Y-m-d');
            
            $player1 = get_post_meta($matchID, 'player1', true);
            $player2 = get_post_meta($matchID, 'player2', true);
            $date = get_post_meta($matchID, 'date', true);
            $type = get_post_meta($matchID, 'type', true);
            $name1 = get_post_meta($player1, 'name', true);
            $displayname1 = get_post_meta($player1, 'displayname', true);
            $name2 = get_post_meta($player2, 'name', true);
            $displayname2 = get_post_meta($player2, 'displayname', true);
            $img1 = wp_get_attachment_url(get_post_meta($player1, 'img', true));
            $img2 = wp_get_attachment_url(get_post_meta($player2, 'img', true));
            ?>
            <div style="background-image: url('/fight-ranking/wp-content/uploads/2024/04/prediction_header.jpeg')"
                class="mb-4 absolute top-0 left-1/2 -translate-x-1/2 px-1 pt-4 pb-4 bg-cover bg-center shadow mb-8 w-screen max-w-[1024px]    relative justify-evenly items-center text-gray-900 rounded-lg shadow-md shadow-gray-700">
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/3 w-[36vw] sm:w-64">
                    <img src="/fight-ranking/wp-content/uploads/2024/04/title-badge.png" alt="">
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center text-gray-100 font-semibold drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,1)]">
                        <p class="text-[3vw] sm:text-2xl"><?php echo $competition_name?$competition_name:$org_name; ?></p>
                        <p class="text-md"><?php echo $competition_date?$competition_date:"未定"; ?></p>
                    </div>
                
                </div>
                <div class="my-auto flex flex-col justify-between sm:justify-around items-center">
                    <div class="w-full flex justify-between sm:justify-around">
                        <div class="w-48">
                            <img src="<?php echo $img1 ?>"
                                class="mx-auto w-[28vw] h-[28vw] sm:w-48 sm:h-48 rounded-full shadow-gray-700 shadow-lg">
                        </div>
                        <div class="w-48">
                            <img src="<?php echo $img2 ?>"
                                class="mx-auto w-[28vw] h-[28vw] sm:w-48 sm:h-48 rounded-full shadow-gray-700 shadow-lg">
                        </div>
                    </div>
                    <div class="w-full flex justify-around">
                        <h5
                            class="text-[3vw] sm:text-2xl text-center text-gray-100 font-semibold drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,1)]">
                            <?php echo $name1 ?>
                        </h5>
                        <h5
                            class="text-[3vw] sm:text-2xl text-center text-gray-100 font-semibold drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,1)]">
                            <?php echo $name2 ?>
                        </h5>
                    </div>
                </div>
                <!-- <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/4 w-[30vw]"> -->
                    <!-- <img src="/fight-ranking/wp-content/uploads/2024/04/date-badge.png" alt=""> -->
                <!-- </div> -->
            </div>
            <?php
        }
        ?>
    </div>
    <?php
    $current_date = date('Y-m-d'); // Get the current date in the 'YYYY-MM-DD' format
    $args = array(
        'post_type' => 'match',
        'posts_per_page' => 10, // Number of posts to retrieve
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1, // Current page number
        'meta_query' => array(
            array(
                'key' => 'type',
                'value' => $competitionID,
            ),
        ),
    );

    $custom_query = new WP_Query($args);
    $key = 0;
    if ($custom_query->have_posts()) {
        while ($custom_query->have_posts()) {
            $custom_query->the_post();
            $matchID = get_the_ID();
            $catch = get_post_meta($matchID, 'catch', true);
            $player1 = get_post_meta($matchID, 'player1', true);
            $player2 = get_post_meta($matchID, 'player2', true);
            $vote1 = get_post_meta($matchID, 'vote1', true);
            $vote2 = get_post_meta($matchID, 'vote2', true);
            $date = get_post_meta($matchID, 'date', true);
            $type = get_post_meta($matchID, 'type', true);
            $result = get_post_meta($matchID, 'result', true);

            if ($vote1 + $vote2 == 0) {
                $percent1 = $percent2 = 50;
            } else {
                $percent1 = $vote1 * 100 / ($vote1 + $vote2);
                $percent2 = $vote2 * 100 / ($vote1 + $vote2);
            }
            $name1 = get_post_meta($player1, 'name', true);
            $displayname1 = get_post_meta($player1, 'displayname', true);
            $name2 = get_post_meta($player2, 'name', true);
            $displayname2 = get_post_meta($player2, 'displayname', true);
            $record1 = get_post_meta($player1, 'record', true);
            $record2 = get_post_meta($player2, 'record', true);
            $img1 = wp_get_attachment_url(get_post_meta($player1, 'img', true));
            $img2 = wp_get_attachment_url(get_post_meta($player2, 'img', true));
            $weight = get_post_meta(get_post_meta($player1, 'weight', true), 'weightname', true);
            ?>
            <div
                class="mb-8 relative justify-evenly items-center text-gray-900 rounded-lg shadow-md shadow-gray-700 transition-all duration-500">
                <div style="background-image: url('/fight-ranking/wp-content/uploads/2024/03/VS1.jpg')"
                    class="px-1 pt-4 pb-4 w-full max-h-full bg-cover bg-center rounded-lg shadow">
                    <!-- Modal content -->
                    <div class="mt-4 flex flex-col justify-between sm:justify-around items-center">
                        <div class="w-full flex justify-around">
                            <h5 class="text-[3vw] sm:text-2xl text-center text-gray-100 font-semibold">
                                <?php echo $displayname1 ? $displayname1 : $name1 ?>
                            </h5>
                            <h5 class="text-[3vw] sm:text-2xl text-center text-gray-100 font-semibold">
                                <?php echo $displayname2 ? $displayname2 : $name2 ?>
                            </h5>
                        </div>
                        <div class="w-full flex justify-between sm:justify-around">
                            <div class="w-48">
                                <img data-modal-target="modal1<?php echo $matchID ?>"
                                    data-modal-toggle="modal1<?php echo $matchID ?>" src="<?php echo $img1 ?>"
                                    class="mx-auto w-[28vw] h-[28vw] sm:w-48 sm:h-48 rounded-full hover:animate-pulse hover:cursor-pointer duration-500 border-4 border-gray-400 border-solid"
                                    alt="">
                                <div id="modal1<?php echo $matchID ?>" tabindex="-1" aria-hidden="true"
                                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <!-- Modal header -->
                                            <div
                                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                <h1 class="text-[3vw] sm:text-2xl font-bold text-gray-900 dark:text-white">この選手に投票しますか？</h1>

                                                <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                    data-modal-hide="modal1<?php echo $matchID ?>">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="p-4 md:p-5 border-t rounded-b border-gray-600 text-right">
                                                <form method="POST" action="/fight-ranking/vote">
                                                    <input type="hidden" name="match_id" value="<?php echo $matchID ?>">
                                                    <input type="hidden" name="vote_hand" value="0">
                                                    <button type="submit" data-modal-hide="modal1<?php echo $matchID ?>"
                                                        type="button"
                                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                        はい</button>


                                                    <button data-modal-hide="modal1<?php echo $matchID ?>" type="button"
                                                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">いいえ</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="absolute w-20 bottom-0 -left-8">
                                    <img src="/fight-ranking/wp-content/uploads/2024/03/arrow.png" class="w-20 h-12">
                                    <p class="text-gray-200 drop-shadow-2">勝者を予想してタップで投票！</p>
                                </div> -->
                            </div>
                            <div class="w-48">
                                <img data-modal-target="modal2<?php echo $matchID ?>"
                                    data-modal-toggle="modal2<?php echo $matchID ?>" src="<?php echo $img2 ?>"
                                    class="mx-auto w-[28vw] h-[28vw] sm:w-48 sm:h-48 rounded-full hover:animate-pulse hover:cursor-pointer duration-500 border-4 border-gray-400 border-solid"
                                    alt="">
                                <div id="modal2<?php echo $matchID ?>" tabindex="-1" aria-hidden="true"
                                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <!-- Modal header -->
                                            <div
                                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                <h1 class="text-[3vw] sm:text-2xl font-bold text-gray-900 dark:text-white">この選手に投票しますか？</h1>

                                                <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                    data-modal-hide="modal2<?php echo $matchID ?>">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="p-4 md:p-5 border-t rounded-b border-gray-600 text-right">
                                                <form method="POST" action="/fight-ranking/vote">
                                                    <input type="hidden" name="match_id" value="<?php echo $matchID ?>">
                                                    <input type="hidden" name="vote_hand" value="1">
                                                    <button type="submit" data-modal-hide="modal2<?php echo $matchID ?>"
                                                        type="button"
                                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                        はい</button>


                                                    <button data-modal-hide="modal2<?php echo $matchID ?>" type="button"
                                                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">いいえ</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex justify-center items-end text-center my-2 text-md sm:text-2xl text-gray-200 font-bold drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">
                        <img src="/fight-ranking/wp-content/uploads/2024/03/arrow.png"
                            class="w-[9vw] h-[7vw] sm:w-20 sm:h-12 scale-x-[-1] scale-y-[-1] transform rotate-90">
                        <p>勝者を予想してタップで投票!</p>
                        <img src="/fight-ranking/wp-content/uploads/2024/03/arrow.png"
                            class="w-[9vw] h-[7vw] sm:w-20 sm:h-12 scale-y-[-1] transform -rotate-90">
                    </div>
                    <div class="mx-4 flex justify-center">
                        <div
                            class="w-[calc(<?php echo $percent1 ?>%<?php if ($percent1 == 100) {
                                   echo '-8rem';
                               }
                               ?>)] min-w-28 text-center bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-[2.6vw] sm: text-[2.6vw] sm:text-xl  text-gray-200 font-bold drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">
                            <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                            <?php echo $vote1 ?>(
                            <?php echo number_format($percent1, 1) ?>%)
                        </div>
                        <div class="w-[calc(<?php echo $percent2 ?>%<?php if ($percent2 == 100) {
                               echo '-8rem';
                           }
                           ?>)] min-w-28 text-center bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-90%  text-[2.6vw] sm: text-[2.6vw] sm:text-xl  text-gray-200 font-bold
                        drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">
                            <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                            <?php echo $vote2 ?>(
                            <?php echo number_format($percent2, 1) ?>%)
                        </div>
                    </div>
                </div>
                <div
                    class="-top-5 w-56 left-1/2 -translate-x-1/2 text-center font-semibold rounded-3xl absolute bg-gray-200 p-2 shadow-md shadow-gray-700 hover:bg-white">
                    <?php echo $catch ?>
                </div>
            </div>
            <div class="flex justify-center mb-8">
                <?php if ($key != $custom_query->found_posts-1 && $key==4 &&  function_exists ('adinserter')) echo adinserter (5); ?>
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
            予定される試合なし
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