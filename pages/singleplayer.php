<?php
/*
Template Name: PlayerInfo
 */

get_header();
?>

<?php

$pid = isset ($_GET['pid']) ? $_GET['pid'] : '';

$post = get_post($pid);
$playerID = $pid;
$name = get_post_meta($playerID, 'name', true);
$catchcopy = get_post_meta($playerID, 'catchcopy', true);
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
        <?php echo $name; ?>
    </div>

    <div onclick="" class="mx-auto my-3 flex flex-col sm:flex-row items-center relative w-auto sm:w-[450px]">
        <div class="p-2 mx-auto sm:mx-none">
            <img src="<?php echo $img ?>" class="h-[180px] w-[160px] object-cover rounded-md" alt="" />
        </div>
        <div class="text-md ml-4 my-2 w-full flex flex-col flex-1 justify-around">
            <div class="flex-1 p-4 bg-gray-200 w-full rounded-xl shadow-md shadow-gray-400">
                <p class="text-xl">
                    <?php echo $catchcopy ?>
                </p>
                <p class="text-2xl font-bold">
                    <?php echo $name ?>
                </p>
                <p class="text-xl">
                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                    <?php echo $vote ?>
                </p>
            </div>
            <form method="POST" action="/fight-ranking/vote" class="w-full">
                <input type="hidden" name="player_id" value="<?php echo $playerID ?>">
                <button type="submit"
                    class="p-3 py-2 w-full mt-2 bg-gray-900 hover:bg-gray-100 hover:text-gray-900 shadow-md shadow-gray-700 text-white rounded-md">
                    応援投票 <i class="fa fa-thumbs-up"></i></button>
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