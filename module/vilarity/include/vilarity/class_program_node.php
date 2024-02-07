<?php
/**
 *
 */

// Use the "vilarity" module namespace:
namespace vilarity;

// Include Protection:
if (!defined('ALLOW_INCLUDE_FILES')) die;

// Import namespaces:
use \DOMDocument as DOMDocument;
use \DOMXPath    as DOMXPath;

use \_LOG   as _LOG;
use \_R     as _R;
use \_T     as _T;
use \Log    as Log;
use \ObjectBase as ObjectBase;

// Class vilarity\ProgramNode:
class ProgramNode
      extends ObjectBase
{
// Constants:
	const STATUS_INACTIVE = 0x00;
	const STATUS_ACTIVE   = 0x01;

// Static members:
	public static $objOptions; // default options (defined below)

// Class members:
	public $childNodes = array();

// Construct/Destruct
	public function __construct($options = array())
	{
	// Object options:
		$options = array_merge_recursive_distinct((array)self::$objOptions,(array)$options);

	// Call the base class constructor.
		parent::__construct($options);
	}
	public function __destruct()
	{
	// Call the base class destructor.
		parent::__destruct();
	}

/***************************************************************************
 * ProgramNode::Action
 ***************************************************************************/
	public function Action($action)
	{
	// Perform the requested action.
		switch ($action)
		{
		case 'activate' :
			$this->status = self::STATUS_ACTIVE; // (active)
			return $this->Save();
		case 'deactivate' :
			$this->status = self::STATUS_INACTIVE; // (inactive)
			return $this->Save();
		}

	// Let the parent method try.
		return parent::Action($action);
	}

/***************************************************************************
 * ProgramNode::Save
 ***************************************************************************/
	public function Save($saveMode = self::SAVE_DEFAULT)
	{
	// Object validation.
		if ($this->title       == '' &&
			$this->description == '')
		{
		// Log it.
			_LOG::$_->Write('Cannot save a program node without a title or description.');
			return FALSE;
		}

	// Provide a unique program key if one isn't set.
		if ($this->key == '')
		{	$this->key = uniqid('node-');	}

	// Sanitize the key.
		$this->SanitizeKey();

	// Set the node type.
		if ($this->type == '')
		{
			if ($this->parent->GetID() == 0)
			{
				$this->type = 'series';
			}
			else switch ($this->parent->type)
			{
			case ''       :
			case 'series' :
				$this->type = 'session';
				break;
			}
		}

	// Define the depth.
		$depth = 1; // start at 1 because the "program" node does not exist yet
		            // but could in the future.
		if (($parentNode = $this->parent) &&
			$parentNode->GetID() != 0)
		{
			$depth = $parentNode->depth + 1;
		}
		$this->depth = $depth;

	// Fetch and cache some data for external resources.
		$this->ResolveResource();

	// Call the base class method.
		if ($ret = parent::Save($saveMode))
		{
		// Delete the Program cache so it will regenerate.
			$programId = (int)$this->program->GetID();
			$cacheDir  = ModuleVilarity::GetCacheDirectory('programs',$programId);
			$cachePath = $cacheDir.'nodes.json';
			if (file_exists($cachePath))
			{	unlink($cachePath);	}
		}
		return $ret;

	// TODO: Process child nodes and fix errors?
	}

/***************************************************************************
 * ProgramNode::Delete
 ***************************************************************************/
	public function Delete()
	{
	// Log it.
		_LOG::$_->Write('Deleting Program Node "%s" (Key: "%s", ID: %s) from Program "%s" (Key: "%s", ID: %s)',
		                NULL,
		                $this->title,$this->key,$this->GetID(),
		                $this->program->title,$this->program->key,$this->program->GetID());

	// Delete the child nodes.
		$conds = array
		(
		//	ObjectBase::Where('program','=',$this->program->GetID()),
			ObjectBase::Where('parent' ,'=',$this->GetID())
		);
		$qObj = new ProgramNode();
		while ($objs = $qObj->Find(0,100,$conds))
		  foreach ($objs as $obj)
		{	$obj->Delete();	}

	// TODO: Delete ProgramRecords?

	// Call the base class method.
		if ($ret = parent::Delete())
		{
		// Delete the Program cache so it will regenerate.
			$programId = (int)$this->program->GetID();
			$cacheDir  = ModuleVilarity::GetCacheDirectory('programs',$programId);
			$cachePath = $cacheDir.'nodes.json';
			if (file_exists($cachePath))
			{	unlink($cachePath);	}
		}

		return $ret;
	}

/***************************************************************************
 * ProgramNode::LoadChildNodes()
 ***************************************************************************/
	public function LoadChildNodes($bForce = FALSE)
	{
	// Use cache?
		if (0 &&
			!$bForce &&
			$this->childNodes) {
			return $this->childNodes;
		}
		$this->childNodes = array();

	// A database ID is needed to have child nodes.
		if ($this->program->GetID() == 0 ||
			$this->GetID() == 0)
		{	return $this->childNodes;	}

	// Fetch the nodes.
		$conds = array
		(
			ObjectBase::Where('program','=',$this->program->GetID()),
			ObjectBase::Where('parent' ,'=',$this->GetID())
		);
		$qObj = new ProgramNode();
		$objs = $qObj->Find(0,100,$conds);

	// Process the nodes.
		foreach ($objs as $programNodeObj)
		{
			$programNodeObj->program = $this->program;
			$programNodeObj->parent  = $this;
		}

		$this->childNodes = $objs;
		return $this->childNodes;
	}

	public function ResolveResource()
	{
		_LOG::$_->Write('Considering ASADSD "%s"',NULL,$this->description);

	// Convert supported external resource URLs into cached data about the resource.
		if ($this->type != 'point')
			return;

		if ($this->description == '')
			return;
		if (preg_match('/^{/',$this->description))
			return; // already converted.

		switch ($this->subType)
		{
		case 'audio' :
			if (preg_match('/^https?:\/\/podcast\\.ausha\\.co\\/.+$/',$this->description) == FALSE)
				break;
			_LOG::$_->Write('Processing "%s"',NULL,$this->description);

		// Load the remote page.
			$url         = $this->description;
			$htmlContent = $this->FetchURL($url);
			$doc = new DOMDocument();
			$doc->loadHTML($htmlContent);
			$xpath = new DOMXPath($doc);

		// Get the content title.
			$nodes = $xpath->query('//head/meta[@property="twitter:description"]');
			if ($nodes->length == 0)
			{
				_LOG::$_->Write('Could not find "twitter:description" meta tag for "%s"',NULL,$url);
				break;
			}
			else
			{	$this->title = $nodes[0]->getAttribute('content');	}

		// Get the audio ID and embed URL.
			$nodes = $xpath->query('//head/meta[@property="twitter:player"]');
			if ($nodes->length == 0)
			{
				_LOG::$_->Write('Could not find "twitter:player" meta tag for "%s"',NULL,$url);
				break;
			}
			$playerLink = $nodes[0]->getAttribute('content');
			_LOG::$_->Write('Found player link "%s" for "%s"',NULL,$playerLink,$url);

			if (preg_match('/(https?:\\/\\/player\\.ausha\\.co\\/index\\.html\\?podcastId=([^&]+))(?:&|$)/',$playerLink,$matches) == FALSE)
			{
				_LOG::$_->Write('Failed to understand player link URL "%s" for "%s"',NULL,$playerLink,$url);
				break;
			}
			$embedURL = $matches[1];
			$audioId  = $matches[2];

			$data = json_encode(array
			(
				'url'      => $url,
				'provider' => 'ausha',
				'service'  => 'podcast',
				'audioId'  => $audioId,
				'embedURL' => $embedURL,
			));
			_LOG::$_->Write('Recording meta data for "%s": "%s"',NULL,$url,$data);

			$this->description = $data;
			break;

		default :
			return;
		}
	}

	private function FetchURL($url)
	{
		$user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36';

		$options = array
		(
			CURLOPT_CUSTOMREQUEST  => "GET",
			CURLOPT_POST           => FALSE,
			CURLOPT_USERAGENT      => $user_agent,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HEADER         => FALSE,
			CURLOPT_FOLLOWLOCATION => TRUE,
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_MAXREDIRS      => 10,
		);

		$ch = curl_init($url);
		curl_setopt_array($ch,$options);
		return curl_exec($ch);
	}

/***************************************************************************
 * ProgramNode::GetNodeTree
 ***************************************************************************/
	public function SanitizeKey()
	{
	// Remove invalid key characters.
		$key    = $this->key;
		$keyNew = strtolower($key);
		$keyNew = preg_replace('/[^a-z0-9\-]/','-',$keyNew);
		$keyNew = preg_replace('/-{2,}/'      ,'-',$keyNew);
		$keyNew = preg_replace('/^-|-$/'      , '',$keyNew);
		if ($key != $keyNew)
		{
			$this->key = $keyNew;
			_LOG::$_->Write('Fixed invalid key: "%s" to "%s"',NULL,$key,$keyNew);
		}
		return $keyNew;
	}

// EXPORT:
	public function ExportProperties($target = FALSE)
	{
	// Get the object properties.
		$objProperties = $this->GetProperties();
		if ($objProperties == FALSE)
		{	return array();	}

	// Expand the objects.
		$objProperties['program' ] = $this->program ->GetUUID();
		$objProperties['parent'  ] = $this->parent  ->GetUUID();
		$objProperties['next'    ] = $this->next    ->GetUUID();
		$objProperties['previous'] = $this->previous->GetUUID();
	// TODO:?

		return $objProperties;
	}
}

/***************************************************************************
 * Static Init
 ***************************************************************************/
// Define the common options.
ProgramNode::$objOptions = array
(
// Use an object definition.
	'definition' => 'vilarity/program_node',
);

?>
