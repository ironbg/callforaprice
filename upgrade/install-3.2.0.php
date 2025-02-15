<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_2_0($object)
{
	$object->uninstallOverrides();
	$object->installOverrides();
	return $object->upgradeCallForPrice_3_2_0();
}


