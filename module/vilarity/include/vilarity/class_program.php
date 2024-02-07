<?php
/**
 *
 */

// Use the "vilarity" module namespace:
namespace vilarity;

// Include Protection:
if (!defined('ALLOW_INCLUDE_FILES')) die;

// Import namespaces:
use \_LOG   as _LOG;
use \_R     as _R;
use \_T     as _T;
use \Log    as Log;
use \ObjectBase as ObjectBase;

// Class vilarity\Program:
class Program
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
 * Program::Action
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
 * Program::Save
 ***************************************************************************/
	public function Save($saveMode = self::SAVE_DEFAULT)
	{
	// Object validation.
		if ($this->title       == '' &&
			$this->description == '')
		{
		// Log it.
			_LOG::$_->Write('Cannot save a program without a title or description.');
			return FALSE;
		}

	// Provide a unique program key if one isn't set.
		if ($this->key == '')
		{	$this->key = uniqid('program-');	}

	// Sanitize the key.
		$this->SanitizeKey();

	// Default the card color to white if nothing is defined.
		if ($this->cardColor == '')
		{	$this->cardColor = 'white';	}

	// Call the base class method.
		return parent::Save($saveMode);

	// TODO: Process child nodes and fix errors?
	}

/***************************************************************************
 * Program::Delete
 ***************************************************************************/
	public function Delete()
	{
	// Log it.
		_LOG::$_->Write('Deleting Program "%s" (Key: "%s", ID: %s)',
		                NULL,
		                $this->title,$this->key,$this->GetID());

	// Delete the program nodes.
		$conds = array
		(
			ObjectBase::Where('program','=',$this->GetID()),
		//	ObjectBase::Where('parent' ,'=',0)
		);
		$qObj = new ProgramNode();
		while ($objs = $qObj->Find(0,100,$conds))
		  foreach ($objs as $obj)
		{	$obj->Delete();	}

	// Call the base class method.
		return parent::Delete();
	}

/***************************************************************************
 * Program::GetNodeTree
 ***************************************************************************/
	public function GetNodeTree($bForce = FALSE)
	{
	// Use cache?
		if (0 &&
			!$bForce &&
			$this->childNodes) {
			return $this->childNodes;
		}
		$this->childNodes = array();

	// A database ID is needed to have child nodes.
		if ($this->GetID() == 0)
		{	return $this->childNodes;	}

	// Fetch the nodes.
		$conds = array
		(
			ObjectBase::Where('program','=',$this->GetID()),
		);
		$qObj = new ProgramNode();
		$objs = $qObj->Find(0,5000,$conds);

	// Build a node ID map.
		$nodesById = array();
		foreach ($objs as $programNodeObj)
		{
			$programNodeObj->program = $this;
			$nodesById[$programNodeObj->GetID()] = $this;
		}

	// Process the nodes.
		foreach ($objs as $programNodeObj)
		{
		// Note: There is no way to destinguish between a parent that
		//       does not exist and a node that doesn't have a parent
		//       because both will return an object with objId==0.
		//       Test the "type" property instead. Only a "series"
		//       may have a program as the parent (no programNode parent).
		//	$parentIdDB    = $programNodeObj->Get('parent',FALSE);
			$parentNodeObj = $programNodeObj->parent;
			$parentId      = $parentNodeObj->GetID();
		//	if ($parentIdDB != 0 && $parentId == 0)
			if ($parentId == 0 && $programNodeObj->type != 'series')
			{
				_LOG::$_->Write('Orphaned Program Node detected (ID: %s)',
				                NULL,$programNodeObj->GetID());
				continue;
			}

		// Add the node to the tree.
			if ($parentId == 0)
			{
				$this->childNodes[] = $programNodeObj;
			}
			else
			{
				$programNodeObj->parent = $nodesById[$parentId];
				$programNodeObj->parent->childNodes[] = $programNodeObj;
			}
		}
		return $this->childNodes;
	}

/***************************************************************************
 * Program::GetNodeTree
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
Program::$objOptions = array
(
// Use an object definition.
	'definition' => 'vilarity/program',
);

?>
