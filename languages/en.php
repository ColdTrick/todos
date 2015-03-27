<?php
$translation = array(
	'todos' => "To-dos",
	
	'item:object:todolist' => "To-do list",
	'item:object:todoitem' => "To-do item",
	
	'todos:all:no_results' => "There are currently no to-do lists. You can add one to start writing to-dos.",
	'todos:assigned:no_results' => "No assigned to-do items could be found",
	'todos:assigned:closed' => "Recently closed assigned to-do items",
	
	'todos:filter:active' => "Active lists",
	'todos:filter:completed' => "Completed lists",
	'todos:filter:assigned' => "Assigned to-dos",
	
	'todos:todolist:error:missing_container' => "Please supply a container to create the list in",
	'todos:todolist:add' => "Add to-do list",
	'todos:todolist:title' => "Enter the name for the to-do list",

	'todos:todoitem:add' => "Add a to-do",
	'todos:todoitem:error:missing_container' => "Missing to-do list",
	
	'todos:todoitem:title' => "Enter the title of the to-do",
	'todos:todoitem:assignee' => "Assignee",
	'todos:todoitem:due' => "Due",
	
	// sidebar
	'todos:sidebar:todoitems_due:title' => "Due to-do items",
	'todos:sidebar:todoitems_due:none' => "No to-do items are due!",
	
	'todos:sidebar:todoitems_closed:title' => "Recently closed to-do items",
	'todos:sidebar:todoitems_closed:none' => "No recently closed to-do items",
	
	'todos:sidebar:todoitems_created:title' => "Recently added to-do items",
	'todos:sidebar:todoitems_created:none' => "No recently added to-do items",
	
	// widgets
	'todos:widget:assigned:title' => "Assigned to-do items",
	'todos:widget:assigned:description' => "List to-do items assigned to you",
	
	// actions
	'todos:action:error:title' => "Please enter a title",
	
	'todos:action:todolist:edit:success' => "Todolist saved",
	'todos:action:todolist:edit:error' => "An error occured during the save of the todolist",
	
	'todos:action:todoitem:edit:cant_write' => "You're not allowed to add todo items to this list",
	'todos:action:todoitem:edit:assignee' => "You can only assign a todo item to one person",
	
	'todos:action:todoitem:edit:success' => "Todoitem saved",
	'todos:action:todoitem:edit:error' => "An error occured during the save of the todoitem",
	
);

add_translation('en', $translation);