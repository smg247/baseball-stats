<?php
error_reporting(0); // Need to turn off to use create functionality with missing params
include 'html_templaters.php';
include 'header.html';

$is_edit = $_GET['is-edit'];

$columns = array(Column::createEditColumn('city', 'City', 'text', $_GET['city']),
                 Column::createEditColumn('name', 'Name', 'text', $_GET['name']));

$createTemplater = new CreateEditTemplater('Team', $columns, $_GET['id'], 'team.php', boolval($is_edit));

echo $createTemplater->execute();

include 'footer.html';