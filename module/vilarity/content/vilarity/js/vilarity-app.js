// Do not use strict mode live.
ss.vue.stores['webapp'].strict = (app_env.mode == 'dev' ? true : false);

// Define an app reference (inside beforeCreate).
vilarity.app = null;

// Setup the app.
var app = ss.vue.SetupApp('vilarity','webapp-vue',
{
//	el        : null,
//	store     : store,
//	extends   : ss.vue.apps['webapp-vue'],
	components: ss.vue.components['vilarity'],

	data : function()
	{
		return {
			settings :
			{
				maxNodes :
				{
					'program' : 10,
					'series'  : 10,
					'session' : 10,
					'part'    : 10,
					'point'   : 100
				}
			},

			ui :
			{
				mode : '',
				bShowLoadingThrobber : 0
			},

		// Managed by the router callback (below).
			currentProgram     : null,
			currentProgramNode : null,

			'user' : {
				'config' : {
					'allowInactiveUsers' : false
				}
			}
		};
	},

	computed :
	{
		bLoggedIn : function() {
			return this.$store.state.user.bLoggedIn;
		},
		sessionUser : function() {
			return this.$store.state.user.sessionUser;
		},

		account : function() {
			return this.$store.state.vilarity.account;
		},

		effectiveAccount :
		{
			get: function () {
				return this.$store.state.vilarity.effectiveAccount;
			},
			set: function(value) {
				this.$store.commit('vilarity/effectiveAccount',value);
			}
		},

		viewAccount : function()
		{
		// The account currently being viewed, either via the "View As" feature or just the current account logged in.
			return (this.effectiveAccount && this.effectiveAccount.objId != 0 ? this.effectiveAccount : this.account);
		},

		effectiveGroup :
		{
			get: function () {
				return this.$store.state.vilarity.effectiveGroup;
			},
			set: function(value) {
				this.$store.commit('vilarity/effectiveGroup',value);
			}
		},

		programs : function() {
			return this.$store.state.vilarity.programs;
		},

		permissions : function()
		{
			return {
				'vilarity' : {
					'programs' : {
						'edit' : (this.effectiveAccount.objId == 0 && this.sessionUser.IsAllowed('vilarity.program.edit'))
					}
				}
			};

		}
	},

	watch :
	{

	//	'$route.params.pathMatch' : function(to, from)
		'$route.params' : { deep: true, handler: function(to, from)
		{
			var $route         = this.$route;
		//	var routePathMatch = $route.params.pathMatch;

			this.currentProgram     = null;
			this.currentProgramNode = null;

		// Only react to a program route change.
			if ($route.matched[0].name !== '/program-route') {
				return;
			}

		// Be a Page Not Found error by default.
			this.navTitle = $route.matched[0].meta.navTitle = 'Page Not Found';
			$route.matched[0].components = {
				default: ss.vue.components['vilarity']['view-page_not_found']
			};

		// Match a Program.
		//	var programs = this.programs;
			var programs = this.FilterByProgramAccess(this.viewAccount,this.programs);
			for (let i = 0; i < programs.length; ++i)
			{
			//	if (programs[i].key === routePathMatch)
				if (programs[i].key === $route.params.programKey)
				{
					var programObj = programs[i];

				// Set the app title.
					$route.matched[0].meta.navTitle = this.navTitle = programObj.title;

				// Switch the components for a program view.
					$route.matched[0].components = {
						default: ss.vue.components['vilarity']['view-program']
					};

				// Use meta data to pass the Program object.
				// TODO: Is it too late to init props? (seems to be too late)
					this.currentProgram            = programObj;
					$route.matched[0].meta.program = programObj;

				//	programObj.LoadTreeNodes().then(() =>
				//	{
					// Match a ProgramNode.
						var programNodeObj = new vilarity.ProgramNode();
						this.currentProgramNode            = null;//programNodeObj;
						$route.matched[0].meta.programNode = null;//programNodeObj;

						for (let j = 6; j >= 1; --j)
						{
							var nodeKey = $route.params['nodeKey'+j];
							if (nodeKey === undefined) {
								continue;
							}

						// Make it reactive.
						//	Vue.observable($route.matched[0].meta);

						// Load the program node.
							var programNode = programObj.nodesByKey[nodeKey];
							if (programNode == undefined || programNode == null) {
								continue;
							}
						// Test the program node access.
							if (this.HasProgramAccess        (this.viewAccount,programNode) == false ||
								this.HasProgramAncestorAccess(this.viewAccount,programNode) == false)
							{
							//	console.log('Access denied.');
								continue;
							}
						// Use the accessible node.
							this.currentProgramNode            = programNode;
							$route.matched[0].meta.programNode = programNode;
						//	programObj.GetNode(nodeKey).then((obj) => {
						//		$route.matched[0].meta.programNode = obj;
						//	});
							break;
						}
				//	});
					break;
				}
			}
		}},

		bLoggedIn : function(to, from)
		{
			if (this.bLoggedIn === false)
				this.$store.commit('vilarity/account',new vilarity.Account());
		},
		sessionUser : { deep: true, handler: function(to, from)
		{
		// Manage the current Vilarity account when the session user changes.
			if (this.sessionUser === null ||
				(this.account.objId && this.sessionUser.objId == 0))
			{
				console.log('Session cleared.');
			//	this.account = null;
				this.$store.commit('vilarity/account',new vilarity.Account());
			}
			else
			{
				if (this.account.user !== undefined &&
					this.sessionUser.objId &&
					(this.account.user       == this.sessionUser.objId ||
					 this.account.user.objId == this.sessionUser.objId))
				{
				// Session user didn't actually change.
					console.log('Session still active.');
					return;
				}
				if (this.sessionUser.objId == 0)
				{
				// Clear the session.
					console.log('No session.');
					this.$store.commit('vilarity/account',new vilarity.Account());
					return;
				}

			// Load the account for the session user.
				console.log('Session active.');
				this.$store.commit('vilarity/account',new vilarity.Account());
				this.__dataReadyPromises.push(
				new Promise((resolve, reject) =>
				{
					this.ui.bShowLoadingThrobber += 1;
					return this.LoadSessionAccount().then((obj) =>
					{
						this.$store.commit('vilarity/account',obj);
						if (this.account)
						{
						// Load the account data.
							return this.LoadDataAccount();
						}
					}).then(() =>
					{
						this.ui.bShowLoadingThrobber -= 1;
						resolve(this.account);
					}).catch((obj) =>
					{
						console.log('Account not found.');
					});
				}));
			}
		}}
	},

	beforeCreate : function()
	{
	// Reference the app.
		vilarity.app = this;

	// Allow vilarity classes to access the Vuex store.
		vilarity.$store = this.$store;

	// Setup the store.
		this.$store.registerModule('vilarity',vilarity.storeSpec);

		this.$store.RegisterObjectEndpoint(vilarity.Account,'api/v1/account');
		this.$store.RegisterObjectEndpoint(vilarity.Group,'api/v1/group');
		this.$store.RegisterObjectEndpoint(vilarity.GroupMember,'api/v1/group/member');
		this.$store.RegisterObjectEndpoint(vilarity.Program,'api/v1/program');
		this.$store.RegisterObjectEndpoint(vilarity.ProgramNode,'api/v1/program/node');
		this.$store.RegisterObjectEndpoint(vilarity.ProgramRecord,'api/v1/program/record');

		this.$store.vilarity = {};
		this.$store.vilarity.GetPrograms = this.GetPrograms;
	},

	created : function()
	{
	// Update the data when the route changes.
		var vm = this;
		var OnRouteChange_SetData = function(to,from)
		{
		}
		vm.$router.afterEach(OnRouteChange_SetData);

	// Load data boot strapped with the initial app load.
		this.__dataReadyPromises = [];
		this.__dataReadyPromises.push(new Promise((resolve, reject) =>
		{
			this.ui.bShowLoadingThrobber += 1;
			this.LoadBootstrapData().then((data) =>
			{
				this.ui.bShowLoadingThrobber -= 1;
				resolve(data);
			});
		}));

	// Delay routing until mandatory program data is loaded.
		vm.$router.beforeEach((to, from, next) =>
		{
		// Wait for all dataReady promises.
			WaitForDataReady = function(promises)
			{
				if (promises.length === 0)
				{
				// Data is ready. Unleash the router.
					next();
				}
				else
				{
				// There are more dataReady promises waiting.
					return promises.pop().then(() => WaitForDataReady(promises))
					.catch(e => {
					// Handle error
						return Promise.reject('Required data failed to load');
					});
				}
			};
			WaitForDataReady(this.__dataReadyPromises);
		});

	// Add the app routes.
		this.AddRoutes(vilarity_routes);

	// Setup the webapp panels.
		this.AddPanelConfig('program-node-panel-1',1,1);
		this.AddPanelConfig('program-node-panel-2',2,1);
	},

	mounted : function()
	{
	},

	methods :
	{
		LoadBootstrapData : function()
		{
			var promises = [];

			var programs = [];
			var groups   = [];

		// Load the initial settings.
			var dataNode = document.getElementById('vilarity.api#settings');
			if (dataNode == null ||
				dataNode.textContent == '')
			{
				console.log('No initial settings.');
			}
			else
			{
				var dataParsed = JSON.parse(dataNode.textContent);
				if (dataParsed.data)
				{
					this.$store.commit('vilarity/settings',dataParsed.data);
				}
			}

		//	vilarity.api#programs
		// Load the initial Programs.
			var dataNode = document.getElementById('vilarity.api#programs');
			if (dataNode == null ||
				dataNode.textContent == '')
			{
				console.log('No initial Programs.');
			}
			else
			{
				var dataParsed = JSON.parse(dataNode.textContent);
				if (dataParsed.data)
				{
				// Convert Object to vilarity.Program
					for (var key in dataParsed.data)
					{
					// TODO: Map objId/UUID to referenced objects already in the store?
						var obj = new vilarity.Program();
						obj.SetProperties(dataParsed.data[key]);
						programs[key] = obj;

					// Load the entire program.
						if (obj.childNodes.length === 0) {
							promises.push(obj.LoadTreeNodes());
						}
					}
				}
			}

		//	vilarity.api#groups
		// Load the initial Groups.
			var dataNode = document.getElementById('vilarity.api#groups');
			if (dataNode == null ||
				dataNode.textContent == '')
			{
				console.log('No initial Groups.');
			}
			else
			{
				var dataParsed = JSON.parse(dataNode.textContent);
				if (dataParsed.data)
				{
				// Convert Object to vilarity.Group
					for (var key in dataParsed.data)
					{
					// TODO: Map objId/UUID to referenced objects already in the store?
						var obj = new vilarity.Group();
						obj.SetProperties(dataParsed.data[key]);
						groups[key] = obj;
					}
				}
			}

		// Wait for all promises to resolve.
			return new Promise((resolve,reject) =>
			{
			// Update the store when the entire Program list is loaded.
				Promise.all(promises).then((values) =>
				{
				// Store the bootstrap data.
					this.$store.commit('vilarity/programs',programs);
					this.$store.commit('vilarity/groups'  ,groups);

				// The initial store data is now completely loaded.
					resolve({'programs':programs, 'groups':groups});
				});
			});
		},

		LoadSessionAccount : function()
		{
		// Load the account associated with the user session.
			var promise = axios.get('api/v1/account/session'/*.'?start=0&limit=10&keyed=0&resolve=0'*/,{params:
			{
			}})
			.then((response) =>
			{
				var apiReply = response.data;
				if (apiReply && apiReply.response && apiReply.response.data && apiReply.response.data)
				{
					let data = apiReply.response.data;
					if (data == null)
						return null;
					if (data.account.user       == this.sessionUser.objId ||
						data.account.user.objId == this.sessionUser.objId)
					{
					// Match the account to the session user.
						data.account.user  = this.sessionUser;
					}
					var obj = new vilarity.Account();
						obj.SetProperties(data.account);
						obj.groups = data.account.groups;
						obj.id = obj.objId;
					return obj;
				}
			});
			return promise;
		},

		LoadDataAccount : function()
		{
			var vm       = this;
			var sequence = null;

			return Promise.resolve();
		},

		SetEffectiveAccount : function(obj)
		{
			if (obj == null) {
				obj = new vilarity.Account();
			}
			this.effectiveAccount = obj;

		// Leave edit mode.
			this.$root.ui.mode = '';

		// Redirect to the program or list of accounts.
			if (obj.objId != 0) {
				this.$router.push('/');
			}
			else {
				this.$router.push('/accounts');
			}
		},

		SetEffectiveGroup : function(obj)
		{
			if (obj == null) {
				obj = new vilarity.Group();
			}
			this.effectiveGroup = obj;

		// Leave edit mode.
			this.$root.ui.mode = '';

		// Redirect to the program or list of groups.
			if (obj.objId != 0) {
				this.$router.push('/');
			}
			else {
				this.$router.push('/groups');
			}
		},

		GetPrograms : function()
		{
			var objs = [];
			var qObj = new vilarity.Program();
			var promise = qObj.Find(null,null);
			promise.then((objs) =>
			{
				console.log(objs);
				this.programs = objs;
			});
			return promise;
		},

		GetCardStyleClass : function(programObj)
		{
			if (programObj.cardColor == '') {
				return '';
			}
			return 'vilarity-card-'+programObj.cardColor;
		},

		GetVilarityPanelCssClass(panelName)
		{
			var cssClasses = this.GetPanelCssClass(panelName);
			if (this.$root.viewport.width > 768)
			{
				cssClasses += ' webapp-panel-right';
			}
			else
			{
				cssClasses += ' webapp-panel-bottom';
			}
			return cssClasses;
		},

		GetVilarityPanelPosition(panelName)
		{
			if (this.$root.viewport.width > 768)
			{
				return 'right';
			}
			else
			{
				return 'bottom';
			}
		},

		GetVilarityPanelResizerPosition(panelName)
		{
			if (this.$root.viewport.width > 768)
			{
				return 'right';
			}
			else
			{
				return 'bottom';
			}
		},

		HasProgramAccess : function(accountObj,programObj)
		{
		// Group Visibility: -1 (none), 0 (all groups), [groupId] (specific group by ID)

		// Admins should always be able to see content.
			if (accountObj.user.IsAllowed('vilarity.admin')) {
				return true;
			}

		// Invisible except to admins:
			if (programObj.groupVisibility == -1) {
				return false;
			}

		// Any Vilarity group (or no group):
			if (programObj.groupVisibility == 0) {
				return true;
			}

		// Specific Vilarity group given:
			if (programObj.groupVisibility > 0)
			{
			// Is the account in the Vilarity group?
			// Check each group on the account.
				if (accountObj.groups && accountObj.groups.length > 0)
				{
					for (var i = 0; i < accountObj.groups.length; ++i)
					{
						var groupObj = accountObj.groups[i];
						if (groupObj.objId == programObj.groupVisibility) {
							return true;
						}
					}
				}
			// Check the legacy "primary" group, too.
				if (accountObj.group       == programObj.groupVisibility ||
					accountObj.group.objId == programObj.groupVisibility) {
					return true;
				}
				return false;
			}

		// Nothing above passed, so restrict access (except for admins):
			return false;
		},

		HasProgramAncestorAccess : function(account,programNode)
		{
		// Test the full ancestory path of a program node is accessible.
		// Note: This does not test the access of the passed node, only the parent chain.
			var testNode = programNode;
			while (testNode &&
			       testNode.objId != 0)
			{
			// Walk up the parent path.
				if (testNode.parentNode == null ||
					testNode.parentNode.objId == 0)
				{	testNode = testNode.program;	}
				else
				{	testNode = testNode.parent;	}
				if (testNode == undefined || testNode == null)
				{
				// Nothing left to test.
					break;
				}

				if (this.HasProgramAccess(account,testNode) == false)
				{
				//	console.log('Access denied.');
					return false;
				}
			}
			return true;
		},

		FilterByProgramAccess : function(accountObj,programList)
		{
			if (programList instanceof Array === false) {
				return [];
			}
			return programList.filter((obj)=>
			{
				return (this.HasProgramAccess(accountObj,obj));
			});
		},

		OnSaveProgram : function(program)
		{
		// Index the node on the tree level.
		//	this.program.IndexNode(program);
		//	this.program.SetupNodeReferences(program);

		// Sanitize the key.
			var key    = program.key;
			var keyNew = program.SanitizeKey();
			if (key != keyNew)
			{
				UIkit.modal.alert('<p>The URL link ' + (program.title != '' ? 'for <strong>' + program.title + '</strong> ' : '') +
				                  'has been updated to fit the required format.</p>' +
				                  '<p>From: ' + key + '<br/>To: ' + keyNew + '</p>');
			}

		// Manage the route.
			if (this.$route                      && this.$route.meta &&
				this.$route.meta.program != null && this.$route.meta.programNode == null &&
				this.$route.meta.program.UUID == program.UUID)
			{
				var newPath = '/'+program.key;
				if (this.$route.path != newPath)
				{	this.$router.replace(newPath);	}
			}

		// Save the changes.
			return program.Save();
		},

		OnSaveProgramNode : function(programNode)
		{
		// Index the node on the tree level.
			if (programNode.program)
			{
				programNode.program.IndexNode(programNode);
				programNode.program.SetupNodeReferences(programNode);
			}

		// Sanitize the key.
			var key    = programNode.key;
			var keyNew = programNode.SanitizeKey();
			if (key != keyNew)
			{
				UIkit.modal.alert('<p>The URL link ' + (programNode.title != '' ? 'for <strong>' + programNode.title + '</strong> ' : '') +
				                  'has been updated to fit the required format.</p>' +
				                  '<p>From: ' + key + '<br/>To: ' + keyNew + '</p>');
			}

		// Manage the route.
			if (this.$route                          && this.$route.meta &&
				this.$route.meta.programNode != null && programNode.program != null &&
				this.$route.meta.programNode.UUID == programNode.UUID)
			{
				var newPath = '/'+programNode.program.key+'/'+programNode.key;
				if (this.$route.path != newPath)
				{	this.$router.replace(newPath);	}
			}

		// Save the changes.
			return programNode.Save();
		}
	}
});

//vilarity.app.$mount(document.querySelector('#app'));
