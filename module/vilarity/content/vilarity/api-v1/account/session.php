<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 * default.php
 *     : Root page content.
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="vilarity/api-v1/account/session"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<?php
	$apiObj  = _T::$_->obj;
	$apiArgs = _T::$_->apiArgs;

// Get the account for the session user.
	$userObj    = _USER::$_;
	$accountObj = vilarity\Account::GetAccountForUser($userObj);
	if ($accountObj == NULL)
	{
		return (object)array('data' => ['account'=>NULL],'errors'=> [1 => 'Invalid user.'], 'status' => 401);
	}

	$data = $accountObj->ExportProperties();

	return (object)array('data' => ['account' => $data]);
?>
</ss:template>
