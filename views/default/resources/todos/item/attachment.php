<?php

use Elgg\BadRequestException;
use Elgg\EntityNotFoundException;

$guid = (int) get_input('guid');
elgg_entity_gatekeeper($guid, 'object', \TodoItem::SUBTYPE);
$entity = get_entity($guid);

$filename = get_input('filename');
if (empty($filename)) {
	throw new BadRequestException();
}

$contents = $entity->getAttachment($filename);
if (empty($contents)) {
	throw new EntityNotFoundException();
}

header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Disposition: Attachment; filename=' . $filename);
header('Content-Type: application/octet-stream');
header('Content-Length: ' . strlen($contents));

echo $contents;
