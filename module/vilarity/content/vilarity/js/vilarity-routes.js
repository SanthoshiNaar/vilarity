var vilarity_routes = [
{
	path : '/',
	meta :
	{
		navTitle: 'Dashboard'
	},
	components: { default: ss.vue.components['vilarity']['view-default'] },
	children:
	[
	]
},

{
	path : '/settings',
	meta :
	{
		navTitle: 'Settings'
	},
	components: { default: ss.vue.components['vilarity']['view-settings'] },
	children:
	[
	]
},

{
	path : '/help',
	meta :
	{
		navTitle: 'Help'
	},
	components: { default: ss.vue.components['vilarity']['view-help'] },
	children:
	[
	]
},

{
	path : '/account',
	meta :
	{
		navTitle: 'Account'
	},
	components: { default: ss.vue.components['vilarity']['vilarity-account'] },
	props :
	{
		default : function(route)
		{
			return { 'account' : app.account };
		}
	},
	children:
	[
		{
			path : '/account\\:password',
			meta :
			{
				navTitle: 'Change Account Password',

				componentTemplate: 'password'
			},
			components: { default: ss.vue.components['vilarity']['vilarity-account'] },
			props :
			{
				default : function(route)
				{
					return { 'account' : app.account };
				}
			},
			children:
			[
			]
		},
		{
			path : '/account\\:edit',
			meta :
			{
				navTitle: 'Edit Account',

				componentTemplate: 'edit'
			},
			components: { default: ss.vue.components['vilarity']['vilarity-account'] },
			props :
			{
				default : function(route)
				{
					return { 'account' : app.account };
				}
			},
			children:
			[
			]
		}
	]
},

{
	path : '/accounts',
	meta :
	{
		navTitle: 'Accounts',

		componentTemplate: ''
	},
	components: { default: ss.vue.components['vilarity']['view-accounts'] },
	children:
	[
		{
			path : '/accounts/new',
			meta :
			{
				navTitle: 'New Account',

				componentTemplate: 'new'
			},
			components: { default: ss.vue.components['vilarity']['view-accounts'] },
			children:
			[
			]
		},

		{
			path : '/accounts/:objId',
			meta :
			{
				navTitle: 'Account Details',

				componentTemplate: 'view'
			},
			components: { default: ss.vue.components['vilarity']['view-accounts'] },
			children:
			[
				{
					path : '/accounts/:objId\\:password',
					meta :
					{
						navTitle: 'Change Account Password',

						componentTemplate: 'password'
					},
					components: { default: ss.vue.components['vilarity']['view-accounts'] },
					children:
					[
					]
				},
				{
					path : '/accounts/:objId\\:edit',
					meta :
					{
						navTitle: 'Edit Account',

						componentTemplate: 'edit'
					},
					components: { default: ss.vue.components['vilarity']['view-accounts'] },
					children:
					[
					]
				}
			]
		}
	]
},

{
	path : '/groups',
	meta :
	{
		navTitle: 'Groups',

		componentTemplate: ''
	},
	components: { default: ss.vue.components['vilarity']['view-groups'] },
	children:
	[
		{
			path : '/groups/new',
			meta :
			{
				navTitle: 'New Group',

				componentTemplate: 'new'
			},
			components: { default: ss.vue.components['vilarity']['view-groups'] },
			children:
			[
			]
		},

		{
			path : '/groups/:objId\\:edit',
			meta :
			{
				navTitle: 'Edit Group',

				componentTemplate: 'edit'
			},
			components: { default: ss.vue.components['vilarity']['view-groups'] },
			children:
			[
			]
		}
	]
},

{
	name : '/program-route',
	path : '/:programKey/:nodeKey1?/:nodeKey2?/:nodeKey3?/:nodeKey4?/:nodeKey5?/:nodeKey6?',
	meta :
	{
		navTitle: ''
	},
	components : {
		default: ss.vue.components['vilarity']['view-program-route']
	},
	props : {
	},

	beforeEnter(to, from, next)
	{
		next();
	},

//	components: { default: false },
	children:
	[
	]
},



{
	name : '/404',
	path : '*',
	meta :
	{
		navTitle: 'Not Found'
	},
	components : {
		default: ss.vue.components['vilarity']['view-404']
	},
	props : {
	},

	beforeEnter(to, from, next)
	{
		next();
	}
}



];
