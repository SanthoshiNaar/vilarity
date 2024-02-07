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

// Class vilarity\ProgramRecord:
class ProgramRecord
      extends ObjectBase
{
// Constants:
	const STATUS_INCOMPLETE = 0x00;
	const STATUS_COMPLETE   = 0x01;

// Static members:
	public static $objOptions; // default options (defined below)

// Class members:

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
 * ProgramRecord::Action
 ***************************************************************************/
	public function Action($action)
	{
	// Perform the requested action.
		switch ($action)
		{
		case 'complete' :
			$this->status = self::STATUS_COMPLETE;
			return $this->Save();
		case 'incomplete' :
			$this->status = self::STATUS_INCOMPLETE;
			return $this->Save();
		}

	// Let the parent method try.
		return parent::Action($action);
	}

/***************************************************************************
 * ProgramRecord::Save
 ***************************************************************************/
	public function Save($saveMode = self::SAVE_DEFAULT)
	{
	// Manage progress and status.
		switch ($this->status)
		{
		case ProgramRecord::STATUS_COMPLETE :
			$this->progress = 1.0;
			break;

		case ProgramRecord::STATUS_INCOMPLETE :
			if ($this->progress >= 1.0) {
				$this->progress  = 0.90;
			}
			break;
		}

	// Call the base class method.
		return parent::Save($saveMode);
	}

/***************************************************************************
 * ProgramRecord::Delete
 ***************************************************************************/
	public function Delete()
	{
	// Log it.
		_LOG::$_->Write('Deleting Program Record ID: %s (Program Node ID: %s)',
		                NULL,$this->GetID(),$this->programNode->GetID());

	// Call the base class method.
		return parent::Delete();
	}
}

/***************************************************************************
 * Static Init
 ***************************************************************************/
// Define the common options.
ProgramRecord::$objOptions = array
(
// Use an object definition.
	'definition' => 'vilarity/program_record',
);

?>
