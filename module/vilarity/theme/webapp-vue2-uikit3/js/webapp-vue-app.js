(function()
{

//Vue.use(Vuex);
//Vue.use(VueRouter);

/***************************************************************************
 * user-webapp
 ***************************************************************************/
var userComponents =
{
	...ss.vue.components['user']
};
userComponents['ss-user-user_login'] = Vue.extend(
{
	template : ss.vue.templates['components/user_login'],

	mounted : function()
	{
	}
});
userComponents['ss-user-user_password_reset'] = Vue.extend(
{
	template : ss.vue.templates['components/user_password_reset']
});

var userApp = ss.vue.SetupApp('user-webapp',
{
	components: userComponents,
	data : function()
	{
		return {
			'user' : {
				'config' : {
					'allowInactiveUsers' : true // Allow users to login when inactive/unverified?
				},
				'busy' : {
					'login'  : false,
					'logout' : false,
				}
			},
		//	'app' :
		//	{
				'forms' :
				{
					'user/login' :
					{
						busy   : false,
						fields : {
							'username' : '',
							'password' : '',
							'persist'  : false
						},
						errors : {
						}
					}
				}
		//	},
		};
	},

	watch :
	{
		'$store.state.user.sessionUser' : function(value)
		{
			if (value != null && this.bUserAuth == false)
				this.$store.commit('user/LOGIN',value);
			if (value == null && this.bUserAuth == true)
				this.$store.commit('user/LOGOUT');
		}
	},

	computed :
	{
		bUserAuth : function()
		{
			return this.$store.getters['user/bAuth'];
		}
	},

	created : function()
	{
		this.UserSessionStartMonitor();
	},

	methods :
	{
		UserLogin : function(user,pass)
		{
			let form = this.forms['user/login'];
			form.errors = {};
			form.busy = true;

			var start = (new Date()).getTime();
			let OnSuccess = (response) =>
			{
				var end   = (new Date()).getTime();
				var delay = (500 - (end - start));
			// Provide a brief "login success" message before changing the view.
			//	form.errors = {0 : 'Login Success!'};
				let vm = this;
				setTimeout(() =>
				{
					var user = response.data.user;

				//	vm.$store.commit('user/sessionUser',user);
					vm.$store.commit('user/LOGIN',user);
				//	vm.$store.dispatch('SetView','app');
					form.busy = false;
					form.fields.password = '';
					form.errors = {};

				// Allow inactive/unverified users to log in?
					if (vm.user.config.allowInactiveUsers == false &&
						user &&
						parseInt(user.status) === 0)
					{
					// Report it.
						form.errors['2'] = 'Account is inactive.';
						console.log('User status is inactive. Logging out.');
					// Do not switch to the user.
						vm.$store.commit('user/LOGIN',null);
					// Force the user to logout.
						vm.UserLogout();
					}

					vm.user.busy.login = false;
				},(delay > 0 ? delay : 0));
			};
			let OnError = (response) =>
			{
				form.busy   = false;
				form.errors = response.errors;

				this.user.busy.login = false;
			};

		// POST the API request.
			var formPostData = new FormData();
			for (var fieldName in form.fields)
			{	formPostData.append(fieldName,form.fields[fieldName]);	}

			this.user.busy.login = true;
			axios.post('api/v1/user/login',formPostData,{headers : { 'content-type': 'application/x-www-form-urlencoded' }})
			  .then(function (response)
			{
			//	console.log(response);
				var apiReply = response.data;
				if (!apiReply || apiReply.response.errors.length != 0)
				{	return OnError(apiReply.response);	}
				return OnSuccess(apiReply.response);
			})
			.catch(function (response)
			{	return OnError(response);	});
		},

		UserLogout : function()
		{
			var start = (new Date()).getTime();
			let OnSuccess = (response) =>
			{
				var end   = (new Date()).getTime();
				var delay = (500 - (end - start));
				let vm = this;
				setTimeout(() =>
				{
					var user = response.data.user;
					this.$store.commit('user/LOGOUT');

					this.user.busy.logout = false;
				},(delay > 0 ? delay : 0));
			};
			let OnError = (response) =>
			{
			// Describe the logout error.
				this.user.busy.logout = false;
			};

		// POST the API request.
			this.user.busy.logout = true;
			axios.post('api/v1/user/logout')
			  .then(function (response)
			{
			//	console.log(response);
				var apiReply = response.data;
				if (!apiReply || apiReply.response.errors.length != 0)
				{	return OnError(apiReply.response);	}
				return OnSuccess(apiReply.response);
			})
			.catch(function (response)
			{	return OnError(response);	});
		},

		ShowUserLogin : function()
		{
		//	this.$store.commit('user/USER_LOGOUT');
			this.$store.dispatch('SetView','user_login');
		},

		ShowUserPasswordReset : function()
		{
		//	this.$store.commit('user/PASSWORD_RESET');
			this.$store.dispatch('SetView','user_password_reset');
		},

		UserSessionStartMonitor : function()
		{
			setInterval(() =>
			{
				this.DoUserSessionUpdate();
			},60*1000);
		},
		DoUserSessionUpdate : function()
		{
			let OnSuccess = (response) =>
			{
				var user = response.data.user;
				this.$store.commit('user/sessionUser',user);

			// Allow inactive/unverified users to log in?
				if (this.user.config.allowInactiveUsers == false &&
					user &&
					parseInt(user.status) === 0)
				{
				// Report it.
					console.log('Session user status is inactive. Logging out.');
				// Remove the user from the sesesion.
					this.$store.commit('user/sessionUser',null);
				// Force the user to logout.
					this.UserLogout();
				}
			};
			let OnError = (response) =>
			{
			// Describe the error.
				this.$store.commit('user/sessionUser',null);
			};

			axios.post('api/v1/user/session')
			  .then(function (response)
			{
			//	console.log(response);
				var apiReply = response.data;
				if (!apiReply || apiReply.response.errors.length != 0)
				{	return OnError(apiReply.response);	}
				return OnSuccess(apiReply.response);
			})
			.catch(function (response)
			{	return OnError(response);	});
		}
	}
});

/***************************************************************************
 * webapp-vue
 ***************************************************************************/
var webappApp = ss.vue.SetupApp('webapp-vue',['user-webapp'],
{
//	el        : '#app',
	store     : ss.vue.stores['webapp'] ? ss.vue.stores['webapp'] : null,
	mixins    : Object.values(ss.vue.mixins['webapp-vue']),
	components: ss.vue.components['webapp-vue'],
	router    : router,

	data  : function() {
		return {
			baseTitle : document.title,
			navTitle  : '',

			viewport :
			{
				width : undefined,
				height: undefined
			}
		};
	},

	beforeCreate : function()
	{
	// Setup the store.
		this.$store.RegisterObjectEndpoint(ss.user.User,'api/v1/user');
		this.$store.commit('user/sessionUser',new ss.user.User()); // init the sessionUser with a $store connection
	},

	created : function()
	{
	// Update data from the current route.
		var vm = this;
		var OnRouteChange_SetData = function(to,from)
		{
		// Set the theme navbar title from the route.
			var navTitle = '';
			for (var i = to.matched.length; i >= 0; --i)
			{
			// Find the closest match that has a title and use it.
				var match = to.matched[i];
				if (match && match.meta && match.meta.navTitle)
				{
					navTitle = match.meta.navTitle;
					if (navTitle instanceof Function)
						navTitle = navTitle(vm,match,to,from);
					break;
				}
			}
			if (vm.navTitle != navTitle)
			{	vm.navTitle  = navTitle;	}
		};
		vm.$router.afterEach(OnRouteChange_SetData);

	// Trigger an initial "ready" event on the router.
	// Note: This is not really necessary since there aren't any routes, yet.
	//	vm.$router.onReady(function(){ OnRouteChange_SetData(vm.$router.currentRoute);});

	// Continue a session provided with the initial download.
		var userSessNode = document.getElementById('ss.api#user/session');
		if (userSessNode == null ||
			userSessNode.textContent == '')
		{
			console.log('No initial user session.');
		}
		else
		{
			var sessionUser = JSON.parse(userSessNode.textContent);
			if (sessionUser.data)
				sessionUser = sessionUser.data.user;
			this.$store.commit('user/LOGIN',sessionUser);

		// Allow inactive/unverified users to log in?
			if (this.user.config.allowInactiveUsers == false &&
				sessionUser &&
				parseInt(sessionUser.status) === 0)
			{
			// Report it.
				console.log('Session user status is inactive. Logging out.');
			// Remove the user from the sesesion.
				this.$store.commit('user/sessionUser',null);
			// Force the user to logout.
				this.UserLogout();
			}
		}
	},

	mounted : function()
	{
	// Listen for viewport resizes.
		if (this.bListeningForResize)
		{
			window.removeEventListener('resize',this.OnViewportResize);
			this.bListeningForResize = undefined;
		}
		window.addEventListener('resize',this.OnViewportResize);
		this.bListeningForResize = true;

		this.UpdateViewportDimensions();
	},

	destroyed : function()
	{
		if (this.bListeningForResize)
		{
			window.removeEventListener('resize',this.OnViewportResize);
			this.bListeningForResize = undefined;
		}
	},

	watch :
	{
		'$store.state.user.bLoggedIn' : function(value)
		{
			if (this.$store.state.user.bLoggedIn)
				this.$store.dispatch('SetView','app');
			else
				this.$store.dispatch('SetView','user_login');

			this.SyncAppTitle();
		},
		'$store.state.view' : function(value)
		{
			console.log(value);
		},

		navTitle : function(value)
		{
			Vue.nextTick(() =>
			{
				this.SyncAppTitle();
			});
		}
	},

	methods :
	{
		OnViewportResize : function(eventObj)
		{
			this.UpdateViewportDimensions();
		},

		UpdateViewportDimensions : function()
		{
			if (this.viewport.width  != window.innerWidth)
				this.viewport.width   = window.innerWidth;
			if (this.viewport.height != window.innerHeight)
				this.viewport.height  = window.innerHeight;
		},

		AddRoutes : function(routes)
		{
		// Set default options for path matching.
			for (var i = 0; i < routes.length; ++i)
			{
				var route = routes[i];
				if (route.pathToRegexpOptions === undefined)
					route.pathToRegexpOptions   = {};
				if (route.pathToRegexpOptions.sensitive === undefined)
					route.pathToRegexpOptions.sensitive   = true;
				if (route.pathToRegexpOptions.strict    === undefined)
					route.pathToRegexpOptions.strict      = true;
			}
		// Add the routes.
			return this.$router.addRoutes(routes);
		},

		SyncAppTitle : function()
		{
			var docTitle = '';
			if (this.$store.state.user.bLoggedIn === true)
			{
			// Signed in.
				if (this.navTitle != '')
					docTitle += this.navTitle;
				if (this.baseTitle != '')
				{
					if (docTitle != '')
						docTitle += routerTitleSeparator;
					docTitle += this.baseTitle;
				}
			}
			else
			{
			// Not logged in.
				docTitle = this.baseTitle;
			}
			document.title = docTitle;
		}
	}
});

})();
