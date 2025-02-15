<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_1_2($object)
{
	return $object->upgradeCallForPrice_3_1_2();
}


