ss.vue.components['vilarity']['vilarity-program'] = Vue.extend(
{
	name       : 'vilarity-program',
//	mixins     : [ss.vue.mixins['ssobject:vilarity\\Program']],
	mixins     : [ss.vue.mixins['ss\\Object']],
	template   : ss.vue.templates['vilarity/components/program'],
	components : ss.vue.components['vilarity'],

	props : {
	//	'program' : Object
	},

	data : function()
	{
		return {
			ui : {
				edit: {}
			}
		}
	},

	computed :
	{
		settings : function() {
			return this.$store.state.vilarity.settings;
		},

		program : function()
		{
			return this.object;
		}
	},

	watch :
	{
		'object' : function(to,from)
		{
		}
	},

	created : function()
	{
	},

	mounted : function()
	{
	},

	methods:
	{
		GetProgramTerm : function(programObj)
		{
		// Level 0:
			return this.settings.contentLevels['program'].label;
		}
	}
});
