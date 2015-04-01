<?php
$translation = array(
	'todos' => "To-dos",
	
	'item:object:todolist' => "To-do list",
	'item:object:todoitem' => "To-do item",
	
	// settings
	'todos:settings:enable_groups' => "Enable to-dos for groups",
	
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
	'todos:todoitem:reopen' => "Reopen",
	'todos:todoitem:close' => "Close",
	'todos:todoitem:error:missing_container' => "Missing to-do list",
	
	'todos:todoitem:title' => "Enter the title of the to-do",
	'todos:todoitem:assignee' => "Assignee",
	'todos:todoitem:due' => "Due",
	
	// sidebar
	'todos:sidebar:todoitems_due:title' => "Due to-do items",
	
	'todos:sidebar:todoitems_closed:title' => "Recently closed to-do items",
	
	'todos:sidebar:todoitems_created:title' => "Recently added to-do items",
	
	// widgets
	'todos:widget:assigned:title' => "Assigned to-do items",
	'todos:widget:assigned:description' => "List to-do items assigned to you",
	
	'todos:widget:closed:title' => "Recently closed to-do items",
	'todos:widget:closed:description' => "List recently closed to-do items",
	'todos:widget:closed:none' => "No recently closed to-do items",
	
	'todos:widget:due:title' => "Due to-do items",
	'todos:widget:due:description' => "List due to-do items",
	'todos:widget:due:none' => "No to-do items are due!",
	
	'todos:widget:created:title' => "Recently created to-do items",
	'todos:widget:created:description' => "List recently created to-do items",
	'todos:widget:created:none' => "No recently added to-do items",
	
	'todos:widget:list:title' => "To-do list",
	'todos:widget:list:description' => "Show a specific to-do list",
	'todos:widget:list:list' => "Please select the list to display",
	'todos:widget:list:select' => "Select a list",
	'todos:widget:list:no_list' => "No to-do list selected",
	
	// groups
	'todos:group:tool_option' => "Enable to-dos",
	'todos:owner_block:group' => "Group to-dos",
	
	// notifications
	'todos:notify:todoitem:unassinged:subject' => "To-do item %s is no longer assigned",
	'todos:notify:todoitem:unassinged:message' => "Hi,

The to-do item %s is no longer assigned to %s.

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:assinged:subject' => "To-do item %s was assigned",
	'todos:notify:todoitem:assinged:message' => "Hi,

The to-do item %s was assigned to %s.

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:reassinged:subject' => "To-do item %s was re-assigned",
	'todos:notify:todoitem:reassinged:message' => "Hi,

The to-do item %s was re-assigned from %s to %s.

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:completed:subject' => "To-do item %s was completed",
	'todos:notify:todoitem:completed:message' => "Hi,

The to-do item %s was completed.

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:reopen:subject' => "To-do item %s was reopened",
	'todos:notify:todoitem:reopen:message' => "Hi,

The to-do item %s was reopened.

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:due:subject' => "To-do item %s got a new due date",
	'todos:notify:todoitem:due:message' => "Hi,

The to-do item %s got the new due date %s.

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:due_soon:subject' => "To-do item %s is due soon",
	'todos:notify:todoitem:due_soon:message' => "Hi,

The to-do item %s is due soon (%s).

To view the to-do item click on the link
%s",

	// actions
	'todos:action:error:title' => "Please enter a title",
	
	'todos:action:todolist:edit:success' => "To-do list saved",
	'todos:action:todolist:edit:error' => "An error occured during the save of the to-do list",
	
	'todos:action:todoitem:edit:cant_write' => "You're not allowed to add to-do items to this list",
	'todos:action:todoitem:edit:assignee' => "You can only assign a todo item to one person",
	
	'todos:action:todoitem:edit:success' => "To-do item saved",
	'todos:action:todoitem:edit:error' => "An error occured during the save of the to-do item",
	
	// river
	'river:create:object:todoitem' => '%s added a new to-do %s',
	'river:close:object:todoitem' => '%s closed to-do %s',
	'river:reopen:object:todoitem' => '%s reopened to-do %s',
);

add_translation('en', $translation);
