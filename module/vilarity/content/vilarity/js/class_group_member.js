(function()
{

// Use the "vilarity" namespace.
if (vilarity === undefined) console.log('Namespace "vilarity" is not defined.');
if (vilarity.GroupMember !== undefined) return;

// class vilarity.GroupMember:
class GroupMember
      extends ss.es.classes['ssobject:vilarity\\GroupMember']
{
	constructor(options)
	{
	// Call the base class constructor.
		super({
			...GroupMember.prototype.objOptions,
			'store' : vilarity.$store,
			...options
		});

	// Public properties:
		this.programs = null;

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
};

/***************************************************************************
 * Static Init
 ***************************************************************************/
// Define the common options.
GroupMember.prototype.objOptions =
{
// Use a generated definition.
	...ss.es.classes['ssobject:vilarity\\GroupMember'].prototype.objOptions
};

// Use the "vilarity" namespace.
vilarity.GroupMember = GroupMember;

// Register the object class by name.
ss.api.RegisterObject(vilarity.GroupMember);

})();
