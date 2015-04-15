<?php

$full = (bool) elgg_extract('full_view', $vars, false);
$entity = elgg_extract('entity', $vars);

$default_show_due = true;
$default_show_comments = true;
$default_show_assignee = true;
$default_show_checkbox = true;

if (elgg_in_context('todos_sidebar')) {
	$default_show_due = false;
	$default_show_comments = false;
	$default_show_assignee = false;
	$default_show_checkbox = false;
}

if (elgg_in_context('widgets')) {
	$default_show_due = false;
	$default_show_comments = false;
	$default_show_assignee = false;
	$default_show_checkbox = false;
}

$show_due = elgg_extract('show_due', $vars, $default_show_due);
$show_comments = elgg_extract('show_comments', $vars, $default_show_comments);
$show_assignee = elgg_extract('show_assignee', $vars, $default_show_assignee);
$show_checkbox = elgg_extract('show_checkbox', $vars, $default_show_checkbox);

if (!$full) {
	$checkbox = '';
	if ($show_checkbox) {
		$checkbox = elgg_view('input/checkbox', array(
			'rel' => $entity->guid,
			'checked' => $entity->isCompleted(),
			'disabled' => !$entity->canEdit()
		));
	}
	
	$body = elgg_view('output/url', array(
		'text' => $entity->title,
		'href' => $entity->getURL(),
		'is_trusted' => true
	));
	
	if ($show_comments) {
		$comments_count = $entity->countComments();
		if ($comments_count) {
			$body .= '<span class="todos-item-comments mls">';
			$body .= elgg_echo('comments:count', array($comments_count));
			$body .= '</span>';
		}
	}
	
	$info = array();
	
	if ($show_assignee) {
		$assignee = $entity->getAssignee();
		if (!empty($assignee)) {
			$assignee_text = '<span class="todos-item-assignee">';
			$assignee_text .= elgg_view('output/url', array(
				'text' => $assignee->name,
				'href' => $assignee->getURL(),
				'is_trusted' => true
			));
			$assignee_text .= '</span>';
	
			$info[] = $assignee_text;
		}
	}
		
	if ($entity->due && $show_due) {
		$due_text = '<span class="todos-item-due">';
		$due_text .= elgg_view('output/date', array('value' => $entity->due));
		$due_text .= '</span>';
		
		$info[] = $due_text;
	}
	
	if (!empty($info)) {
		$body .= '<span class="todos-item-info mls">';
		$body .= implode('<span class="phs">&#8226;</span>', $info);
		$body .= '</span>';
	}
	
	$body .= elgg_view_menu('todoitem', array(
		'entity' => $entity,
		'class' => 'elgg-menu-hz elgg-menu-todos',
		'sort_by' => 'register'
	));
	
	$params = array();
	if ($entity->isCompleted()) {
		$params['class'] = 'todos-list-item-completed';
	}

	echo elgg_view_image_block($checkbox, $body, $params);
	
} else {
	
	$rows = array();
	
	if ($entity->due) {
		$row = '<td>';
		$row .= '<label>' . elgg_echo('todos:todoitem:due') . ': </label>';
		$row .= '</td><td class="plm">';
		$row .= elgg_view('output/date', array('value' => $entity->due));
		$row .= '</td>';
		
		$rows[] = $row;
	}
	
	$assignee = $entity->getAssignee();
	if (!empty($assignee)) {
		$row = '<td>';
		$row .= '<label>' . elgg_echo('todos:todoitem:assignee') . ': </label>';
		$row .= '</td><td class="plm">';
		$row .= elgg_view('output/url', array(
			'text' => $assignee->name,
			'href' => $assignee->getURL(),
			'is_trusted' => true
		));
		$row .= '</td>';
		
		$rows[] = $row;
	}
	
	$attachments = $entity->getAttachments();
	if (!empty($attachments) || $entity->canEdit()) {
		$row = '<td>';
		$row .= '<label>' . elgg_echo('todos:todoitem:attachment') . ': </label>';
		$row .= '</td><td class="plm">';
		
		if (!empty($attachments)) {
			$row .= '<ul>';
			
			foreach ($attachments as $attachment) {
				$row .= '<li>';
				$row .= elgg_view('output/url', array(
					'text' => $attachment,
					'href' => "todos/attachment/{$entity->getGUID()}/{$attachment}"
				));
				
				if ($entity->canEdit()) {
					$row .= elgg_view('output/confirmlink', array(
						'text' => elgg_view_icon('delete'),
						'href' => "action/todos/todoitem/delete_attachment?guid={$entity->getGUID()}&filename={$attachment}",
						'confirm' => elgg_echo('deleteconfirm'),
						'title' => elgg_echo('delete'),
						'class' => 'mlm'
					));
				}
				
				$row .= '</li>';
			}
			
			$row .= '</ul>';
		}
		
		$rows[] = $row;
		
		if ($entity->canEdit()) {
			elgg_load_js('lightbox');
			elgg_load_css('lightbox');
			
			$row = '<td></td><td class="plm">';
			$row .= elgg_view('output/url', array(
				'text' => elgg_echo('todos:todoitem:attachment:upload'),
				'href' => "ajax/view/todos/todoitem/attach?guid={$entity->getGUID()}",
				'class' => 'elgg-lightbox'
			));
			$row .= '</div>';
			
			$rows[] = $row;
		}
	}
	
	if (!empty($rows)) {
		echo '<table><tr>';
		echo implode('</tr><tr>', $rows);
		echo '</tr></table>';
	}
	
	echo elgg_view_comments($entity, $entity->canComment());
	
	$activity = elgg_list_river(array(
		'object_guids' => array($entity->guid),
		'action_types' => array('create', 'reopen', 'close'),
		'limit' => false
	));
	
	if ($activity) {
		echo elgg_view_module('info', elgg_echo('activity'), $activity);
	}
}
