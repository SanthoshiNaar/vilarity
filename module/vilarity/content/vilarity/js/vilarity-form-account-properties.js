ss.vue.components['vilarity']['vilarity-form-account-properties'] = Vue.extend(
{
	name       : 'vilarity-form-account-properties',
	extends    : ss.vue.components['user']['ss-user-form-user-properties'],
//	mixins     : [ss.vue.mixins['ss\\Form']],
	template   : ss.vue.templates['vilarity/vue/components/vilarity-form-account-properties'],
	components : ss.vue.components['user'],

	props : {
		mode    : {type: String, default: 'edit'},
		account : vilarity.Account,
		user    : ss.user.User
	},

	data : function()
	{
		return {
			form :
			{	method : 'POST',
				action : this.SubmitForm,
				fields :
				{
					vilarityGroups : {property: 'groups', type: 'checkbox', name: 'vilarityGroups', value: [], label: 'Vilarity Groups', options: {}, required: false, errors: {}}
				},
				messages : {},
				errors   : {}
			}
		}
	},

	computed :
	{
		vilarityGroups: function() {
			return this.$store.state.vilarity.groups;
		}
	},

	watch :
	{
		$route : function()
		{
			this.ResetForm();
			this.ImportObjectIntoFormFields(this.user);
			this.ImportAccountIntoFormFields();
		},

		account : function()
		{
			this.ImportAccountIntoFormFields();
		},

		user : function()
		{
			this.ImportObjectIntoFormFields(this.user);
		}
	},

	created : function()
	{
	},

	mounted : function()
	{
		this.ResetForm();
		this.ImportObjectIntoFormFields(this.user);
		this.ImportAccountIntoFormFields();
	},

	methods:
	{
		ImportAccountIntoFormFields : function()
		{
			var primaryGroup = this.vilarityGroups.find((obj) => (obj.objId == this.account.group.objId));
			if (primaryGroup == undefined)
				this.form.fields['vilarityGroups'].value = [];
			else
				this.form.fields['vilarityGroups'].value = [this.account.group.objId];

			var groups   = this.account.groups;
			var groupIds = this.form.fields['vilarityGroups'].value;
			for (var i = 0; i < groups.length; ++i)
			{
				var groupObj = groups[i];
				if (groupObj.objId == this.account.group.objId) {
					continue; // already handled
				}
			//	var idx = this.vilarityGroups.findIndex((obj) => (obj.objId == groupObj.objId));
			//	if (idx != undefined) {
					groupIds.push(groupObj.objId);
			//	}
			}
			this.form.fields['vilarityGroups'].value = groupIds;
		},

		ExportFormFieldsIntoAccount : function()
		{
			var groupIds  = this.form.fields['vilarityGroups'].value.sort();
			var groups    = [];
			for (var i = 0; i < groupIds.length; ++i) {
				var groupObj = this.vilarityGroups.find((obj) => (obj.objId == groupIds[i]));
				if (groupObj) {
					groups.push(groupObj);
				}
			}
			this.account.groups = groups;

		// Update the old single group to the first one, too.
			if (this.account.groups.length === 0)
				this.account.group = null;
			else
				this.account.group = this.account.groups[0];
		},

		SubmitForm : function()
		{
			this.form.messages = {};

			this.ExportFormFieldsIntoObject(this.user);
			this.ExportFormFieldsIntoAccount();

			var bNewUser = this.user.objId == 0 ? true : false;

			var emitData =
			{
				component: this,
				form: this.form,
				account: this.account,
				user: this.user,
				formMode: bNewUser ? 'new' : 'edit'
			};

			this.user.Save().then(() =>
			this.account.Save().then(() =>
			{
				var msg = 'The account has been updated.';
				if (bNewUser) {
					msg = 'The account has been created.';
				}
				if (this.sessionUser &&
					this.sessionUser.objId == this.user.objId)
				{	msg = 'Your information has been updated.';	}
				this.form.messages = { 100 : msg };

				this.$emit('form_success',emitData);
				this.$router.push('/accounts/'+this.account.objId);
			})).
			catch((e) =>
			{
				console.log(e);
				this.$emit('form_error',emitData);
			});
		}
	}
});
