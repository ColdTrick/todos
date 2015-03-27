<?php

$item = elgg_extract('item', $vars);
$object = $item->getObjectEntity();

echo elgg_view('river/elements/layout', array(
	'item' => $item
));
