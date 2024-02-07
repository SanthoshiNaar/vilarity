ss.vue.components['vilarity']['vilarity-form-group-properties'] = Vue.extend(
{
	name       : 'vilarity-form-group-properties',
//	mixins     : [ss.vue.mixins['ss\\Form']],
	template   : ss.vue.templates['vilarity/vue/components/vilarity-form-group-properties'],
	components : ss.vue.components['vilarity'],

	props : {
		mode  : {type: String, default: 'edit'},
		group : vilarity.Group
	},

	data : function()
	{
		return {
			form :
			{	method : 'POST',
				action : this.SubmitForm,
				fields :
				{
					title : {property: 'title', type: 'text', name: 'title', value: '', label: 'Group Title', required: true, errors: {}}
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
		$route : function()
		{
			this.ResetForm();
			this.ImportObjectIntoFormFields(this.group);
		},

		group : function()
		{
			this.ImportObjectIntoFormFields(this.group);
		}
	},

	created : function()
	{
	},

	mounted : function()
	{
		this.ResetForm();
		this.ImportObjectIntoFormFields(this.group);
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

			this.ExportFormFieldsIntoObject(this.group);

			var bNewObj = this.group.objId == 0 ? true : false;

			var emitData =
			{
				component: this,
				form: this.form,
				group: this.group,
				formMode: bNewObj ? 'new' : 'edit'
			};

			this.group.Save().then(() =>
			{
				var msg = 'The group has been updated.';
				if (bNewObj) {
					msg = 'The group has been created.';
				}
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
