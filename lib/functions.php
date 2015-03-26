<?php
/**
 * All helper functions are bundled here
 */

function todos_get_open_assigned_item_options($assignee = 0) {
	
	$assignee = sanitise_int($assignee, false);
	
	$options = array(
		'type' => 'object',
		'subtype' => TodoItem::SUBTYPE,
		'limit' => false,
		'metadata_name_value_pairs' => array(
			array(
				'name' => 'order',
				'value' => 0,
				'operand' => '>'
			)
		),
		'full_view' => false,
		'list_class' => 'todos-list',
		'pagination' => false
	);
	
	if (!empty($assignee)) {
		// assiged to specific person
		$options['metadata_name_value_pairs'][] = array(
			'name' => 'assignee',
			'value' => $assignee
		);
	} else {
		// just assigned
		$options['metadata_name_value_pairs'][] = array(
			'name' => 'assignee',
			'value' => 0,
			'operand' => '>'
		);
	}
	
	return $options;
}