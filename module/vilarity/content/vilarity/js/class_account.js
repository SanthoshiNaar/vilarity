(function()
{

// Use the "vilarity" namespace.
if (vilarity === undefined) console.log('Namespace "vilarity" is not defined.');
if (vilarity.Account !== undefined) return;

// class vilarity.Account:
class Account
      extends ss.es.classes['ssobject:vilarity\\Account']
{
	constructor(options)
	{
	// Call the base class constructor.
		super({
			...Account.prototype.objOptions,
			'store' : vilarity.$store,
			...options
		});

	// Public properties:
		this.programs = null;
		this.groups   = [];

	// Make these properties Vue-reactive.
	//	if (Vue && Vue.util)
	//	{
	//		Vue.util.defineReactive(this,'games');
	//		Vue.util.defineReactive(this,'players');
	//	}
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

	LoadProgramRecords(program,parentProgramNode)
	{
		var obj   = new vilarity.ProgramRecord();
		var conds =
		{
			'account' : this.GetID()
		};
		if (program instanceof vilarity.Program)
		{
			conds['program'] = program.GetID();
		}
		if (parentProgramNode instanceof vilarity.ProgramNode)
		{
		// TODO: ?
		//	conds['programNode.parent'] = parentProgramNode.GetID();
		}

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
		return QueryApi(start,limit,conds).then((objs) =>
		{
			if (!objs) {
				return Promise.resolve([]);
			}

			for (var i = 0; i < objs.length; ++i)
			{
			// Map the known objects.
				var obj = objs[i];
					obj.account = this;
				if (program instanceof vilarity.Program) {
					obj.program = program;
				}
			// Note: Cannot do this, not known.
			//	if (programNode instanceof vilarity.ProgramNode) {
			//		obj.programNode = programNode;
			//	}
			}
			return objs;
		});
	}

	Save(saveMode)
	{
	// Call the base class method.
		var groups = this.groups; // backup so ::Save() doesn't erase it
		return super.Save.call(this,saveMode).then(() =>
		{
			this.groups = groups;
			return this.__SaveGroups();
		});
	}

	__SaveGroups()
	{
		// Load the account associated with the user session.
		var promise = axios.post('api/v1/account/'+this.UUID+'/groups',
		{
			groups : this.groups
		})
		.then((response) =>
		{
			var apiReply = response.data;
			if (apiReply && apiReply.response && apiReply.response.data && apiReply.response.data)
			{
				let data = apiReply.response.data;
				if (data == null)
					return null;
				if (data.account)
				{
				//	this.SetProperties(data.account);
				}
				return this;
			}
		});
		return promise;
	}
};

/***************************************************************************
 * Static Init
 ***************************************************************************/
// Define the common options.
Account.prototype.objOptions =
{
// Use a generated definition.
	...ss.es.classes['ssobject:vilarity\\Account'].prototype.objOptions
};

// Use the "vilarity" namespace.
vilarity.Account = Account;

// Register the object class by name.
ss.api.RegisterObject(vilarity.Account);

})();
