<?php

echo elgg_view_form('todos/todoitem/edit', array('id' => 'todos-todoitem-edit'));

// datepicker lightbox and userpicker fix
?>
<script>
	elgg.ui.initDatePicker();

	elgg.userpicker.init();
</script>