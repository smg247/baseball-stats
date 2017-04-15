<?php
include 'html_templaters.php';
include 'header.html';

$id = $_GET['id'];

$columns = array(Column::simpleDisplayColumn('city', 'City'), Column::simpleDisplayColumn('name', 'Name'));
$team_templater = new ReadTemplater('Team', $columns, $id, "id", null, true, 'create-edit-team.php', null, null, null, null);

echo '<h1>Team Details</h1>';
echo $team_templater->execute();

$columns = array(
    Column::simpleDisplayColumn('YEAR(year)', 'Year'),
    Column::simpleDisplayColumn('wins', 'Wins'),
    Column::simpleDisplayColumn('losses', 'Losses'),
    Column::simpleDisplayColumn('rank', 'Rank'),
    Column::valuedColumn('team_id', $id, 'hidden')
);

$season_templater = new ReadTemplater('Season', $columns, $id, "team_id", 'Season', true, "create-edit-season.php", "?team_id=$id", null, "team.php?id=$id", "Season");

echo '<h2>Seasons</h2>';
echo $season_templater->execute();

$columns = array(
    Column::simpleDisplayColumn('away_team_name', 'Versus'),
    Column::simpleDisplayColumn('home_score', 'Our Score'),
    Column::simpleDisplayColumn('away_score', 'Their Score'),
    Column::simpleDisplayColumn('date', 'Date'),
    Column::valuedColumn('home_team_id', $id, 'hidden')
);

$home_game_templater = new ReadTemplater('DetailedGame', $columns, $id, 'home_team_id', 'Game', true, 'create-edit-game.php', "?team_id=$id", null, "team.php?id=$id", "Game");

$columns = array(
    Column::simpleDisplayColumn('home_team_name', 'At'),
    Column::simpleDisplayColumn('away_score', 'Our Score'),
    Column::simpleDisplayColumn('home_score', 'Their Score'),
    Column::simpleDisplayColumn('date', 'Date'),
    Column::valuedColumn('away_team_id', $id, 'hidden')
);

$away_game_templater = new ReadTemplater('DetailedGame', $columns, $id, 'away_team_id', 'Game', true, 'create-edit-game.php', "?team_id=$id", null, "team.php?id=$id", null);

echo '<h2>Games</h2>';
echo $home_game_templater->execute();
echo $away_game_templater->execute();

include 'footer.html';
