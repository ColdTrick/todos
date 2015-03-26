<?php

elgg_push_context("todos_sidebar");

echo elgg_view('todos/sidebar/todoitems_due');

echo elgg_view('todos/sidebar/todoitems_closed');

echo elgg_view('todos/sidebar/todoitems_created');

elgg_pop_context();