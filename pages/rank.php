<?php
/*
Template Name: Rank
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div id="title" class="p-3 text-white text-3xl bg-gray-900">
    夢の対戦カード 総合ランキング
</div>
<section id="rankBox">
    <?php
$ranktype = isset($_GET['ranktype']) ? $_GET['ranktype'] : '';
$weighttype = isset($_GET['weighttype']) ? $_GET['weighttype'] : '';
$favorite = isset($_GET['favorite']) ? $_GET['favorite'] : '';

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
            'compare' => 'LIKE', // Use '=' for exact value match
        ),
        array(
            'key' => 'weight',
            'value' => $weighttype, // Filter by the value of 'org' field
            'compare' => 'LIKE', // Use '=' for exact value match
        ),
    ),
);

$custom_query = new WP_Query($args);

if ($custom_query->have_posts()) {
    while ($custom_query->have_posts()) {
        $custom_query->the_post();
        $playerID = get_the_ID();
        $name = get_post_meta($playerID, 'name', true);
        $age = get_post_meta($playerID, 'age', true);
        $rank = get_post_meta($playerID, 'rank', true);
        $vote = get_post_meta($playerID, 'vote', true);
        $weight = get_post_meta($playerID, 'weight', true);
        $org = get_post_meta($playerID, 'org', true);
        $record = get_post_meta($playerID, 'record', true);
        $img = wp_get_attachment_url(get_post_meta($playerID, 'img', true));
        ?>

    <div class="mx-1 my-3 flex items-center relative transition-all duration-500">
        <div class="p-2">
            <img src="<?php echo $img ?>" class="h-[180px] w-[160px] object-cover rounded-md" alt="">
        </div>
        <div
            class="text-md p-4 ml-4 my-2 w-full flex flex-1 items-center bg-gray-200 bg-opacity-70 rounded-xl shadow-md shadow-gray-400">
            <div class="">
                <h4 class="mr-2">氏名</h4>
                <p>ランク</p>
                <p>年齢</p>
                <p>推奨</p>
                <p>戦績</p>
                <p>体給</p>
                <p>団体</p>
            </div>
            <div class="mr-2">
                <h4 class="">:</h4>
                <p>:</p>
                <p>:</p>
                <p>:</p>
                <p>:</p>
                <p>:</p>
                <p>:</p>
            </div>
            <div class="flex-1 ">
                <h4 class="font-semibold"><?php echo $name; ?></h4>
                <p><?php echo $rank; ?></p>
                <p><?php echo $age; ?></p>
                <p><i class=" fa fa-thumbs-up"></i> <?php echo $vote; ?></p>
                <p><?php echo $record . '(勝-敗-KO)'; ?></p>
                <p><?php echo $weight; ?></p>
                <p><?php echo $org; ?></p>
            </div>
            <form method="POST" action="/fight-ranking/vote">
                <input type="hidden" name="player_id" value="<?php echo $playerID ?>">
                <button type="submit"
                    class="absolute bottom-4 right-3 p-3 py-2 mt-2 bg-gray-900 hover:bg-gray-800 text-white rounded-md text-sm">
                    <i class="fa fa-thumbs-up"></i> 推奨</button>
            </form>
        </div>
    </div>

    <?php
}

    // Pagination
    $total_pages = $custom_query->max_num_pages;
    if ($total_pages > 1) {
        $current_page = max(1, get_query_var('paged'));
        echo '<div class="text-xl font-semibold text-shadow-lg">';
        echo paginate_links(array(
            'base' => add_query_arg('paged', '%#%'),
            'format' => '', // URL structure for pagination links
            'current' => $current_page,
            'total' => $total_pages,
        ));
        echo '</div>';
    }
} else {
    ?>
    <div class="p-4 text-gray-900 text-2xl font-bold text-shadow-md text-center ">
        該当選手がいません
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