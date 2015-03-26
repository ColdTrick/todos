<?php
$due_todos = 'lijstje';

echo elgg_view_module('aside', elgg_echo("Due to-do items"), $due_todos);

$recently_closed = 'lijstje';

echo elgg_view_module('aside', elgg_echo("Recently closed to-do items"), $recently_closed);

$recently_added = 'lijstje';

echo elgg_view_module('aside', elgg_echo("Recently added to-do items"), $recently_added);