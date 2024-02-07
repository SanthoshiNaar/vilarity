ss.vue.components['vilarity']['vilarity-account'] = Vue.extend(
{
	name       : 'vilarity-account',
//	mixins     : [ss.vue.mixins['vilarity']['object']],
	mixins     : [ss.vue.mixins['ss-vue-component']],
	template   : ss.vue.templates['vilarity/templates/view/account'],
	components : {...ss.vue.components['user'], ...ss.vue.components['vilarity']},

	props :
	{
		account : vilarity.Account
	},

	data : function()
	{
		return {
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
					{	init :
						{	renderTemplate : 'route'
						},
						render :
						{	templateBase : 'vilarity/templates/view/account',
							template     : ''
						}
					},
				}
			}
		}
	},

	computed :
	{
		bLoggedIn : function() {
			return this.$store.state.user.bLoggedIn;
		},
		sessionUser : function() {
			return this.$store.state.user.sessionUser;
		}
	},

	watch :
	{
		$route : function()
		{
		},
		'account' : function()
		{
			this.LoadAccountFully();
		}
	},

	created : function()
	{
	},

	beforeMount : function()
	{
		this.LoadAccountFully();
	},

	methods :
	{
		LoadAccountFully : function()
		{
			if (!this.account)
			{
			// TODO: Log it.
				return false;
			}
			else
			{
				Promise.all([this.account.user._ps]).then(() => {
					this.ui.busy.loading = false;
				});
				return this.account;
			}
		}
	}
});
