<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 * default.php
 *     : Root page content.
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="vilarity/api-v1/program/nodes"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<?php
	$apiObj     = _T::$_->obj;
	$apiArgs    = _T::$_->apiArgs;
	$apiPayload = _T::$_->apiPayload;
	$obj        = _SITEMAP::$_->GetCurrentObject(NULL,'vilarity\\Program');

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

// Get the newest mtime.
	$qObj  = new vilarity\ProgramNode();
	$conds = array(ObjectBase::Where('program','=',$obj->GetID()));
	$qObj->LoadOrFind(NULL,array($conds,Object::SortBy('mtime','desc')));
	$mtime = $qObj->GetMTime();

// Load and cache the ProgramNodes for the Program.
	$nodes     = array();
	$cacheDir  = vilarity\ModuleVilarity::GetCacheDirectory('programs',(int)$obj->GetID(),TRUE);
	$cachePath = $cacheDir.'nodes.json';
	_LOG::$_->Write('cachePath: %s',null,$cachePath);
	if (file_exists($cachePath) && $mtime < filemtime($cachePath))
	{
	// Load the nodes from the cache.
		$nodes = json_decode(file_get_contents($cachePath));
	}
	else
	{
	// Return all of the ProgramNodes for the Program.
		$qObj  = new vilarity\ProgramNode();
		$start = 0;
		$limit = 5000;
		$apiLinkBase = _SITEMAP::$_->GetURL('api/v1/program/node');
		while ($results = $qObj->Find($start,$limit,$conds))
		{
			foreach ($results as $nodeObj)
			{
				$node = $nodeObj->ExportProperties();
				$node['api-link'] = $apiLinkBase.'/'.$node['UUID'];
				$nodes[] = $node;
			}
			if (count($results) < $limit)
			{
				break;
			}
			$start += $limit;
		}

	// Cache the output.
		file_put_contents($cachePath,json_encode($nodes));
	}

// Include the Program.
	$programData = $obj->ExportProperties();
	$programData['api-link'] = _SITEMAP::$_->GetURL('api/v1/program').'/'.$programData['UUID'];

	$data = array
	(
		'program'   => $programData,
		'nodeCount' => count($nodes),
		'nodes'     => $nodes,
	);
	return (object)array('data' => $data);
?>
</ss:template>
