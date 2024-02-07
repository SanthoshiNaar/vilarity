<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 * default.php
 *     : Root page content.
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="vilarity/api-v1/settings"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<?php
	$apiObj     = _T::$_->obj;
	$apiArgs    = _T::$_->apiArgs;
	$apiPayload = _T::$_->apiPayload;

	if ($apiPayload)
	{
	// [stack push]
		$prev_source = _R::$_->GetSource();
		_R::$_->SetSource('profile:'._PROFILE::$_->GetName().'#config');

	// Apply the configuration settings.
		_R::$_->Set('VILARITY_SETTING_CONTENT_LEVELS',$apiPayload['contentLevels'],TRUE);

	// [stack pop]
		_R::$_->SetSource($prev_source);

	// Save the registry.
		if (_R::$_->Save() == FALSE)
		{
		// TODO: Log this.
		// TODO: Locale?
			return array('error' => 'Failed to save the changes to the registry.');
		}

	// Reload the application registry.
		_APP::$_->ReloadRegistry();
	}

// Return the current settings.
	return (object)array
	(
		'data' => ['contentLevels' => _R::$_->VILARITY_SETTING_CONTENT_LEVELS]
	);
?>
</ss:template>
