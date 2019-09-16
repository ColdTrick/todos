<?php

$filters = (array) elgg_extract('filters', $vars, array());
$base_url = elgg_extract('base_url', $vars);
$container = elgg_extract('container', $vars);

// show completed items
echo elgg_view_field([
	'#type' => 'checkboxes',
	'name' => 'filters[show_completed]',
	'value' => elgg_extract('show_completed', $filters),
	'options' => [
		elgg_echo('todos:form:filters:show_completed') => 1,
	],
]);

// date filter
$date_options = [
	'' => elgg_echo('todos:form:filters:date:all'),
	'today' => elgg_echo('todos:form:filters:date:today'),
	'tomorrow' => elgg_echo('todos:form:filters:date:tomorrow'),
	'overdue' => elgg_echo('todos:form:filters:date:overdue'),
	'range' => elgg_echo('todos:form:filters:date:range'),
];

$range_class = ['plm', 'todos-form-filters-range'];
if (elgg_extract('date', $filters) !== 'range') {
	$range_class[] = 'hidden';
}

$date = '<div>';
$date .= '<label for="todos-filters-date">' . elgg_echo('todos:form:filters:date') . '</label>';
$date .= elgg_view('input/dropdown', [
	'id' => 'todos-filters-date',
	'name' => 'filters[date]',
	'value' => elgg_extract('date', $filters),
	'options_values' => $date_options,
	'class' => 'mls',
]);
$date .= '<div ' . elgg_format_attributes(['class' => $range_class]) . '>';
$date .= '<label>' . elgg_echo('todos:form:filters:date:range:from') . '</label>';
$date .= elgg_view('input/datepicker', [
	'name' => 'filters[range_lower]',
	'value' => elgg_extract('range_lower', $filters),
	'timestamp' => true,
]);
$date .= '<label>' . elgg_echo('todos:form:filters:date:range:to') . '</label>';
$date .= elgg_view('input/datepicker', [
	'name' => 'filters[range_upper]',
	'value' => elgg_extract('range_upper', $filters),
	'timestamp' => true,
]);
$date .= '</div>';
$date .= '</div>';

echo $date;

// assignee filter
echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('todos:form:filters:assignee'),
	'id' => 'todos-filters-assignee',
	'name' => 'filters[assignee]',
	'value' => elgg_extract('assignee', $filters),
	'options_values' => todos_get_assignee_filter_for_container($container),
]);

// footer
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('filter'),
]);

$footer .= elgg_view('output/url', [
	'text' => elgg_echo('reset'),
	'href' => $base_url,
	'class' => 'elgg-button elgg-button-cancel',
]);

elgg_set_form_footer($footer);