<?php

$guid = (int) get_input('guid');

elgg_entity_gatekeeper($guid, 'object', \TodoItem::SUBTYPE);
$entity = get_entity($guid);

elgg_push_entity_breadcrumbs($entity, false);

$title = $entity->getDisplayName();

$content = elgg_view_entity($entity, [
	'full_view' => true,
	'show_responses' => false,
]);

$body = elgg_view_layout('content', [
	'title' => $title,
	'filter' => false,
	'content' => $content,
	'entity' => $entity,
]);

echo elgg_view_page($title, $body);

