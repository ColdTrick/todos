<?php
?>
.elgg-menu-todos {
	display: none;
}

.todos-list-item > h3 {
	display: inline;
}

.todos-list-item:hover .elgg-menu-todos {
	display: inline-block;
}

.elgg-menu.elgg-menu-todos > li {
	margin-left: 5px;
}

.elgg-menu.elgg-menu-todos > li,
.elgg-menu.elgg-menu-todos > li > a {
	vertical-align: inherit;
}

.elgg-menu-todos a {
	color: #999;
}

.todos-list {
	border-top: none;
}

.todos-list,
.todos-list li {
	border: none;
}

.todos-list.todos-list-todoitem {
	padding-top: 1px;
	padding-bottom: 1px;
}

.todos-list-item .elgg-image-block .elgg-body > a {
	color: inherit;
}

.todos-list-item-completed.elgg-image-block .elgg-body > a {
	color: #999;
}

#todos-todoitem-edit,
#todos-todoitem-attach {
	max-width: 330px;
}

.todos-item-comments {
	font-size: 0.9em;
    padding: 2px 8px;
	background: #71b9f7;
	color: #FFF;
	border-radius: 8px;
	white-space: nowrap;
}

.todos-item-info {
	font-size: 0.9em;
    padding: 2px 8px;
	background: #eee;
	color: #999;
	border-radius: 8px;
}

.todos-item-info a {
	color: #999;
}
