<?php
/**
 *
 */

// Use the "vilarity" module namespace:
namespace vilarity;

// Include Protection:
if (!defined('ALLOW_INCLUDE_FILES')) die;

// Import namespaces:
use \RecursiveDirectoryIterator as RecursiveDirectoryIterator;
use \RecursiveIteratorIterator  as RecursiveIteratorIterator;
use \FilesystemIterator         as FilesystemIterator;

use \_LOG      as _LOG;
use \_PROFILE  as _PROFILE;
use \_R        as _R;
use \_T        as _T;
use \Event     as Event;
use \Kernel    as Kernel;
use \Log       as Log;
use \Module    as Module;
use \ModuleWeb as ModuleWeb;

// class vilarity\ModuleVilarity:
class ModuleVilarity
      extends ModuleWeb
{
// Constants:
	const MODULE_NAME = 'vilarity';

// Static members:

/***************************************************************************
 * IModule Methods
 ***************************************************************************/
	public static function InitModule()
	{
	// Call the base class method.
		parent::InitModule(self::MODULE_NAME);

	// Listen for events.
		Kernel::AddEventListener('Event','xhtml_head_script',
		                         array(__CLASS__,'OnEventXHTMLHeadScript'));
		Kernel::AddEventListener('Event','xhtml_head_style',
		                         array(__CLASS__,'OnEventXHTMLHeadStyle'));

		Kernel::AddEventListener('SitemapEvent','bind#vilarity/app',
		                         array(__CLASS__,'OnSitemapEventBindVilarityApp'));

		Kernel::AddEventListener('SitemapEvent','bind#vilarity/api',
		                         array(__CLASS__,'OnSitemapEventBindVilarityAPI'));

		Kernel::AddEventListener('SitemapEvent','bind#vilarity/admin',
		                         array(__CLASS__,'OnSitemapEventBindVilarityBackend'));
		Kernel::AddEventListener('SitemapEvent','bind:after#admin',
		                         array(__CLASS__,'OnSitemapEventBindAfterBackendVilarity'));

	// Load the submodules.
		$subModules = array_keys(_R::$_->VILARITY_MODULES);
		foreach ($subModules as $modName)
		{
		// Load it.
			if (Module::Load($modName) == FALSE)
			{	return FALSE;	}
		}

	// Create the Vilarity cache and data folders.
		$dirs = array
		(
			'cache' => self::GetCacheDirectory(/*$objType =*/ FALSE,/*$objId =*/ FALSE,/*$bAutoCreate =*/ TRUE),
			'data'  => self::GetDataDirectory (/*$objType =*/ FALSE,/*$objId =*/ FALSE,/*$bAutoCreate =*/ TRUE),
		);
		foreach ($dirs as $dir)
		{
			if (file_exists($dir) == FALSE)
			{	mkdir($dir,0770,TRUE);	}
			chmod($dir,0770);
		}

	// Purge the Vilarity cache on startup.
		if ($dirs['cache'] != '' && file_exists($dirs['cache']))
		{
			_LOG::$_->Write('Purging "vilarity" cache (%s*)...',NULL,$dirs['cache']);
			$di = new RecursiveDirectoryIterator($dirs['cache'],FilesystemIterator::SKIP_DOTS);
			$ri = new RecursiveIteratorIterator ($di,RecursiveIteratorIterator::CHILD_FIRST);
			foreach ($ri as $file)
			{
				$file->isDir() ? rmdir($file->getRealPath()) :
				                unlink($file->getRealPath());
			}
		}
	}

/***************************************************************************
 * Event Handlers
 ***************************************************************************/
	public static function OnEventXHTMLHeadScript($eventObj)
	{
	// We only need to handle the opening tag.
		if ($eventObj->GetType() == Event::CLOSE)
		{	return;	}

	// Include our script.
		_T::$_->IncludeTemplate('vilarity_script');
	}
	public static function OnEventXHTMLHeadStyle($eventObj)
	{
	// We only need to handle the opening tag.
		if ($eventObj->GetType() == Event::CLOSE)
		{	return;	}

	// Include our style sheet.
		_T::$_->IncludeTemplate('vilarity_style');
	}

/***************************************************************************
 * SitemapEvent Handlers
 ***************************************************************************/
	public static function OnSitemapEventBindVilarityApp($eventObj)
	{
	// Load the sitemap template.
		$sitemapNode = $eventObj->GetNode();
		$sitemapNode->MergeBinding('vilarity/app');
		return TRUE;
	}

	public static function OnSitemapEventBindVilarityAPI($eventObj)
	{
	// Load the sitemap template.
		$sitemapNode = $eventObj->GetNode();
		$version     = '1';
		$sitemapNode->MergeBinding('vilarity/api/v'.(int)$version);
		return TRUE;
	}

	public static function OnSitemapEventBindVilarityBackend($eventObj)
	{
	// Load the sitemap template.
		$sitemapNode = $eventObj->GetNode();
		$sitemapNode->MergeBinding('vilarity/admin');
		return TRUE;
	}
	public static function OnSitemapEventBindAfterBackendVilarity($eventObj)
	{
	// Load the sitemap template.
		$sitemapNode = $eventObj->GetNode();
		$sitemapNode->MergeBinding('vilarity/admin,vilarity');
		return TRUE;
	}

/***************************************************************************
 * Get
 ***************************************************************************/
	public static function GetCacheDirectory($objType = FALSE,$objId = FALSE,$bAutoCreate = FALSE)
	{
		$dir = _PROFILE::$_->GetPath().
		       _R::$_->CACHE_BASEPATH.
		       'vilarity'.DIRECTORY_SEPARATOR;
		if ($objType != FALSE)
		{	$dir     .= strtolower($objType).DIRECTORY_SEPARATOR;	}
		if ($objId   != FALSE)
		{	$dir     .= strtolower($objId).DIRECTORY_SEPARATOR;		}
		if ($bAutoCreate)
		{
			if (file_exists($dir) == FALSE)
			{	mkdir($dir,0770,TRUE);	}
			chmod($dir,0770);
		}
		return $dir;
	}

	public static function GetDataDirectory($objType = FALSE,$objId = FALSE,$bAutoCreate = FALSE)
	{
		$dir = _PROFILE::$_->GetPath().
		       _R::$_->DATA_BASEPATH.
		       'vilarity'.DIRECTORY_SEPARATOR;
		if ($objType != FALSE)
		{	$dir     .= strtolower($objType).DIRECTORY_SEPARATOR;	}
		if ($objId   != FALSE)
		{	$dir     .= strtolower($objId).DIRECTORY_SEPARATOR;		}
		if ($bAutoCreate)
		{
			if (file_exists($dir) == FALSE)
			{	mkdir($dir,0770,TRUE);	}
			chmod($dir,0770);
		}
		return $dir;
	}
}

?>
