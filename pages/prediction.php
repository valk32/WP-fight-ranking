<?php
/*
Template Name: Prediction
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="px-4 py-32 bg-cover" style="background-image: url('/assets/img/background4.jpg')">
    <div class="w-[800px] mx-auto">
        <section id="vscardsBox">

            <?php
$args = array(
    'post_type' => 'match',
    'posts_per_page' => 10, // Number of posts to retrieve
    'orderby' => 'date', // Order by numeric value
    'order' => 'DESC', // Sort in ascending order
);

$custom_query = new WP_Query($args);

if ($custom_query->have_posts()) {
    while ($custom_query->have_posts()) {
        $custom_query->the_post();
        $matchID = get_the_ID();
        $player1 = get_post_meta($matchID, 'player1', true);
        $player2 = get_post_meta($matchID, 'player2', true);
        $vote1 = get_post_meta($matchID, 'vote1', true);
        $vote2 = get_post_meta($matchID, 'vote2', true);
        $date = get_post_meta($matchID, 'date', true);
        $type = get_post_meta($matchID, 'type', true);
        $result = get_post_meta($matchID, 'result', true);

        $pinfo1 = new WP_Query(array(
            'post_type' => 'playerinfo',
            'meta_key' => 'name',
            'meta_value' => $player1,
        ));
        $img1 = '';
        if ($pinfo1->have_posts()) {
            $pinfo1->the_post();
            $img1 = wp_get_attachment_url(get_post_meta(get_the_ID(), 'img', true));
            wp_reset_postdata();
        }

        $pinfo2 = new WP_Query(array(
            'post_type' => 'playerinfo',
            'meta_key' => 'name',
            'meta_value' => $player2,
        ));
        $img2 = '';
        if ($pinfo2->have_posts()) {
            $pinfo2->the_post();
            $img2 = wp_get_attachment_url(get_post_meta(get_the_ID(), 'img', true));
            wp_reset_postdata();
        }?>
            <div
                class="my-8 relative flex justify-evenly w-[700px] items-center p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 transition-all duration-500">
                <div>
                    <img src="<?php echo $img1 ?>" class="h-[180px] w-[160px] object-cover rounded-md" alt="" />
                    <div class="block text-center text-md">
                        <span class="p-2">
                            <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                            <?php echo $vote1 . "(" . number_format($vote1 * 100 / ($vote1 + $vote2), 1) . "%)"; ?>
                        </span>

                        <form name="form1" id="form1" method="POST" action="/fight-ranking/vote" class="inline">
                            <input type="hidden" name="match_id" value="<?php echo $matchID ?>">
                            <input type="hidden" name="vote_hand" value="0">
                            <button type="submit"
                                class="p-3 py-2 mt-2 bg-gray-900 hover:bg-gray-800 text-white rounded-md text-sm">
                                <i class="fa fa-thumbs-up"></i> 推奨</button>
                        </form>
                    </div>
                </div>
                <div class="mx-2 text-center flex-1">
                    <h4>
                        <h5 class="text-2xl font-semibold inline"><?php echo $player1 ?></h5>
                        <p class="text-2xl font-bold text-red-600">VS</p>
                        <h5 class="text-2xl inline font-semibold"><?php echo $player2 ?></h5>
                    </h4>
                    <div><?php echo DateTime::createFromFormat('Ymd', $date)->format('Y-m-d') ?></div>
                    <span class="text-xl font-semibold text-red-600">
                        <?php
if ($result == 0) {
            echo "未定";
        } else if ($result == 1) {
            echo $player1;
        } else {
            echo $player2;
        }

        ?> <span>
                </div>
                <div>
                    <img src="<?php echo $img2 ?>" class="h-[180px] w-[160px] object-cover rounded-md" alt="" />
                    <div class="block text-center text-md">
                        <span class="p-2">
                            <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                            <?php echo $vote2 . "(" . number_format($vote2 * 100 / ($vote1 + $vote2), 1) . "%)"; ?>
                        </span>
                        <form name="form2" id="form2" method="POST" action="/fight-ranking/vote" class="inline">
                            <input type="hidden" name="match_id" value="<?php echo $matchID ?>">
                            <input type="hidden" name="vote_hand" value="1">
                            <button type="submit"
                                class="p-3 py-2 mt-2 bg-gray-900 hover:bg-gray-800 text-white rounded-md text-sm">
                                <i class="fa fa-thumbs-up"></i> 推奨</button>
                        </form>
                    </div>
                </div>
                <div
                    class="-top-5 w-52 text-center rounded-3xl absolute bg-gray-200 p-2 shadow-md shadow-gray-700 hover:bg-white  transition-all duration-500">
                    <?php echo $type; ?>
                </div>
            </div>
            <?php
}
}

// Restore the global post data
wp_reset_postdata();
?>

        </section>
    </div>
</main>



<?php
get_footer();
?>