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

// Class vilarity\Group:
class Group
      extends ObjectBase
{
// Constants:
	const STATUS_INACTIVE = 0x00;
	const STATUS_ACTIVE   = 0x01;

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
 * Account::Action
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

// EXPORT:
	public function ExportProperties($target = FALSE)
	{
	// Get the object properties.
		$objProperties = $this->GetProperties();
		if ($objProperties == FALSE)
		{	return array();	}

	// Expand the objects.


		return $objProperties;
	}
}

/***************************************************************************
 * Static Init
 ***************************************************************************/
// Define the common options.
Group::$objOptions = array
(
// Use an object definition.
	'definition' => 'vilarity/group',
);

?>
