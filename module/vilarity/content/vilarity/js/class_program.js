(function()
{

// Use the "vilarity" namespace.
if (vilarity === undefined) console.log('Namespace "vilarity" is not defined.');
if (vilarity.Program !== undefined) return;

// class vilarity.Program:
class Program
      extends ss.es.classes['ssobject:vilarity\\Program']
{
	constructor(options)
	{
	// Call the base class constructor.
		super({
			...Program.prototype.objOptions,
			'store' : vilarity.$store,
			...options
		});

	// Public properties:
		this.childNodes = [];

		this.nodesById  = {};
		this.nodesByKey = {};
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

		console.log('Saving...');
		var bNewNode = (this.objId == 0 ? true : false)
		return super.Save().then((obj) =>
		{
			console.log('Saved.');
			return this;
		});
	}

	LoadTreeNodes()
	{
		var obj   = new vilarity.ProgramNode();
		var conds =
		{	'program' : this.GetID(),
		};

	// Fetch the nodes incrementally.
		var start = 0;
		var limit = 100;
		var max   = 5000;
		var QueryApi = (start,limit,conds) =>
		{
			return obj.Find(start,limit,conds).then((objs) =>
			{
				if (!objs) {
					return Promise.resolve([]);
				}
				if (start > max) {
					return Promise.resolve([]);
				}
				if ((start + limit) > max) {
					limit = max - start;
				}
				if (objs.length >= limit)
				{
					return QueryApi((start+limit),limit,conds).then((moreObjs) =>
					{
						var values = Object.values(moreObjs);
						if (values.length > 0)
							return [...objs,...values]
						else
							return objs;
					});
				}
				return objs;
			});
		};

		var QueryApi_Direct = (start,limit,conds) =>
		{
		// Load the nodes for the program.
			var promise = axios.post('api/v1/program/'+this.UUID+'/nodes',
			{
			})
			.then((response) =>
			{
				var apiReply = response.data;
				if (apiReply && apiReply.response && apiReply.response.data && apiReply.response.data)
				{
					let data = apiReply.response.data;
					if (data == null)
						return null;
					if (data.nodes)
					{
						var nodeObjs = [];
						for (var i = 0; i < data.nodes.length; ++i)
						{
						// Convert the data to ProgramNode objects.
							var obj = new vilarity.ProgramNode();
							Object.assign(obj,data.nodes[i]);
							nodeObjs.push(obj);
						}
						return nodeObjs;
					}
					return [];
				}
			});
			return promise;
		};

		return QueryApi_Direct(start,limit,conds).then((objs) =>
		{
			if (!objs) {
				return Promise.resolve([]);
			}

		// Build a node ID map.
		//	this.nodesById  = {};
		//	this.nodesByKey = {};
			for (var i = 0; i < objs.length; ++i) {
				this.IndexNode(objs[i]);
			}
			for (var i = 0; i < objs.length; ++i) {
				this.SetupNodeReferences(objs[i]);
			}

		// Process the nodes.
		//	this.childNodes = [];
			for (i = 0; i < objs.length; ++i)
			{
				var programNodeObj = objs[i];
				var parentNodeObj  = programNodeObj.parent;
				var parentId       = parentNodeObj.objId;

			// Note: There is no way to destinguish between a parent that
			//       does not exist and a node that doesn't have a parent
			//       because both will return an object with objId==0.
			//       Test the "type" property instead. Only a "series"
			//       may have a program as the parent (no programNode parent).
				if (parentId == 0 && programNodeObj.type != 'series')
				{
					console.log('Orphaned Program Node detected (ID: %s). Ignoring.',
					             programNodeObj.objId);
					continue;
				}

			// Add the node to the tree.
				if (programNodeObj.parent.objId == 0) {
					this.childNodes.push(programNodeObj);
				}
				else {
					programNodeObj.parent.childNodes.push(programNodeObj);
				}
			}

			return this.childNodes;
		});
	}

	IndexNode(nodeObj)
	{
		nodeObj.program = this;
		this.nodesById [nodeObj.GetID()] = nodeObj;
		this.nodesById [nodeObj.UUID   ] = nodeObj;
		this.nodesByKey[nodeObj.key    ] = nodeObj;
	}
	DropNode(nodeObj)
	{
		nodeObj.program = null;
		delete this.nodesById [nodeObj.GetID()];
		delete this.nodesById [nodeObj.UUID   ];
		delete this.nodesByKey[nodeObj.key    ];
	}

	SetupNodeReferences(nodeObj)
	{
		nodeObj._.objPropGetMode = 'id';

		var parentId   = nodeObj.parent;
		var previousId = nodeObj.previous;
		var nextId     = nodeObj.next;

		nodeObj._.objPropGetMode = 'obj';

		if (parentId != 0) {
			nodeObj.parent = this.nodesById[parentId];
		}
		if (previousId != 0) {
			nodeObj.previous = this.nodesById[previousId];
		}
		if (nextId != 0) {
			nodeObj.next = this.nodesById[nextId];
		}
	}

	LoadChildNodes()
	{
		if (this.childNodes && this.childNodes.length)
		{
			return Promise.resolve(this.childNodes);
		}

		var obj   = new vilarity.ProgramNode();
		var conds =
		{	'program' : this.GetID(),
			'parent'  : 0
		};
		return obj.Find(0,100,conds).then((objs) =>
		{
			this.childNodes = Object.values(objs);
			return objs;
		});
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
				var prev   = node.previous;
				var next   = node.next;
				var parent = node.parent;
				promises.push(node.Save().then((obj) =>
				{
				// Restore our object pointers.
				// BUG: This is necessary because Object::Save() might replace property objects
				//      with new objects when the server returns new data instead of just updating
				//      the existing object properties.
				// TODO: This might be a save promise race condition, too.
				// TODO: Remove once the Object::Save() bug is fixed.
					obj.previous = prev;
					obj.next     = next;
					obj.parent   = parent;

					return node;
				}));
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

/***************************************************************************
 * Program::SanitizeKey
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
	GetNode(nodeKey)
	{
		if (this.nodesByKey && nodeKey in this.nodesByKey)
		{
			return Promise.resolve(this.nodesByKey[nodeKey]);
		}

		var obj   = new vilarity.ProgramNode();
		var conds =
		{	'program' : this.GetID(),
			'key'     : nodeKey
		};
		return obj.Find(0,1,conds).then((objs) =>
		{
			if (objs && objs.length)
				return objs[0];
			return null;
		});
	}
};

/***************************************************************************
 * Static Init
 ***************************************************************************/
// Define the common options.
Program.prototype.objOptions =
{
// Use a generated definition.
	...ss.es.classes['ssobject:vilarity\\Program'].prototype.objOptions
};

// Use the "vilarity" namespace.
vilarity.Program = Program;

// Register the object class by name.
ss.api.RegisterObject(vilarity.Program);

})();
