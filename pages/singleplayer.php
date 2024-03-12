<?php
/*
Template Name: PlayerInfo
 */

get_header();
?>

<?php

$pid = isset($_GET['pid']) ? $_GET['pid'] : '';

$post = get_post($pid);
$playerID = $pid;
$name = get_post_meta($playerID, 'name', true);
$age = get_post_meta($playerID, 'age', true);
$rank = get_post_meta($playerID, 'rank', true);
$vote = get_post_meta($playerID, 'vote', true);
$weight = get_post_meta(get_post_meta($playerID, 'weight', true), 'weightname', true);
$org = get_post_meta(get_post_meta($playerID, 'org', true), 'orgname', true);
$record = get_post_meta($playerID, 'record', true);
$img = wp_get_attachment_url(get_post_meta($playerID, 'img', true)); //     }

$comments = get_comments(
    array(
        'post_id' => $playerID,
        'status' => 'approve', // Retrieve only approved comments
        'oderby' => 'date',
        'order' => 'DESC', // Order comments in ascending order by time
    )
);
// Restore the global post data
?>


<section id="personInfoBox">
    <div class="p-3 text-white text-3xl bg-gray-900">
        <?php echo $name; ?>選手の個人情報
    </div>

    <div onclick="" class="mx-1 my-3 flex items-center relative  ">
        <div class="p-2">
            <img src="<?php echo $img ?>" class="h-[180px] w-[160px] object-cover rounded-md" alt="" />
        </div>
        <div
            class="text-md p-4 ml-4 my-2 w-full flex flex-1 items-center  bg-gray-200  rounded-xl shadow-md shadow-gray-400">
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
                <h4 class="font-semibold">
                    <?php echo $name; ?>
                </h4>
                <p>
                    <?php echo $rank; ?>
                </p>
                <p>
                    <?php echo $age; ?>
                </p>
                <p><i class="fa fa-thumbs-up"></i>
                    <?php echo $vote; ?>
                </p>
                <p>
                    <?php echo $record; ?>
                </p>
                <p>
                    <?php echo $weight; ?>
                </p>
                <p>
                    <?php echo $org; ?>
                </p>
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
    foreach ($comments as $key => $comment) {
        echo '<div class="flex items-center p-2 hover:cursor-pointer  " onclick="">';
        echo '<div class="mx-2">';
        echo '<img src="' . $img . '" class="w-24 h-full rounded-md border-3 border-gray-100" />';
        echo '</div>';
        echo '<div class="p-3 mx-2 flex-1 break-all bg-gray-200 hover:bg-gray-300 rounded-md ">';
        echo '<h3 class="font-bold"></h3>';
        echo '<p href="#">' . $comment->comment_content . '</p>';
        echo '<p class="text-sm"> ';
        echo $comment->comment_author;
        echo ' </p><p>' . $comment->comment_date . ' </p>';
        echo '</div>';
        echo '<hr class="mx-2 text-gray-800">';
        echo '</div>';

        echo '<hr class="mx-2 text-gray-800">';
    }
    ?>
</section>
<?php
comment_form();
?>



<?php get_footer(); ?>