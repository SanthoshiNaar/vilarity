ss.vue.components['user']['ss-user-form-user-password'] =
ss.vue.components['user'][   'user-form-user-password'] = Vue.extend(
{
	name       : 'ss-user-form-user-password',
//	mixins     : [ss.vue.mixins['ss\\Form']],
	template   : ss.vue.templates['user/vue/template/user-form-user-password'],
	components : ss.vue.components['user'],

	props :
	{
		user     : ss.user.User,
		authUser : ss.user.User
	},

	data : function()
	{
		return {
			form :
			{	method : 'POST',
				action : this.SubmitPasswordChange,
				fields :
				{	passwordCurrent : {type: 'password', name: 'passwordCurrent', value: '', label: 'Current Password', required: true, errors: []},
					password        : {type: 'password', name: 'password'       , value: '', label: 'New Password'    , required: true, errors: []},
					passwordVerify  : {type: 'password', name: 'passwordVerify' , value: '', label: 'Verify Password' , required: true, errors: []}
				},
				messages : {},
				errors   : {}
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
		if (this.authUser       && this.user &&
			this.authUser.objId != this.user.objId)
		{
			this.form.fields.passwordCurrent.label = 'Administrator Password';
		}
	},

	methods:
	{
		SubmitPasswordChange : function()
		{
			this.form.messages = {};
			this.user.ChangePassword(this.form.fields.password.value,
			                         this.form.fields.passwordVerify.value,
			                         this.form.fields.passwordCurrent.value)
			.then((response) =>
			{
				var errors = response.errors;
				var pwResults = response.pwResults;

			// Collect password issues as errors, too.
				if (pwResults.hasOwnProperty('minLength') && pwResults['minLength']['code'] == 'F')
					errors['minLength'] = '✗ '+pwResults['minLength']['msg'];
				if (pwResults.hasOwnProperty('maxLength') && pwResults['maxLength']['code'] == 'F')
					errors['maxLength'] = '✗ '+pwResults['maxLength']['msg'];
				if (pwResults.hasOwnProperty('minLowerCase') && pwResults['minLowerCase']['code'] == 'F')
					errors['minLowerCase'] = '✗ '+pwResults['minLowerCase']['msg'];
				if (pwResults.hasOwnProperty('minUpperCase') && pwResults['minUpperCase']['code'] == 'F')
					errors['minUpperCase'] = '✗ '+pwResults['minUpperCase']['msg'];
				if (pwResults.hasOwnProperty('minNumber') && pwResults['minNumber']['code'] == 'F')
					errors['minNumber'] = '✗ '+pwResults['minNumber']['msg'];
				if (pwResults.hasOwnProperty('minSymbol') && pwResults['minSymbol']['code'] == 'F')
					errors['minSymbol'] = '✗ '+pwResults['minSymbol']['msg'];

				this.AssignFieldErrors(errors);

				if (errors == undefined || errors == null || Object.keys(errors).length == 0)
				{
					this.form.fields.password.value  = '';
					this.form.fields.passwordVerify.value  = '';
					this.form.fields.passwordCurrent.value  = '';

					this.form.messages = { 100 : 'The password has been changed.' };

					this.$emit('form_success',{form: this});
				}
				else
				{
					this.form.messages = errors; //{ '-100' : 'The password could not be changed.' };

					this.$emit('form_error',{form: this});
				}

			// DEBUG:	this.form.messages[101] = JSON.stringify(response);
			}).
			catch(() =>
			{
				this.form.messages = { '-100' : 'An unexpected error occurred. Please try again.' };
				this.$emit('form_error',{form: this});
			});
		},

		AssignFieldErrors(errors)
		{
			var fields     = this.form.fields;
			var fieldNames = Object.keys(fields);
			for (var i = 0; i < fieldNames.length; ++i)
			{
				var fieldName = fieldNames[i];
				var fieldObj  = fields[fieldName];
				if (errors.hasOwnProperty(fieldName)) {
					fieldObj.errors = [errors[fieldName]];
				}
				else {
					fieldObj.errors = [];
				}
			}
		}
	}
});
