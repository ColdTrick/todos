<?php
$translation = array(
	'todos' => "To-dos",
	
	'item:object:todolist' => "To-do list",
	'item:object:todoitem' => "To-do item",
	
	// settings
	'todos:settings:enable_personal' => "Enable to-dos for users",
	'todos:settings:enable_groups' => "Enable to-dos for groups",
	
	'todos:all:no_results' => "There are currently no to-do lists. You can add one to start writing to-dos.",
	'todos:assigned:no_results' => "No assigned to-do items could be found",
	'todos:assigned:closed' => "Recently closed assigned to-do items",
	
	'todos:filter:active' => "Active lists",
	'todos:filter:completed' => "Completed lists",
	'todos:filter:assigned' => "Assigned to me",
	'todos:filter:overdue' => "Overdue items",
	'todos:filter:assigned_per_user' => "Assigned per user",
	
	'todos:todolist:error:missing_container' => "Please supply a container to create the list in",
	'todos:todolist:add' => "Add to-do list",
	'todos:todolist:title' => "Enter the name for the to-do list",
	'todos:todolist:completed' => "Completed to-dos",

	'todos:todoitem:add' => "Add a to-do",
	'todos:todoitem:reopen' => "Reopen",
	'todos:todoitem:close' => "Close",
	'todos:todoitem:error:missing_container' => "Missing to-do list",
	'todos:todoitem:error:not_item' => "Please provide a to-do item",
	'todos:todoitem:error:assignee:too_many' => "A to-do item can only be assigned to 1 user",
	'todos:todoitem:error:assignee:no_user' => "A to-do item can only be assigned to a user",
	'todos:todoitem:error:assignee:access' => "Unable to assign %s because he/she can't access the to-do item",
	
	'todos:todoitem:title' => "Enter the title of the to-do",
	'todos:todoitem:description' => "Description",
	'todos:todoitem:tags' => "Tags",
	'todos:todoitem:assignee' => "Assignee",
	'todos:todoitem:due' => "Due",
	'todos:todoitem:attachment' => "Attachment",
	'todos:todoitem:attachment:upload' => "Upload attachment",
	
	'todos:todoitem:attach' => "Upload attachment for: %s",
	
	'todos:assigned_per_user:unassigned' => "Unassigned",
	
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
	'todos:widget:due:list_select' => "Show items from the to-do list",
	'todos:widget:due:list_select:all' => "All lists",
	
	'todos:widget:created:title' => "Recently created to-do items",
	'todos:widget:created:description' => "List recently created to-do items",
	'todos:widget:created:none' => "No recently added to-do items",
	
	'todos:widget:list:title' => "To-do list",
	'todos:widget:list:description' => "Show a specific to-do list",
	'todos:widget:list:list' => "Please select the list to display",
	'todos:widget:list:select' => "Select a list",
	'todos:widget:list:list_completed' => "List completed to-dos",
	'todos:widget:list:no_list' => "No to-do list selected",
	
	// filter options
	'todos:form:filters:show_completed' => "Show completed items",
	'todos:form:filters:date' => "Limit items based on due date",
	'todos:form:filters:date:all' => "Select a date",
	'todos:form:filters:date:today' => "Today",
	'todos:form:filters:date:tomorrow' => "Tomorrow",
	'todos:form:filters:date:overdue' => "In the past (overdue)",
	'todos:form:filters:date:range' => "Range",
	'todos:form:filters:date:range:from' => "From",
	'todos:form:filters:date:range:to' => "To",
	'todos:form:filters:assignee' => "Assignee",
	'todos:form:filters:assignee:all' => "Show all",
	'todos:form:filters:assignee:unassigned' => "Unassigned items",
	
	'todos:filters_toggle:show' => 'Show filter options',
	'todos:filters_toggle:hide' => 'Hide filter options',
	
	// groups
	'todos:group:tool_option' => "Enable to-dos",
	'todos:owner_block:group' => "Group to-dos",
	
	// personal
	'todos:owner_block:user' => "To-dos",
	
	// notifications
	'todos:notify:todoitem:unassinged:subject' => "To-do item '%s' is no longer assigned",
	'todos:notify:todoitem:unassinged:message' => "Hi,

%s unassigned the to-do item '%s' from %s.

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:assinged:subject' => "To-do item '%s' was assigned",
	'todos:notify:todoitem:assinged:message' => "Hi,

%s assigned the to-do item '%s' to %s.

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:reassinged:subject' => "To-do item '%s' was re-assigned",
	'todos:notify:todoitem:reassinged:message' => "Hi,

%s reassigned the to-do item '%s' from %s to %s.

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:unassinged:assignee:subject' => "To-do item '%s' is no longer assigned to you",
	'todos:notify:todoitem:unassinged:assignee:message' => "Hi,

%s unassigned the to-do item '%s' from you.

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:assinged:assignee:subject' => "To-do item '%s' was assigned to you",
	'todos:notify:todoitem:assinged:assignee:message' => "Hi,

%s assigned the to-do item '%s' to you.

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:completed:subject' => "To-do item '%s' was completed",
	'todos:notify:todoitem:completed:message' => "Hi,

%s marked the to-do item '%s' as completed.

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:reopen:subject' => "To-do item '%s' was reopened",
	'todos:notify:todoitem:reopen:message' => "Hi,

%s reopend the to-do item '%s'.

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:due:subject' => "To-do item '%s' got a new due date",
	'todos:notify:todoitem:due:message' => "Hi,

%s changed the due date of the to-do item '%s' to %s.

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:due_soon:subject' => "To-do item '%s' is due soon",
	'todos:notify:todoitem:due_soon:message' => "Hi,

The to-do item '%s' is due soon (%s).

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:due_yesterday:subject' => "To-do item '%s' was due yesterday",
	'todos:notify:todoitem:due_yesterday:message' => "Hi,

The to-do item '%s' was due yesterday (%s).

To view the to-do item click on the link
%s",
	
	'todos:notify:todoitem:delete:subject'  => "To-do item '%s' was removed",
	'todos:notify:todoitem:delete:message'  => "Hi,

%s removed the to-do item '%s'.
",

	// actions
	'todos:action:error:title' => "Please enter a title",
	
	'todos:action:todolist:edit:success' => "To-do list saved",
	'todos:action:todolist:edit:error' => "An error occured during the save of the to-do list",
	
	'todos:action:todoitem:edit:cant_write' => "You're not allowed to add to-do items to this list",
	'todos:action:todoitem:edit:assignee' => "You can only assign a todo item to one person",
	'todos:action:todoitem:edit:due_in_past' => "You can't select a due date in the past",
	
	'todos:action:todoitem:edit:success' => "To-do item saved",
	'todos:action:todoitem:edit:error' => "An error occured during the save of the to-do item",
	
	'todos:action:todoitem:toggle:reopened' => "The to-do item %s was reopend",
	'todos:action:todoitem:toggle:closed' => "The to-do item %s was completed",
	
	'todos:action:todoitem:delete_attachment:error' => "Unable to delete the attachment",
	'todos:action:todoitem:delete_attachment:success' => "The attachment was deleted",
	
	'todos:action:todoitem:attachment:error:file' => "Please upload a file",
	'todos:action:todoitem:attachment:success' => "The attachment was uploaded successfully",
	
	// river
	'river:create:object:todoitem' => '%s added a new to-do %s',
	'river:close:object:todoitem' => '%s closed to-do %s',
	'river:reopen:object:todoitem' => '%s reopened to-do %s',
);

add_translation('en', $translation);
