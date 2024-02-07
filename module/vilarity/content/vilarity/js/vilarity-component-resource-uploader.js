ss.vue.components['vilarity']['vilarity-resource-uploader'] = Vue.extend(
{
	name       : 'vilarity-resource-uploader',
	mixins     : [ss.vue.mixins['ss\\Object']],
	template   : ss.vue.templates['vilarity/vue/components/resource-uploader'],
	components : ss.vue.components['vilarity'],

	props : {
		'type' : String,
		'resource' : Object
	//	'program' : Object
	},

	data : function()
	{
		return {
			settings :
			{
				endpointType : this.$props.type,

				uploadEndpoints :
				{
					'file'  : 'api/v1/resource/file',
					'audio' : 'api/v1/resource/file',
					'video' : 'api/v1/resource/file',
					'image' : 'api/v1/resource/photo'
				},

				bAllowDelete : true
			}
		}
	},

	computed :
	{
	},

	watch :
	{
		'type' : function(value)
		{
			this.InitUploader();
		}
	},

	created : function()
	{
	},

	mounted : function()
	{
		this.InitUploader();
	},

	methods:
	{
		InitUploader : function()
		{
			var drop = this.$el.querySelector('.js-upload');
			var bar  = this.$el.querySelector('.js-progressbar');
			var vm   = this;

			UIkit.upload(drop,
			{
				url: this.settings.uploadEndpoints[this.type],
				multiple: true,

				beforeSend: function (environment) {
					console.log('beforeSend', arguments);

					// The environment object can still be modified here.
					// var {data, method, headers, xhr, responseType} = environment;

				},
				beforeAll: function () {
					console.log('beforeAll', arguments);
				},
				load: function () {
					console.log('load', arguments);
				},
				error: function () {
					console.log('error', arguments);
				},

				complete: function (xhr)
				{
					console.log('complete', arguments);

					var reply = JSON.parse(xhr.response);

					vm.$emit('complete',reply.response.data,reply);
				},

				loadStart: function (e) {
					console.log('loadStart', arguments);

					bar.removeAttribute('hidden');
					bar.max = e.total;
					bar.value = e.loaded;
				},

				progress: function (e) {
					console.log('progress', arguments);

					bar.max = e.total;
					bar.value = e.loaded;
				},

				loadEnd: function (e) {
					console.log('loadEnd', arguments);

					bar.max = e.total;
					bar.value = e.loaded;
				},

				completeAll: function () {
					console.log('completeAll', arguments);

					setTimeout(function () {
						bar.setAttribute('hidden', 'hidden');
					}, 1000);
				}

			});
		},

		OnReplace : function()
		{
			if (this.settings.uploadEndpoints[this.type] === undefined)
			{
				console.log('Cannot manage an unknown resource type: %s',this.type);
				return;
			}
			if (this.resource === undefined || this.resource === null)
			{
				console.log('Cannot delete an undefined resource.');
				return;
			}
			var entryId = null;
			if (this.type === 'image')
			{
			// Get the ID of the resource, which should be a photo with a defined gallery.
				if (this.resource.hasOwnProperty('gallery') === false)
				{
					console.log('Resource definition is not valid for type %s.',this.type);
					return;
				}
				entryId = this.resource.objId;
			}
			else
			{
			// Get the ID of the resource, which should be an LVM entry with a defined volume.
				if (this.resource.hasOwnProperty('volume') === false)
				{
					console.log('Resource definition is not valid for type %s.',this.type);
					return;
				}
				entryId = this.resource.objId;
			}
			if (entryId === 0 || entryId === '0' || entryId === null || entryId === '' || entryId === undefined || entryId === false)
			{
				console.log('ERROR: No resource available to delete.');
				return;
			}
			axios.delete(this.settings.uploadEndpoints[this.type],{ data:
			{	'id' : entryId
			}}).then(() =>
			{
				this.$emit('deleted',this.resource);
			});
		}
	}
});
