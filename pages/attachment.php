<?php

$guid = (int) get_input('guid');
$entity = get_entity($guid);
if (empty($entity) || !elgg_instanceof($entity, 'object', TodoItem::SUBTYPE)) {
	forward(REFERER);
}

$filename = get_input('filename');
if (empty($filename)) {
	forward(REFERER);
}

$contents = $entity->getAttachment($filename);
if (empty($contents)) {
	header('"HTTP/1.1 404 Not Found');
	exit();
}

header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Disposition: Attachment; filename=' . $filename);
header('Content-Type: application/octet-stream');
header('Content-Length: ' . strlen($contents));

echo $contents;
