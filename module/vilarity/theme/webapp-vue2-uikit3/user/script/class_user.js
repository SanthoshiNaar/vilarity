(function()
{

// Use the "ss.user" namespace.
if (ss === undefined) console.log('Namespace "ss" is not defined.');
if (ss.user === undefined) ss.user = {};
if (ss.user.User !== undefined) return;

// class ss.user.User:
class User
      extends ss.es.classes['ssobject:user\\User']
//    extends ss.es.classes['ssobject:User']
{
	bAuth = false;
	__permissions = {};

	constructor(options)
	{
	// Call the base class constructor.
		super({
			...User.prototype.objOptions,
			'store' : ss.vue.stores['webapp'],
			...options
		});
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

	ChangePassword(password,passwordVerify,passwordCurrent)
	{
	// Load the account associated with the user session.
		var promise = axios.post('api/v1/user/password',
		{
			'username'        : this.username,
			'password'        : password,
			'passwordVerify'  : passwordVerify,
			'passwordCurrent' : passwordCurrent
		})
		.then((response) =>
		{
			var apiReply = response.data;
			if (apiReply && apiReply.response && apiReply.response.data && apiReply.response.data)
			{
				let data = apiReply.response.data;
				if (data == null)
					return null;
			// Update this object.
				this.SetProperties(data.user);
			}
			return apiReply.response;
		});
		return promise;
	}

/***************************************************************************
 * SECURITY
 ***************************************************************************/

/***************************************************************************
 * User::GetPermission
 ***************************************************************************/
	GetPermission(permission)
	{
	// Parameter validation:
		if (permission == '')
		{	return FALSE;	}

	// Determine the allow/deny status of the permission.
		return (this.__permissions[permission] ? true : false);
	}

	GetPermissions()
	{	return this.__permissions;	}

/***************************************************************************
 * User::SetPermission
 ***************************************************************************/
/* N.B.: We cannot "set" permissions because that is a security risk.
	SetPermission(permission,bValue)
	{
	// Parameter validation:
		if (permission == '')
		{	return false;	}

	// Deny setting the value of an undefined permission.
	// TODO: Is this a good idea?
		if (this.__permissions &&
			this.__permissions.hasOwnProperty(permission))
		{
		// TODO: Trace it?
			return false;
		}

	// Set the value of the permission.
		this.__permissions[permission] = Boolean(bValue);
		return true;
	}

	SetPermissions(permissions)
	{	this.__permissions = permissions;	}
*/
/***************************************************************************
 * User::PurgePermissions
 ***************************************************************************/
	PurgePermissions()
	{	this.__permissions = {};	}

/***************************************************************************
 * User::Is*
 ***************************************************************************/
	IsAllowed(permission)
	{
	// Determine if ALL permissions are allowed.
		if (this.__permissions &&
			this.__permissions.hasOwnProperty('all') &&
			this.__permissions['all'])
		{	return true;	}

	// Parameter validation:
		if (permission == '')
		{	return false;	}

	// Permission validation:
		if (this.__permissions &&
			this.__permissions.hasOwnProperty(permission) == false)
		{	return false;	}

	// Determine the "allow" status of the permission.
		if (this.__permissions[permission])
		{	return true;	}
		return false;
	}
	IsDenied(permission)
	{
		if (this.IsAllowed(permission))
		{	return false;	}
		else
		{	return true;	}
	}

/***************************************************************************
 * User::RegisterPermission
 ***************************************************************************/
	RegisterPermission(permission,
	                   bValue = FALSE)
	{
	// Parameter validation:
		if (permission == '')
		{	return false;	}

	// Define the permission.
		this.__permissions[permission] = Boolean(bValue);
		return true;
	}

/***************************************************************************
 * User::MergePermissions
 ***************************************************************************/
	MergePermissions(permissions)
	{
	// Parameter validation.
		if (is_array(permissions) == FALSE)
		{	return false;	}

	// Merge the permissions with the existing ones.
		this.__permissions = Object.assign([],this.__permissions,permissions);
		return true;
	}
};

/***************************************************************************
 * Static Init
 ***************************************************************************/
// Define the common options.
User.prototype.objOptions =
{
// Use a generated definition.
	...ss.es.classes['ssobject:user\\User'].prototype.objOptions
//	...ss.es.classes['ssobject:User'].prototype.objOptions
};

// Use the "ss.user" namespace.
ss.user.User = User;

// Register the object class by name.
ss.api.RegisterObject(ss.user.User);

})();
