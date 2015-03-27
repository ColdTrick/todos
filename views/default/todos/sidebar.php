<?php

elgg_push_context("todos_sidebar");

echo elgg_view('todos/sidebar/due');

echo elgg_view('todos/sidebar/closed');

echo elgg_view('todos/sidebar/created');

elgg_pop_context();