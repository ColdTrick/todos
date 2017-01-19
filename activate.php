<?php

if (get_subtype_id('object', TodoList::SUBTYPE)) {
	update_subtype('object', TodoList::SUBTYPE, "TodoList");
} else {
	add_subtype('object', TodoList::SUBTYPE, "TodoList");
}

if (get_subtype_id('object', TodoItem::SUBTYPE)) {
	update_subtype('object', TodoItem::SUBTYPE, "TodoItem");
} else {
	add_subtype('object', TodoItem::SUBTYPE, "TodoItem");
}
