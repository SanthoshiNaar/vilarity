ss.vue.components['vilarity']['view-groups'] = ss.vue.components['vilarity']['object-list'].extend(
{
	name       : 'view-groups',
//	mixins     : ss.vue.mixins['vilarity']['object-list'],
	template   : ss.vue.templates['vilarity/templates/view/groups'],
	components : {...ss.vue.components['user'], ...ss.vue.components['vilarity']},

	props :
	{
		objectClass : false,

		objectListTemplate : {type: String, default: 'groups'}
	},

	data : function()
	{
		return {
			currentGroupId : 0,
			currentGroup : new vilarity.Group(),

			ui :
			{
				busy : {
					loading : true,
				},
				mode : 'view' // 'view' | 'edit'
			},

			ss :
			{	vue :
				{	component :
					{	render :
						{	templateBase : 'vilarity/templates/view/groups',
							template     : ''
						},
						init :
						{	renderTemplate : 'route'
						}
					}
				}
			}
		}
	},

	computed :
	{
		groups : function() {
			return this.objectList.objects;
		},

		effectiveGroup : function() {
			return this.$root.effectiveGroup;
		}
	},

	watch :
	{
		'$route.params.objId' : function(value,oldValue)
		{
			console.log(value);
			this.SetCurrentGroup(value);
		},

		'groups' : function()
		{
			this.SetCurrentGroup(this.currentGroupId);
		}
	},

	created : function()
	{
	// Define the Object class to work with.
		this.objectList.objectClass = vilarity.Group;
	},

	beforeMount : function()
	{
		console.log(this.$route.params.objId);
		this.SetCurrentGroup(this.$route.params.objId);
	},

	methods :
	{
		Debug(obj)
		{
			debugger;
		},

		SetCurrentGroup : function(groupId)
		{
			this.currentGroupId = groupId;
			if (!groupId)
			{
			// No group set.
				this.currentGroupId = 0;
				this.currentGroup = new vilarity.Group();
			}
			else
			{
			// Find a specific group.
				for (var i = 0; i < this.groups.length; ++i)
				{
					var obj = this.groups[i];
					if (obj.objId == groupId)
					{
						this.currentGroupId = obj.objId;
						this.currentGroup = obj;

						Promise.all([this.currentGroup._ps]).then(() => {
							this.ui.busy.loading = false;
						});
						return this.currentGroup;
					}
				}
			}
			return this.currentGroup;
		},

		OnNewGroup : function(data)
		{
			if (data.formMode == 'new')
			{
			// Note: The form saves the account.
			//	var newGroup = new vilarity.Group();
			//	newGroup.title = data.title;
			//	newGroup.Save().then(() =>
			//	{
					this.$router.push('/groups');
					this.LoadObjects();
			//	});
			}
		}
	}
});
