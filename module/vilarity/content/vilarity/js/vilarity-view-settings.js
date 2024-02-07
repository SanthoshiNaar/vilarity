ss.vue.components['vilarity']['view-settings'] = Vue.extend(
{
	template   : ss.vue.templates['vilarity/templates/view/settings'],
	components : ss.vue.components['vilarity'],

	data : function()
	{
		return {
			contentLevels : this.$store.state.vilarity.settings.contentLevels
		}
	},
	computed :
	{
	},

	watch :
	{
		'contentLevels' : { deep: true, handler : function(value)
		{
			this.SaveContentLevels({contentLevels: value}).then(()=>
			{
				this.$store.commit('vilarity/settings/contentLevels',value);
			});
		}}
	},

	beforeCreate : function()
	{
	},

	methods :
	{
		SaveContentLevels : function(config)
		{
			return axios.post('api/v1/settings',config);
		}
	}
});
