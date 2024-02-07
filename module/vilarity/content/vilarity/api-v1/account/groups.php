<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 * default.php
 *     : Root page content.
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="vilarity/api-v1/account/groups"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<?php
	$apiObj     = _T::$_->obj;
	$apiArgs    = _T::$_->apiArgs;
	$apiPayload = _T::$_->apiPayload;
	$groups     = $apiPayload['groups'];
	$obj        = _SITEMAP::$_->GetCurrentObject(NULL,'vilarity\\Account');

// Get the account for the session user.
	$accountObj = $obj;
	if ($accountObj == NULL)
	{
		$userObj    = _USER::$_;
		$accountObj = vilarity\Account::GetAccountForUser($userObj);
		if ($accountObj == NULL)
		{
			return (object)array('data' => ['account'=>NULL],'errors'=> [1 => 'Invalid user.'], 'status' => 401);
		}
	}

// Convert the input.
	foreach ($groups as $idx => $group)
	{
		$obj = new vilarity\Group();
		if (is_numeric($group))
		{	if ($obj->Load($group) == FALSE)
			{	unset($groups[$idx]);
				continue;
			}
		}
		else if (is_array($group))
		{	if ($obj->SetProperties($group) == FALSE)
			{	unset($groups[$idx]);
				continue;
			}
		}
		$groups[$idx] = $obj;
	}
_LOG::$_->Write('%s',null,var_export($groups,TRUE));
// Set the groups.
	$accountObj->SetGroups($groups);
	$accountObj->Save();

// Return the updated account object.
	$data = $accountObj->ExportProperties();
	return (object)array('data' => ['account' => $data]);
?>
</ss:template>
