(function()
{

// Vuex Store Modules:
var storeModules = {};

// Vuex Store Module: user
storeModules['user'] =
{
	namespaced: true,

	state :
	{
		sessionUser : null, // new ss.user.User(), // the user object
		bLoggedIn   : false
	},

	mutations :
	{
		sessionUser: function(state,userObj)
		{
			if (!(userObj instanceof Object))
				state.sessionUser = new ss.user.User();
			else if (userObj instanceof ss.user.User)
				state.sessionUser = userObj;
			else
			{
			// Note: If the session user is referenced in the UI, for example
			//       through permission checks, this has the potential to cause
			//       full Vue redraws at that level and anything below it.
			//       This is particularly noticable when a component creates
			//       temporary state or a Vuex route uses dynamic props,
			//       because the redraw will cause that state to be lost/reloaded.
			// TODO: Do more to prevent unnecessary reactivity triggering.
			// TODO: Is there any way to prevent <router-view> from being affected by this?
				state.sessionUser.SetProperties(userObj,/*bMergeExistingObjects=*/true);
			// TODO: Compare and merge permissions instead.
				if (userObj instanceof Object &&
					userObj.__permissions &&
					JSON.stringify(state.sessionUser.__permissions) !== JSON.stringify(userObj.__permissions))
				{	state.sessionUser.__permissions = userObj.__permissions;	}
			}
			state.bLoggedIn = (state.sessionUser.objId ? true : false);
		},
		LOGIN : function(state,userObj)
		{
			if (!(userObj instanceof Object))
				state.sessionUser = new ss.user.User();
			else if (userObj instanceof ss.user.User)
				state.sessionUser = userObj;
			else
			{
			// Note with a login, we don't attempt to merge data like with session updates (above).
				state.sessionUser.SetProperties(userObj,/*bMergeExistingObjects=*/false);
				if (userObj instanceof Object &&
					userObj.__permissions)
				{	state.sessionUser.__permissions = userObj.__permissions;	}
			}
			state.bLoggedIn = (state.sessionUser.objId ? true : false);
		},
		LOGOUT : function(state)
		{
			state.sessionUser = new ss.user.User();
			state.bLoggedIn   = false;
		}
	},

	getters :
	{
		'bAuth' : function(state) {
			return state.bLoggedIn ? true : false;
		}
	},

	actions :
	{
	}
};

// Vuex Store:
var store = ss.vue.SetupStore('webapp',
{
	strict  : true,
	modules : storeModules,

	state :
	{
		view : 'user_login'
	},

	mutations :
	{
		SET_VIEW : function(state,view)
		{
			state.view = view;
		}
	},

	getters :
	{
		view : function(state) { return state.view; }
	},

	actions :
	{
		SetView : function(context,view)
		{
			context.commit('SET_VIEW',view);
		}
	}
});

})();
