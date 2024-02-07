ss.vue.components['vilarity']['view-accounts'] = ss.vue.components['vilarity']['object-list'].extend(
{
	name       : 'view-accounts',
//	mixins     : ss.vue.mixins['vilarity']['object-list'],
	template   : ss.vue.templates['vilarity/templates/view/accounts'],
	components : {...ss.vue.components['user'], ...ss.vue.components['vilarity']},

	props :
	{
		objectClass : false,

		objectListTemplate : {type: String, default: 'accounts'}
	},

	data : function()
	{
		return {
			currentAccountId : 0,
			currentAccount : new vilarity.Account(),

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
						{	templateBase : 'vilarity/templates/view/accounts',
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
		bLoggedIn : function() {
			return this.$store.state.user.bLoggedIn;
		},
		sessionUser : function() {
			return this.$store.state.user.sessionUser;
		},

		accounts : function() {
			return this.objectList.objects;
		},

		effectiveAccount : function() {
			return this.$root.effectiveAccount;
		}
	},

	watch :
	{
		'$route.params.objId' : function(value,oldValue)
		{
			console.log(value);
			this.SetCurrentAccount(value);
		},

		'accounts' : function()
		{
			this.SetCurrentAccount(this.currentAccountId);
		}
	},

	created : function()
	{
	// Define the Object class to work with.
		this.objectList.objectClass = vilarity.Account;
	},

	beforeMount : function()
	{
		console.log(this.$route.params.objId);
		this.SetCurrentAccount(this.$route.params.objId);
	},

	methods :
	{
		SetCurrentAccount : function(accountId)
		{
			this.currentAccountId = accountId;
			if (!accountId)
			{
			// No account set.
				this.currentAccountId = 0;
				this.currentAccount = new vilarity.Account();
			}
			else
			{
			// Find a specific account.
				for (var i = 0; i < this.accounts.length; ++i)
				{
					var obj = this.accounts[i];
					if (obj.objId == accountId)
					{
						this.currentAccountId = obj.objId;
						this.currentAccount = obj;

						Promise.all([this.currentAccount.user._ps]).then(() => {
							this.ui.busy.loading = false;
						});
						return this.currentAccount;
					}
				}
			}
			return this.currentAccount;
		},

		OnNewAccount : function(data)
		{
			if (data.formMode != 'new')
			{
				return;
			}

			this.currentAccount.user = data.user;
		// Note: The form saves the account.
		//	this.currentAccount.Save().then(() =>
		//	{
				this.$router.push('/accounts');
				this.LoadObjects();
		//	});
		}
	}
});
