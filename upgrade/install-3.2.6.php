<?php

if (!defined('_PS_VERSION_'))
  exit;

function upgrade_module_3_2_6($object)
{
  return $object->upgradeCallForPrice_3_2_6();
}