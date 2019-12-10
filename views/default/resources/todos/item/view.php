<?php

$guid = (int) get_input('guid');

elgg_entity_gatekeeper($guid, 'object', \TodoItem::SUBTYPE);
$entity = get_entity($guid);

$list = $entity->getContainerEntity();
if ($list instanceof \ElggEntity) {
	$owner = $list->getOwnerEntity();
	if ($owner instanceof \ElggUser) {
		elgg_push_breadcrumb($owner->getDisplayName(), $owner->getURL());
		elgg_push_breadcrumb(elgg_echo('collection:object:todolist'), elgg_generate_url('collection:object:todolist:all'));
	}
}

elgg_push_entity_breadcrumbs($entity, false);

$title = $entity->getDisplayName();

$content = elgg_view_entity($entity, [
	'full_view' => true,
]);

$body = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
	'entity' => $entity,
]);

echo elgg_view_page($title, $body);

