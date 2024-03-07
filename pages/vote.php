<?php
/*
Template Name: Vote
 */

session_start();
// Check if the form is submitted and the "vote" button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['player_id'])) {
    // Get the player ID from the form submission
    $playerId = $_POST['player_id'];
    // Perform any necessary validation and security checks here

    if (isset($_SESSION['voted'][$playerId])) {
        echo "You have already voted for this match.";
    } else {
        // Process the vote and store the information
        $_SESSION['voted'][$playerId] = $playerId;
        // Update the vote field of the player
        update_player_vote($playerId);
    }

    // Redirect the user or display a success message
    // For example, redirect back to the previous page
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['match_id'])) {
    // Get the player ID from the form submission
    $matchID = $_POST['match_id'];
    $vote_hand = $_POST['vote_hand'];
    // Perform any necessary validation and security checks here
    if (isset($_SESSION['voted'][$matchID])) {
        echo "You have already voted for this match.";
    } else {
        // Process the vote and store the information
        $_SESSION['voted'][$matchID] = $voteHand;
        // Update the vote field of the player
        update_match_vote($matchID, $vote_hand);
    }

    // Redirect the user or display a success message
    // For example, redirect back to the previous page
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
// Function to update the vote field in the playerinfo post
function update_player_vote($playerId)
{
    // Retrieve the existing vote count for the player
    $existingVoteCount = get_post_meta($playerId, 'vote', true);

    // Increment the vote count by 1
    $newVoteCount = $existingVoteCount + 1;

    // Update the vote field in the playerinfo post
    update_post_meta($playerId, 'vote', $newVoteCount);
}

function update_match_vote($matchID, $vote_hand)
{
    // Retrieve the existing vote count for the player
    $existingVoteCount = get_post_meta($matchID, $vote_hand ? 'vote2' : 'vote1', true);

    // Increment the vote count by 1
    $newVoteCount = $existingVoteCount + 1;

    // Update the vote field in the playerinfo post
    update_post_meta($matchID, $vote_hand ? 'vote2' : 'vote1', $newVoteCount);
}