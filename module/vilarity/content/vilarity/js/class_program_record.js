(function()
{

// Use the "vilarity" namespace.
if (vilarity === undefined) console.log('Namespace "vilarity" is not defined.');
if (vilarity.ProgramRecord !== undefined) return;

// class vilarity.ProgramRecord:
class ProgramRecord
      extends ss.es.classes['ssobject:vilarity\\ProgramRecord']
{
	constructor(options)
	{
	// Call the base class constructor.
		super({
			...ProgramRecord.prototype.objOptions,
			'store' : vilarity.$store,
			...options
		});

	// Public properties:
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
		var origUUID = this.UUID;
		if (this.GetID() === 0) {
			this.UUID = ''; // Clear so the SS object API doesn't use it
		}
		return super.Save().then((obj) =>
		{
			return obj;
		});
	}

	Delete()
	{
		return super.Delete().then((obj) =>
		{
			if (this.program.objId)
			{
			// Index the node on the tree level.
			//	this.program.DropNode(this);
			}
			return obj;
		});
	}
};

/***************************************************************************
 * Static Init
 ***************************************************************************/
// Define the common options.
ProgramRecord.prototype.objOptions =
{
// Use a generated definition.
	...ss.es.classes['ssobject:vilarity\\ProgramRecord'].prototype.objOptions
};

// Use the "vilarity" namespace.
vilarity.ProgramRecord = ProgramRecord;

// Register the object class by name.
ss.api.RegisterObject(vilarity.ProgramRecord);

})();
