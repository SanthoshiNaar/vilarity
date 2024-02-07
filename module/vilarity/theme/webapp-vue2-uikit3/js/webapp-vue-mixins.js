ss.vue.mixins['webapp-vue'] = {};

// Component: webapp-panel-resizers
ss.vue.mixins['webapp-vue']['webapp-panel-resizer'] = Vue.extend(
{
	data : function()
	{
		return {
			settings :
			{
				panels :
				{
				//	'panel-1' :
				//	{
				//		name : 'panel-1',
				//		size : { max : 2 }
				//	}
				}
			},

			ui :
			{
				panels :
				{
				//	'panel-1' :
				//	{
				//		size : 1
				//	}
				}
			}
		};
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
		AddPanelConfig : function(panelName,maxSize,initSize)
		{
			Vue.set(this.settings.panels,panelName,
			{
				name : panelName,
				size : { max : maxSize }
			});

			Vue.set(this.ui.panels,panelName,
			{
				size : initSize
			});
		},

		GetPanelCssClass : function(panelName)
		{
			if (this.settings.panels[panelName] === undefined ||
				this.      ui.panels[panelName] === undefined)
			{	console.log('Panel "%s" is not defined.');
				return '';
			}

			return 'webapp-panel webapp-panel-size-'+this.ui.panels[panelName].size;
		},

		CanPanelExpand : function(panelName)
		{
			if (this.settings.panels[panelName] === undefined ||
				this.      ui.panels[panelName] === undefined)
			{	console.log('Panel "%s" is not defined.');
				return false;
			}

			return (this.ui.panels[panelName].size < this.settings.panels[panelName].size.max);
		},

		CanPanelCollapse : function(panelName)
		{
			if (this.settings.panels[panelName] === undefined ||
				this.      ui.panels[panelName] === undefined)
			{	console.log('Panel "%s" is not defined.');
				return false;
			}

			return (this.ui.panels[panelName].size > 0);
		},

		OnExpandPanel : function(panelName)
		{
			if (this.settings.panels[panelName] === undefined ||
				this.      ui.panels[panelName] === undefined)
			{	console.log('Panel "%s" is not defined.');
				return false;
			}

			if (this.CanPanelExpand(panelName))
			{	this.ui.panels[panelName].size += 1;	}
		},

		OnCollapsePanel : function(panelName)
		{
			if (this.settings.panels[panelName] === undefined ||
				this.      ui.panels[panelName] === undefined)
			{	console.log('Panel "%s" is not defined.');
				return false;
			}

			if (this.CanPanelCollapse(panelName))
			{	this.ui.panels[panelName].size -= 1;	}
		}
	}
});
