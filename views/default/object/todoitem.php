<?php

$full = (bool) elgg_extract('full_view', $vars, false);
$entity = elgg_extract('entity', $vars);

$default_show_due = true;
$default_show_assignee = true;
$default_show_checkbox = true;

if (elgg_in_context('todos_sidebar')) {
	$default_show_due = false;
	$default_show_assignee = false;
	$default_show_checkbox = false;
}

if (elgg_in_context('widgets')) {
	$default_show_due = false;
	$default_show_assignee = false;
	$default_show_checkbox = false;
}

$show_due = elgg_extract('show_due', $vars, $default_show_due);
$show_assignee = elgg_extract('show_assignee', $vars, $default_show_assignee);
$show_checkbox = elgg_extract('show_checkbox', $vars, $default_show_checkbox);

if (!$full) {
	$checkbox = '';
	if ($show_checkbox) {
		$checkbox = elgg_view('input/checkbox', [
			'rel' => $entity->guid,
			'checked' => $entity->isCompleted(),
			'disabled' => !$entity->canEdit(),
		]);
	}
	
	$body = elgg_view('output/url', [
		'text' => $entity->getDisplayName(),
		'href' => $entity->getURL(),
		'is_trusted' => true,
	]);
	
	$info = [];
	
	if ($show_assignee) {
		$assignee = $entity->getAssignee();
		if (!empty($assignee)) {
			$assignee_text = '<span class="todos-item-assignee">';
			$assignee_text .= elgg_view('output/url', [
				'text' => $assignee->getDisplayName(),
				'href' => $assignee->getURL(),
				'is_trusted' => true,
			]);
			$assignee_text .= '</span>';
	
			$info[] = $assignee_text;
		}
	}
		
	if ($entity->due && $show_due) {
		$class = 'todos-item-due';
		if (!$entity->isCompleted() && ($entity->due < time())) {
			$class .= ' todos-item-overdue';
		}
		$due_text = "<span class='{$class}'>";
		$due_text .= elgg_view('output/date', ['value' => $entity->due]);
		$due_text .= '</span>';
		
		$info[] = $due_text;
	}
	
	if (!empty($info)) {
		$body .= '<span class="todos-item-info mls">';
		$body .= implode('<span class="phs">&#8226;</span>', $info);
		$body .= '</span>';
	}
	
	$body .= elgg_view_menu('todoitem', [
		'entity' => $entity,
		'class' => 'elgg-menu-hz elgg-menu-todos',
		'sort_by' => 'register',
	]);
	
	$params = [];
	if ($entity->isCompleted()) {
		$params['class'] = 'todos-list-item-completed';
	}

	echo elgg_view_image_block($checkbox, $body, $params);
	return;
}

$content = '';
$imprint = [];

// full view
if ($entity->description) {
	$content .= elgg_view('output/longtext', [
		'value' => $entity->description,
		'class' => 'mbm',
	]);
}


if ($entity->due) {
	$imprint[] = [
		'icon_name' => 'calendar',
		'class' => ($entity->due < time()) ? 'todos-item-overdue' : null,
		'content' => elgg_echo('todos:todoitem:due') . ': ' . elgg_view('output/date', ['value' => $entity->due]),
	];
}

$assignee = $entity->getAssignee();
if (!empty($assignee)) {
	$assignee_text = elgg_echo('todos:todoitem:assignee') . ': ';
	$assignee_text .= elgg_view('output/url', [
		'text' => $assignee->getDisplayName(),
		'href' => $assignee->getURL(),
		'is_trusted' => true,
	]);
	
	$imprint[] = [
		'icon_name' => 'user',
		'content' => $assignee_text,
	];
}

$attachments = $entity->getAttachments();
if (!empty($attachments)) {
	$content .= '<label>' . elgg_echo('todos:todoitem:attachment') . ': </label>';
	$content .= '<ul class="mlm">';
	
	foreach ($attachments as $attachment) {
		$content .= '<li>';
		$content .= elgg_view('output/url', [
			'text' => $attachment,
			'href' => elgg_generate_url('todoitem:attachment:download', [
				'guid' => $entity->guid,
				'filename' => $attachment,
			]),
		]);
		
		if ($entity->canEdit()) {
			$content .= elgg_view('output/url', [
				'text' => elgg_view_icon('delete'),
				'href' => "action/todos/todoitem/delete_attachment?guid={$entity->guid}&filename={$attachment}",
				'confirm' => elgg_echo('deleteconfirm'),
				'title' => elgg_echo('delete'),
				'class' => 'mlm',
			]);
		}
		
		$content .= '</li>';
	}
	
	$content .= '</ul>';
}

$responses = elgg_view_comments($entity);

$activity = elgg_list_river([
	'object_guids' => [$entity->guid],
	'action_types' => ['create', 'reopen', 'close'],
	'limit' => false,
]);

if ($activity) {
	$responses .= elgg_view_module('info', elgg_echo('activity'), $activity);
}

$params = [
	'icon' => false,
	'body' => $content,
	'access' => false,
	'show_summary' => true,
	'show_social_menu' => false,
	'show_navigation' => false,
	'imprint' => $imprint,
	'responses' => $responses,
];
$params = $params + $vars;

echo elgg_view('object/elements/full', $params);
