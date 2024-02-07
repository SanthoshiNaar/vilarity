(function()
{

// Use the "vilarity" namespace.
if (vilarity === undefined) console.log('Namespace "vilarity" is not defined.');
if (vilarity.ProgramNode !== undefined) return;

// class vilarity.ProgramNode:
class ProgramNode
      extends ss.es.classes['ssobject:vilarity\\ProgramNode']
{
	constructor(options)
	{
	// Call the base class constructor.
		super({
			...ProgramNode.prototype.objOptions,
			'store' : vilarity.$store,
			...options
		});

	// Public properties:
		this.childNodes = [];

	//	this.nodesByKey = {};
	}

	Load(id)
	{
	// Clear the subproperties.

	// Call the base class method.
		return super.Load.call(this,id).then(() =>
		{
			this.id = this.objId;
		});
	}

	Save()
	{
	// Remove invalid key characters before saving.
		this.SanitizeKey();

		var program = this.program;
		var prev    = this.previous;
		var next    = this.next;
		var parent  = this.parent;

		console.log('Saving...');
		var bNewNode = (this.objId == 0 ? true : false)
		return super.Save().then((obj) =>
		{
			console.log('Saved.');

		// Restore our object pointers.
		// BUG: This is necessary because Object::Save() might replace property objects
		//      with new objects when the server returns new data instead of just updating
		//      the existing object properties.
		// TODO: This might be a save promise race condition, too.
		// TODO: Remove once the Object::Save() bug is fixed.
			this.program  = program;
			this.previous = prev;
			this.next     = next;
			this.parent   = parent;

			if (//bNewNode &&
				this.program &&
				this.program.objId)
			{
			// Index the node on the tree level.
				this.program.IndexNode(this);
				this.program.SetupNodeReferences(this);
			}
			return this;
		});
	}

	Delete()
	{
		return super.Delete().then((obj) =>
		{
		// Reorder the tree.
			var parentLevelNode = this.parent;
			if (parentLevelNode == null || parentLevelNode.objId == 0) {
				parentLevelNode = this.program;
			}
			if (parentLevelNode)
			{
				var idx = parentLevelNode.childNodes.indexOf(this);
				if (idx >= 0)
				{
					parentLevelNode.childNodes.splice(idx,1);
				}
			}

		// Drop the node from the tree.
			if (this.program &&
				this.program.objId)
			{
			// Index the node on the tree level.
				this.program.DropNode(this);
			}

			if (parentLevelNode)
			{
				parentLevelNode.ReorderChildNodes();
			}
			return this;
		});
	}

/***************************************************************************
 * ProgramNode::SanitizeKey
 ***************************************************************************/
	SanitizeKey()
	{
	// Remove invalid key characters.
		var key    = this.key;
		var keyNew = key.toLowerCase();
		keyNew = keyNew.replace(/[^a-z0-9\-]/g,'-');
		keyNew = keyNew.replace(/-{2,}/g,'-');
		keyNew = keyNew.replace(/^-|-$/g,'');
		if (key != keyNew)
		{
			this.key = keyNew;
			console.log('Fixed invalid key: "%s" to "%s"',key,keyNew);
		}
		return keyNew;
	}

/***************************************************************************
 * ProgramNode::Get
 ***************************************************************************/
	GetResource()
	{
		try {
			switch (this.subType)
			{
			case 'image' :
				var obj = JSON.parse(this.description);
				if (!obj)
					return null;
				if (obj.hasOwnProperty('gallery') === false || obj.hasOwnProperty('lvmEntry') === false)
					return null;
				return obj;

			case 'audio' :
			case 'video' :
			case 'file'  :
				var obj = JSON.parse(this.description);
				if (!obj)
					return null;
				if (obj.hasOwnProperty('volume') === false || obj.hasOwnProperty('lvmPath') === false)
					return null;
				return obj;

			default :
				return null;
			}
		}
		catch {
			return null;
		}
		return null;
	}

	GetResourceURL()
	{
		try {
			switch (this.subType)
			{
			case 'image' :
				if (this.description.match(/^https?:\/\/.+\.(jpg|jpeg|gif|png)(?:\?|$)/))
					return this.description;
				break;

			case 'audio' :
			// Direct link:
				if (this.description.match(/^https?:\/\/.+\.(mp3|m4a|aac)(?:\?|$)/))
					return this.description;
			// External service (pre-fetched data):
				if (this.description.match(/^\{/))
				{
					var obj = JSON.parse(this.description);
					if (obj && obj.url)
						return obj.url;
				}
				break;

			case 'video' :
			// File host:
				if (this.description.match(/^https?:\/\/.+\.(mkv|m4v|mpg|mpeg|mov|avi)(?:\?|$)/))
					return this.description;
			// YouTube:
				var videoId = this.GetYoutubeVideoIdFromURL(this.description);
				if (videoId != null)
					return this.description;
			// Vimeo:
				videoId = this.GetVimeoVideoIdFromURL(this.description);
				if (videoId != null)
					return this.description;
				break;

			case 'file'  :
				if (this.description.match(/^https?:\/\/.+(?:\?|$)/))
					return this.description;
				break;
			}
			return null;
		}
		catch {
			return null;
		}
	}

	GetYoutubeVideoIdFromURL(link)
	{
		if (link === undefined)
			link = this.description;

		if (!link)
			return null;

		if (link.match(/youtube\.com|youtu\.be/) == null)
			return null;

		var matches;
		var videoId = null;
		var videoUrlPatterns =
		[
			'^https?://(?:www\\.)?youtube\\.com/watch\\?v=([^&]+)(?:&|$)',
			'^https?://(?:www\\.)?youtube\\.com/v/(.+)(?:\\?|$)',
			'^https?://(?:www\\.)?youtube\\.com/embed/(.+)(?:\\?|$)',
			'^https?://(?:www\\.)?youtu\\.be/(.+)(?:\\?|$)',
		];
		for (var i = 0; i < videoUrlPatterns.length; ++i)
		{
			var pattern = videoUrlPatterns[i];
			if (matches = link.match(new RegExp(pattern)))
			{
				videoId = matches[1];
				break;
			}
		}
		return videoId;
	}

	GetYoutubeEmbedURL(link)
	{
		if (link === undefined)
			link = this.description;

		var videoId = this.GetYoutubeVideoIdFromURL(link);
		if (videoId)
			return 'https://www.youtube.com/embed/'+videoId;
		return null;
	}

	GetVimeoVideoIdFromURL(link)
	{
		if (link === undefined)
			link = this.description;

		if (!link)
			return null;

		if (link.match(/vimeo\.com/) == null)
			return null;

		var matches;
		var videoId = null;
		var hashId  = null;
		var videoUrlPatterns =
		[
			'^https?://(?:www\\.)?vimeo\\.com/(\\d+)(?:/(.+))?(?:\\W|$)',
			'^https?://player\\.vimeo\\.com/video/(\\d+)(?:\\?h=(.+))?(?:\\W|$)'
		];
		for (var i = 0; i < videoUrlPatterns.length; ++i)
		{
			var pattern = videoUrlPatterns[i];
			if (matches = link.match(new RegExp(pattern)))
			{
				videoId = matches[1];
				hashId  = matches[2];
				if (hashId  != '')
					videoId += '?h='+hashId;
				break;
			}
		}
		return videoId;
	}

	GetVimeoEmbedURL(link)
	{
		if (link === undefined)
			link = this.description;

		var videoId = this.GetVimeoVideoIdFromURL(link);
		if (videoId)
			return 'https://player.vimeo.com/video/'+videoId;
		return null;
	}

/***************************************************************************
 * ProgramNode::Set
 ***************************************************************************/
	SetResource(data)
	{
		try {
			switch (this.subType)
			{
			case 'image' :
			case 'audio' :
			case 'video' :
			case 'file'  :
				this.description = JSON.stringify(data);
				return true;

			default :
				this.description = '';
				return false;
			}
		}
		catch {
			return false;
		}
	}

	SetResourceURL(url)
	{
		try {
			switch (this.subType)
			{
			case 'image' :
			case 'audio' :
			case 'video' :
			case 'file'  :
				this.description = url;
				return true;

			default :
				this.description = '';
				return false;
			}
		}
		catch {
			return false;
		}
	}

/***************************************************************************
 * ProgramNode::Load
 ***************************************************************************/
	LoadChildNodes()
	{
		if (this.childNodes && this.childNodes.length)
		{
			return Promise.resolve(this.childNodes);
		}

		var programObj = this.program;

	//	return programObj.then((programObj) =>
	//	{
			var obj   = new vilarity.ProgramNode();
			var conds =
			{	'program' : programObj.GetID(),
				'parent'  : this.GetID()
			};
			return obj.Find(null,null,conds).then((objs) =>
			{
				this.childNodes = Object.values(objs);
				return objs;
			});
	//	});
	}

	ReorderChildNodes()
	{
		var siblings = this.childNodes;

		// Renumber.
		var promises = [];
		for (var i = 0; i < siblings.length; ++i)
		{
			var node     = siblings[i];
			var bChanged = false;
		// Order:
			if (node.order != i)
			{	node.order  = i;
				bChanged    = true;
			}
		// Number:
			if (node.number != (i+1))
			{	node.number  = (i+1);
				bChanged    = true;
			}
		// Previous:
			if (i === 0 && (node.previous instanceof vilarity.ProgramNode && node.previous.objId != 0))
			{	node.previous = null;
				bChanged = true;
			}
			if (i !== 0 && (node.previous instanceof vilarity.ProgramNode === false || node.previous.objId != siblings[i-1].objId))
			{	node.previous = siblings[i-1];
				bChanged = true;
			}
		// Next:
			if (i === (siblings.length-1) && (node.next instanceof vilarity.ProgramNode && node.next.objId != 0))
			{	node.next = null;
				bChanged  = true;
			}
			if (i !== (siblings.length-1) && (node.next instanceof vilarity.ProgramNode === false || node.next.objId != siblings[i+1].objId))
			{	node.next = siblings[i+1];
				bChanged = true;
			}
		// Save?
			if (bChanged)
			{
				(function(node)
				{
				var program = node.program;
				var prev    = node.previous;
				var next    = node.next;
				var parent  = node.parent;

			//	var tmpObj = new vilarity.ProgramNode();
			//		tmpObj.SetProperties(node);
				promises.push(node.Save().then((obj) =>
				{
				// Restore our object pointers.
				// BUG: This is necessary because Object::Save() might replace property objects
				//      with new objects when the server returns new data instead of just updating
				//      the existing object properties.
				// TODO: This might be a save promise race condition, too.
				// TODO: Remove once the Object::Save() bug is fixed.
				//	node.program  = program;
				//	node.previous = prev;
				//	node.next     = next;
				//	node.parent   = parent;

					return node;
				}));
				})(node);
			}
		}

	// Wait for all promises to resolve.
		return new Promise((resolve,reject) =>
		{
			Promise.all(promises).then((values) =>
			{
				resolve({values:values});
			});
		});
	}
};

/***************************************************************************
 * Static Init
 ***************************************************************************/
// Define the common options.
ProgramNode.prototype.objOptions =
{
// Use a generated definition.
	...ss.es.classes['ssobject:vilarity\\ProgramNode'].prototype.objOptions
};

// Use the "vilarity" namespace.
vilarity.ProgramNode = ProgramNode;

// Register the object class by name.
ss.api.RegisterObject(vilarity.ProgramNode);

})();
