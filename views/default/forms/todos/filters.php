<?php

$filters = (array) elgg_extract('filters', $vars, array());
$base_url = elgg_extract('base_url', $vars);
$container = elgg_extract('container', $vars);

// show completed items
$show_completed = '<div>';
$show_completed .= elgg_view('input/checkboxes', array(
	'name' => 'filters[show_completed]',
	'value' => elgg_extract('show_completed', $filters),
	'options' => array(
		elgg_echo('todos:form:filters:show_completed') => 1,
	),
));
$show_completed .= '</div>';

echo $show_completed;

// date filter
$date_options = array(
	'' => elgg_echo('todos:form:filters:date:all'),
	'today' => elgg_echo('todos:form:filters:date:today'),
	'tomorrow' => elgg_echo('todos:form:filters:date:tomorrow'),
	'overdue' => elgg_echo('todos:form:filters:date:overdue'),
	'range' => elgg_echo('todos:form:filters:date:range'),
);

$range_class = array('plm', 'todos-form-filters-range');
if (elgg_extract('date', $filters) !== 'range') {
	$range_class[] = 'hidden';
}

$date = '<div>';
$date .= '<label for="todos-filters-date">' . elgg_echo('todos:form:filters:date') . '</label>';
$date .= elgg_view('input/dropdown', array(
	'id' => 'todos-filters-date',
	'name' => 'filters[date]',
	'value' => elgg_extract('date', $filters),
	'options_values' => $date_options,
	'class' => 'mls',
));
$date .= '<div ' . elgg_format_attributes(array('class' => $range_class)) . '>';
$date .= '<label>' . elgg_echo('todos:form:filters:date:range:from') . '</label>';
$date .= elgg_view('input/datepicker', array(
	'name' => 'filters[range_lower]',
	'value' => elgg_extract('range_lower', $filters),
	'timestamp' => true,
));
$date .= '<label>' . elgg_echo('todos:form:filters:date:range:to') . '</label>';
$date .= elgg_view('input/datepicker', array(
	'name' => 'filters[range_upper]',
	'value' => elgg_extract('range_upper', $filters),
	'timestamp' => true,
));
$date .= '</div>';
$date .= '</div>';

echo $date;

// assignee filter
$assignee_options = todos_get_assignee_filter_for_container($container);

$assignee = '<div>';
$assignee .= '<label for="todos-filters-assignee">' . elgg_echo('todos:form:filters:assignee') . '</label>';
$assignee .= elgg_view('input/dropdown', array(
	'id' => 'todos-filters-assignee',
	'name' => 'filters[assignee]',
	'value' => elgg_extract('assignee', $filters),
	'options_values' => $assignee_options,
	'class' => 'mls',
));
$assignee .= '</div>';

echo $assignee;

// footer
$footer = '<div class="elgg-foot">';
$footer .= elgg_view('input/submit', array(
	'value' => elgg_echo('filter'),
));
$footer .= elgg_view('output/url', array(
	'text' => elgg_echo('reset'),
	'href' => $base_url,
	'class' => 'elgg-button elgg-button-cancel',
));
$footer .= '</div>';

echo $footer;