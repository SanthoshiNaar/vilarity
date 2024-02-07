<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */

// Profile module configuration:
	_R::$_->MODULES_PROFILE = array
	(
		'vilarity' => array
		(
			'enabled'   => TRUE,
			'autoload'  => TRUE,
			'path'      => _R::$_->PROFILE_PATH.
			               _R::$_->MODULE_BASEPATH.'vilarity',
			'extension' => array
			(
				'enabled' => TRUE,
				'type'    => 'custom',
				'title'   => 'Vilarity',
				'desc'    => '',
				'icon'    => 'icons/16x16/vilarity.png',
				'admin'   => 'admin/vilarity',
			),
		)
	);

// Add the profile module configuration to the system.
	Module::Config(_R::$_->MODULES_PROFILE);
?>
