<?php

$title = get_input('title');
$description = get_input('description');
$tags = get_input('tags');
$container_guid = (int) get_input('container_guid');
$guid = (int) get_input('guid');
$due = (int) get_input('due');
$assignee = get_input('members');
$attachment = elgg_get_uploaded_file('attachment');

$entity = false;

if (empty($title)) {
	return elgg_error_response(elgg_echo('todos:action:error:title'));
}

if (!empty($guid)) {
	$entity = get_entity($guid);
	if (!$entity instanceof \TodoItem) {
		return elgg_error_response(elgg_echo('InvalidParameterException:NoEntityFound'));
	}
	
	if (!$entity->canEdit()) {
		return elgg_error_response(elgg_echo('actionunauthorized'));
	}
	
	$container_guid = $entity->getContainerGUID();
}

if (!empty($assignee) && !is_array($assignee)) {
	$assignee = array($assignee);
}
if (!empty($assignee) && count($assignee) > 1) {
	return elgg_error_response(elgg_echo('todos:action:todoitem:edit:assignee'));
}

if (empty($container_guid)) {
	return elgg_error_response(elgg_echo('todos:todoitem:error:missing_container'));
}

$todolist = get_entity($container_guid);
if (!$todolist instanceof \TodoList) {
	return elgg_error_response(elgg_echo('todos:todoitem:error:missing_container'));
}

if (!$todolist->canWriteToContainer(0, 'object', TodoItem::SUBTYPE)) {
	return elgg_error_response(elgg_echo('todos:action:todoitem:edit:cant_write'));
}

$new_entity_created = false;
if (empty($entity)) {
	// check due date for the past
	if (!empty($due) && ($due < mktime(0,0,0))) {
		return elgg_error_response(elgg_echo('todos:action:todoitem:edit:due_in_past'));
	}
	
	$entity = new TodoItem();
	$entity->container_guid = $todolist->guid;
	$entity->access_id = $todolist->access_id;
	$entity->save();
	
	$new_entity_created = true;
}

$entity->title = $title;
$entity->description = $description;
$entity->tags = string_to_tag_array($tags);

$entity->setDueDate($due);

if ($entity->canAssign($assignee, true)) {
	$entity->assign($assignee);
}

if (!empty($attachment)) {
	$entity->attach($attachment->getClientOriginalName(), file_get_contents($attachment->getPathname()));
}

if (!$entity->save()) {
	return elgg_error_response(elgg_echo('todos:action:todoitem:edit:error'));
}

if ($new_entity_created) {
	elgg_create_river_item([
		'view' => 'river/object/todoitem/create',
		'action_type' => 'create',
		'subject_guid' => elgg_get_logged_in_user_guid(),
		'object_guid' => $entity->guid,
	]);
}

return elgg_ok_response('', elgg_echo('todos:action:todoitem:edit:success'));
