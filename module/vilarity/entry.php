<?php
/**
 * entry.php - Entry point for the "vilarity" module.
 */

// Use the "vilarity" module namespace:
namespace vilarity;

// Import namespaces:
use \_LOG   as _LOG;
use \_R     as _R;
use \Log    as Log;
use \Module as Module;

// Include Protection:
if (!defined('ALLOW_INCLUDE_FILES')) die;

// ----------

// The "vilarity" module requires PHP 5.3+ for namespacing support.
	if (version_compare(PHP_VERSION,'5.3','<'))
	{
	// Log it.
	// TODO: Locale.
		_LOG::$_->Write('The "vilarity" module requires PHP 5.3+ for namespacing '.
		                'support (Detected PHP %s).',
		                Log::ERROR,PHP_VERSION);
		return FALSE;
	}

// Shim ObjectBase, which is not present in older runtime environments.
	if (PHP_VERSION_ID < 70200 &&
		class_exists('ObjectBase',FALSE) === FALSE)
	{
		class_alias('Object','ObjectBase');
	}

// Include the required modules.
	$modNames = array();
	if ($modNames &&
		Module::Load($modNames) == FALSE)
	{
		_LOG::$_->Write('One or more modules required for module '.
		                '"vilarity" failed to load.',
		                Log::ERROR);
		return FALSE;
	}

// Include files relative to the module folder.
	$modDir = Module::GetDirectory('vilarity');

// Include the required files.
	require $modDir.'include/class_module_vilarity.php';

	require $modDir.'include/vilarity/class_account.php';
	require $modDir.'include/vilarity/class_group.php';
	require $modDir.'include/vilarity/class_group_member.php';
	require $modDir.'include/vilarity/class_program.php';
	require $modDir.'include/vilarity/class_program_node.php';
	require $modDir.'include/vilarity/class_program_record.php';

// Declare the name of this module's entry class.
	return 'vilarity\ModuleVilarity';
?>
