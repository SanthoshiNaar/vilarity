(function()
{

// Use the "vilarity" namespace.
if (vilarity === undefined) console.log('Namespace "vilarity" is not defined.');
if (vilarity.Group !== undefined) return;

// class vilarity.Group:
class Group
      extends ss.es.classes['ssobject:vilarity\\Group']
{
	constructor(options)
	{
	// Call the base class constructor.
		super({
			...Group.prototype.objOptions,
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
Group.prototype.objOptions =
{
// Use a generated definition.
	...ss.es.classes['ssobject:vilarity\\Group'].prototype.objOptions
};

// Use the "vilarity" namespace.
vilarity.Group = Group;

// Register the object class by name.
ss.api.RegisterObject(vilarity.Group);

})();
