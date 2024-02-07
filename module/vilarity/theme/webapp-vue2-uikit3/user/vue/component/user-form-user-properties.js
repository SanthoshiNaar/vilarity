ss.vue.components['user']['ss-user-form-user-properties'] =
ss.vue.components['user'][   'user-form-user-properties'] = Vue.extend(
{
	name       : 'ss-user-form-user-properties',
//	mixins     : [ss.vue.mixins['ss\\Form']],
	template   : ss.vue.templates['user/vue/template/user-form-user-properties'],
	components : ss.vue.components['user'],

	props : {
		mode : {type: String, default: 'edit'},
		user : ss.user.User
	},

	data : function()
	{
		return {
			form :
			{	method : 'POST',
				action : this.SubmitForm,
				fields :
				{
					status     : {property: 'status'    , type: 'select', name: 'status'    , value: '1', label: 'Status'       , required: true , errors: {}},
					username   : {property: 'username'  , type: 'text'  , name: 'username'  , value: '', label: 'Username'     , required: true , errors: {}},
					email      : {property: 'email'     , type: 'text'  , name: 'email'     , value: '', label: 'Email Address', required: true , errors: {}},
					name       : {property: 'name'      , type: 'text'  , name: 'name'      , value: '', label: 'Full Name'    , required: true , errors: {}},
					company    : {property: 'company'   , type: 'text'  , name: 'company'   , value: '', label: 'Company'      , required: false, errors: {}},
					title      : {property: 'title'     , type: 'text'  , name: 'title'     , value: '', label: 'Title'        , required: false, errors: {}},
					department : {property: 'department', type: 'text'  , name: 'department', value: '', label: 'Department'   , required: false, errors: {}},
				},
				messages : {},
				errors   : {}
			}
		}
	},

	computed :
	{
		sessionUser : function() {
			return this.$store.state.user.sessionUser;
		},
	},

	watch :
	{
		$route : function()
		{
			this.ResetForm();
			this.ImportObjectIntoFormFields(this.user);
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
	},

	methods:
	{
		ResetForm()
		{
			this.form.messages = {};
			this.form.errors   = {};

			for (fieldName in this.form.fields)
			{
				var field = this.form.fields[fieldName];
				field.messages = {};
				field.errors   = {};
			}
		},

		ImportObjectIntoFormFields : function(obj)
		{
			if (obj === undefined || obj === null)
				return false;
			if (obj.objId == 0)
				return false;
			for (fieldName in this.form.fields)
			{
				var field = this.form.fields[fieldName];
				if (obj.hasOwnProperty(field.property))
					field.value = obj[field.property];
			}
		},

		ExportFormFieldsIntoObject : function(obj)
		{
			for (fieldName in this.form.fields)
			{
				var field = this.form.fields[fieldName];
				if (obj !== undefined && obj !== null)
					obj[field.property] = field.value;
			}
		},

		SubmitForm : function()
		{
			this.form.messages = {};

			this.ExportFormFieldsIntoObject(this.user);

			var bNewUser = this.user.objId == 0 ? true : false;

			var emitData =
			{
				component: this,
				form: this.form,
				user: this.user,
				formMode: bNewUser ? 'new' : 'edit'
			};

			this.user.Save().then(() =>
			{
				var msg = 'The user has been updated.';
				if (bNewUser) {
					msg = 'The user has been created.';
				}
				if (this.sessionUser &&
					this.sessionUser.objId == this.user.objId)
				{	msg = 'Your information has been updated.';	}
				this.form.messages = { 100 : msg };

				this.$emit('form_success',emitData);
			}).
			catch(() =>
			{
				this.$emit('form_error',emitData);
			});
		}
	}
});
