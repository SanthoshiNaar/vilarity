ss.vue.components['webapp-vue'] = {};

// Component: webapp-panel-resizers
ss.vue.components['webapp-vue']['webapp-panel-resizer'] = Vue.extend(
{
	name       : 'webapp-panel-resizer',
	template   : ss.vue.templates['webapp/vue/template/webapp-panel-resizer'],
//	components : ss.vue.components['webapp-vue'],

	props :
	{
		panelName     : String,
		panelPosition : { type: String, default: 'left'  },
		position      : { type: String, default: 'right' }
	},

	data : function()
	{
		return {
			data : {
			}
		}
	},

	computed :
	{
	},

	watch :
	{
	},

	created : function()
	{
	},

	mounted : function()
	{
	},

	methods:
	{
		GetPanelCssClass : function(panelName)
		{
			return this.$root.GetPanelCssClass(panelName);
		},

		CanPanelExpand : function(panelName)
		{
			return this.$root.CanPanelExpand(panelName);
		},

		CanPanelCollapse : function(panelName)
		{
			return this.$root.CanPanelCollapse(panelName);
		},

		OnExpandPanel : function(panelName)
		{
			return this.$root.OnExpandPanel(panelName);
		},

		OnCollapsePanel : function(panelName)
		{
			return this.$root.OnCollapsePanel(panelName);
		}
	}
});
