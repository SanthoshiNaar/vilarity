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

// Class vilarity\Account:
class Account
      extends ObjectBase
{
// Constants:
	const STATUS_INACTIVE = 0x00;
	const STATUS_ACTIVE   = 0x01;

// Static members:
	public static $objOptions; // default options (defined below)

// Class members:
	protected $groups = NULL;

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

/***************************************************************************
 * Account::Save
 ***************************************************************************/
	public function Save($saveMode = self::SAVE_DEFAULT)
	{
	// Call the base class method.
		if ($ret = parent::Save($saveMode))
		{
		// Manage the group membership associations.
			$groups = $this->GetGroups();
			$gmObjs = $this->GetGroupMemberships();
			if (is_array($groups) == FALSE)
			{	$groups = array();	}
			if (is_array($gmObjs) == FALSE)
			{	$gmObjs = array();	}
			foreach ($groups as $groupObj)
			{
			// Find an existing association.
				foreach ($gmObjs as $idx => $gmObj)
				{	if ($gmObj->group->GetID() == $groupObj->GetID())
					{
					// The association already exists.
						unset($gmObjs[$idx]);
						continue 2; // (continue the outer foreach loop)
					}
				}
			// Create a new association.
				$newObj = new GroupMember();
				$newObj->account = $this;
				$newObj->group   = $groupObj;
				$newObj->Save();
			}
		// Delete the remaining associations.
			foreach ($gmObjs as $idx => $gmObj)
			{	$gmObj->Delete();	}
		}

		return $ret;
	}

/***************************************************************************
 * Account::Get
 ***************************************************************************/
	public static function GetAccountForUser($userObj,$bAutoCreateAccount = TRUE)
	{
	// Argument validation:
		if ($userObj == FALSE ||
			$userObj->GetID() == 0)
		{	return FALSE;	}

	// Find an existing Account.
		$accountObj = new Account();
		if ($accountObj->LoadOrFind(NULL,ObjectBase::Where('user','=',$userObj->GetID())))
		{	return $accountObj;	}

	// Auto-create allowed?
		if ($bAutoCreateAccount == FALSE)
		{	return NULL;	}

	// Create a new account for the user.
		$accountObj->user = $userObj;
		$accountObj->Save();

		return $accountObj;
	}

	public function GetGroupMemberships($bNoCache = FALSE)
	{
	// Find the groups memberships.
		$qObj = new GroupMember();
		return $qObj->Find(0,500,
		                   ObjectBase::Where('account','=',$this->GetID()));
	}

	public function GetGroups($bNoCache = FALSE)
	{
	// Return the cached groups.
		if ($bNoCache == FALSE &&
			is_array($this->groups))
		{	return $this->groups;	}

	// Find the groups via the defined group memberships.
		$gmObjs = $this->GetGroupMemberships();
		$groups = array();
		$priGroupId = $this->group->GetID();
		if ($priGroupId != 0)
		{	$groups[] = $this->group;	}
		foreach ($gmObjs as $gmObj)
		{	if ($gmObj->group->GetID() == $priGroupId)
			{	continue;	}
			$groups[] = $gmObj->group;
		}
		$this->groups = $groups;
		return $this->groups;
	}

/***************************************************************************
 * Account::Set
 ***************************************************************************/
	public function SetGroups($groups)
	{
		$this->groups = $groups;
	}

// EXPORT:
	public function ExportProperties($target = FALSE)
	{
	// Get the object properties.
		$objProperties = $this->GetProperties();
		if ($objProperties == FALSE)
		{	return array();	}

	// Expand the objects.
		$objProperties['user'  ] = $this->user ->ExportProperties($target);
		$objProperties['group' ] = $this->group->ExportProperties($target);
		$objProperties['groups'] = $this->GetGroups();
		foreach ($objProperties['groups'] as $idx => $groupObj)
		{	$objProperties['groups'][$idx] = $groupObj->ExportProperties($target);	}

		return $objProperties;
	}
}

/***************************************************************************
 * Static Init
 ***************************************************************************/
// Define the common options.
Account::$objOptions = array
(
// Use an object definition.
	'definition' => 'vilarity/account',
);

?>
