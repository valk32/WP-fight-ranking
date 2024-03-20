<?php
/*
Template Name: Rank
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$ranktype = isset ($_GET['ranktype']) ? intval($_GET['ranktype']) : 0;
$weighttype = isset ($_GET['weightt']) ? intval($_GET['weightt']) : 0;
$favorite = isset ($_GET['favorite']) ? $_GET['favorite'] : '';
?>

<div id="title" class="p-3 text-white text-3xl bg-gray-900">
    <?php if ($favorite != '') {
        echo '好きな格闘家 総合ランキング';
    } else {
        echo '格闘家 総合ランキング';
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
            $catchcopy = get_post_meta($playerID, 'catchcopy', true);
            $age = get_post_meta($playerID, 'age', true);
            $rank = get_post_meta($playerID, 'rank', true);
            $vote = get_post_meta($playerID, 'vote', true);
            $weight = get_post_meta(get_post_meta($playerID, 'weight', true), 'weightname', true);
            $org = get_post_meta(get_post_meta($playerID, 'org', true), 'orgname', true);
            $record = get_post_meta($playerID, 'record', true);
            $img = wp_get_attachment_url(get_post_meta($playerID, 'img', true));
            ?>

            <div class="mx-1 my-3 flex flex-col sm:flex-row gap-2 items-stretch transition-all duration-500">
                <div class="flex flex-wrap flex-grow items-center">
                    <div class="flex items-center mx-auto sm:mx-none">
                        <div>
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
                                echo '<div class="min-w-16 text-center text-4xl text-gray-900 text-shadow-md text-shadow-gray-400 font-semibold">' . ($key + 1) . '</div>';
                            }
                            ?>
                        </div>
                        <div class="p-2">
                            <a href="/fight-ranking/person?pid=<?php echo $playerID ?>">
                                <img src="<?php echo $img ?>"
                                    class="h-[150px] w-[125px] sm:h-[120px] sm:w-[100px] object-cover rounded-md" alt="">
                            </a>
                        </div>
                    </div>
                    <div
                        class="text-md p-4 flex flex-1 min-w-[150px] items-center bg-gray-200 bg-opacity-70 rounded-xl shadow-md shadow-gray-400">
                        <div class="flex-1 text-center sm:text-left">
                            <h4 class="text-2xl font-semibold">
                                <p class="text-xl inline font-normal">
                                    <?php echo $catchcopy; ?>
                                </p>
                                <p>
                                    <?php echo $name; ?>
                                </p>
                            </h4>
                            <div class="p-2">
                                <a href="/fight-ranking/person?pid=<?php echo $playerID ?>"
                                    class="p-3 py-2 mt-2 bg-gray-900 hover:bg-gray-100 hover:text-gray-900 text-white rounded-md text-sm shadow-md shadow-gray-700">
                                    詳細ビュー <i class="fa fa-arrow-right" aria-hidden="true"></i> </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full sm:w-20 py-3">
                    <form method="POST" action="/fight-ranking/vote">
                        <input type="hidden" name="player_id" value="<?php echo $playerID ?>">
                        <button type="submit"
                            class="w-full h-full p-3 py-2 bg-gray-900 hover:bg-gray-100 hover:text-gray-900 text-white shadow-md rounded-md overflow-hidden shadow-gray-700">
                            <p class="inline sm:block"><i class="fa fa-thumbs-up"></i></p>
                            <p class="text-xl inline sm:block">
                                <?php echo $vote ?>
                            </p>
                        </button>
                    </form>
                </div>
            </div>
            <?php
            $key += 1;
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