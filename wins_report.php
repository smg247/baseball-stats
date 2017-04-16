<?php
error_reporting(0); // Need to turn off to use create functionality with missing params
include 'header.html';

function outputWinsForTeam($teamId, $mysqli, $teamName)
{
    $seasonsQuery = "select id, YEAR(year) from Season where team_id = $teamId;";

    $seasonsResult = $mysqli->query($seasonsQuery);
    foreach ($seasonsResult as $season) {
        $seasonValues = array_values($season);
        $seasonId = $seasonValues[0];
        $year = $seasonValues[1];
        $winsQuery = "select wins_by_team_season($teamId, $seasonId);";
        $result = $mysqli->query($winsQuery);
        $wins = array_values($result->fetch_assoc())[0];

        echo "<tr><td>$teamName</td><td>$year</td><td>$wins</td>";
    }
}

$properties = include 'properties.php';
$mysqli = new mysqli($properties['db_host'], $properties['db_user'], $properties['db_pass'], $properties['db_name'], '3306');

$teamName = $_GET['team_name'];

echo "<form class='form' method='get'><label for='team_name'>Team Name</label> <input type='text' name='team_name' /><input class='btn' type='submit'></form>";

echo "<table class='table'>";
echo "<tr><th>Team</th><th>Season</th><th>Wins</th></tr>";
if ($teamName == null) { // Going to show all teams
    $teamsQuery = 'select id, name from Team;';
    $teamsResult = $mysqli->query($teamsQuery);

    foreach ($teamsResult as $team) {
        $teamValues = array_values($team);
        $teamId = $teamValues[0];
        $teamName = $teamValues[1];
        outputWinsForTeam($teamId, $mysqli, $teamName);
    }
} else { // Only the provided team
    $teamIdQuery = "select id from Team where name like '%$teamName%';";
    $result = $mysqli->query($teamIdQuery);
    $teamId = array_values($result->fetch_assoc())[0];
    outputWinsForTeam($teamId, $mysqli, $teamName);
}
echo '</table>';

include 'footer.html';