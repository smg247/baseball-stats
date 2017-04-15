<?php
include 'header.html';

$properties = include 'properties.php';
$mysqli = new mysqli($properties['db_host'], $properties['db_user'], $properties['db_pass'], $properties['db_name'], '3306');

echo "<table class='table'>";
echo "<tr><th>Team</th><th>Season</th><th>Wins</th></tr>";
$teamsQuery = 'select id, name from Team;';
$teamsResult = $mysqli->query($teamsQuery);
foreach ($teamsResult as $team) {
    $teamValues = array_values($team);
    $teamId = $teamValues[0];
    $teamName = $teamValues[1];
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
echo '</table>';

include 'footer.html';