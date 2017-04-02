<?php
include 'html_templaters.php';
include 'header.html';

$columns = array(Column::simpleDisplayColumn('city', 'City'), Column::simpleDisplayColumn('name', 'Name'));
$createTemplater = new CreateTemplater('Team', $columns, 'team.php');

echo $createTemplater->execute();

include 'footer.html';