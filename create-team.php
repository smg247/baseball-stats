<?php
include 'html_templaters.php';
include 'header.html';

$columns = array(Column::simpleColumn('city', 'City'), Column::simpleColumn('name', 'Name'));
$createTemplater = new CreateTemplater('Team', $columns, 'team.php');

echo $createTemplater->execute();

include 'footer.html';