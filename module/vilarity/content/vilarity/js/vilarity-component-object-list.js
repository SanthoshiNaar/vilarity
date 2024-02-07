ss.vue.components['vilarity']['object-list'] = Vue.extend(
{
	mixins : [ss.vue.mixins['ss-vue-component']],

	components :  {...ss.vue.components['user'], ...ss.vue.components['vilarity']},

	props :
	{
		objectClass : false,
	},

	data : function()
	{
		return {
			objectList :
			{
				objectClass : this.$props.objectClass,

				objects  : [],
				dataName : 'objectList.objects',

				curPage   : 0,
				pageCount : 0,

				bShowActions : true,

				apiLoad   : false,
				apiSave   : false,
				apiDelete : false
			},

			ss :
			{
				route :
				{
					basePath : '',
					parentPath : '',
					currentPath : '',

					objectPath : '',
					objectsPath : ''
				},

				vue :
				{
					component :
					{
						render :
						{	templateBase : 'vilarity/vue/components/object-list',
							template     : ''
						},
						init :
						{	renderTemplate : 'route'
						}
					}
				}
			}
		};
	},

	template : ss.vue.templates['vilarity/vue/components/object-list'],

	computed :
	{
	},

	watch :
	{
		'$route' : { deep: true, handler: function(to, from)
		{
			this.SetupComponentFromRoute(this.$route);
		}}
	},

	beforeMount : function()
	{
		this.InitPrivateVars();

		if (this.$route) {
			this.SetupComponentFromRoute(this.$route);
		}

	// Start the initial load.
		this.LoadObjects();
	},

	methods :
	{
		SetupComponentFromRoute : function($route)
		{
			var $route        = $route;
			var templateName  = '';
			var routeMatchIdx = $route.matched.length-1;
			for (var i = routeMatchIdx; i >= 0; --i)
			{
				if ($route.matched[i].meta.objectListTemplate)
				{
					templateName = $route.matched[i].meta.objectListTemplate;
					break;
				}
			}
			if (templateName)
			{
				this.ss.vue.component.render.template = templateName;
			}

		// Setup navigation paths based on the route.
			this.ss.route.currentPath = $route.fullPath;
			if (this.$route.matched.length)
			{
				this.ss.route.basePath   = $route.matched[0].path;
				this.ss.route.parentPath = $route.matched[0].path;
			}
			if (routeMatchIdx >= 2)
			{
				this.ss.route.parentPath = this.ss.route.basePath + '/' + $route.params.objId;
			}
		},

		InitPrivateVars : function()
		{
			if (this.objectList.objects._ === undefined)
			{
				Object.defineProperty(this.objectList.objects,'_',
				{
					configurable : false,
					enumerable   : false,
					writable     : false,
					value        :
					{
						objStatus : '',
						objCount  : 0
					}
				});
			}
		},

		LoadObjects()
		{
		// Load the objects.
			this.objectList.objects._.objStatus = 'loading';
			var obj = new this.objectList.objectClass();
			return obj.Find().then((objs) =>
			{
				this.objectList.objects = objs;

				this.InitPrivateVars();
				this.objectList.objects._.objStatus = 'loaded';

				if (objs instanceof Array)
					this.objectList.objects._.objCount = objs.length;
				else
					this.objectList.objects._.objCount = Object.keys(objs).length;
			});
		}
	}
})
