ss.vue.components['vilarity']['vilarity-program-node-panel'] = Vue.extend(
{
	name   : 'vilarity-program-node-panel',
//	mixins : [ss.vue.mixins['ssobject:vilarity\\ProgramNode']],
	mixins :
	[
		ss.vue.mixins['ss\\Object'],
		ss.vue.mixins['vilarity']['vilarity-program-node-resource']
	],
	template   : '<div></div>',
	components : ss.vue.components['vilarity'],

	props : {
	//	'editProgramNode' : Object
	},

	data : function()
	{
		return {
			ui : {
				edit: {},

				instructionsEditorConfig: {
					removePlugins : 'elementspath',
					extraPlugins : 'iframe',
					resize_enabled : false,
					toolbar : [
						{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
						{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
						{ name: 'links', items: [ 'Link', 'Unlink' ] },
						{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Iframe' ] }
					],

					// Toolbar groups configuration.
					toolbarGroups : [
						{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
						{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
						{ name: 'editing', groups: [ 'find', 'selection' ] },
						'/',
						{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
						{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
						{ name: 'links' }
					]
				}
			},

			inputSaveDebounceDelay     : (2 * 1000), // delay in milliseconds before saving changes typed into inputs
			inputSaveDebounceTimeoutId : null,
			inputSaveDebounceLastInput : {}
		}
	},

	computed :
	{
		settings : function() {
			return this.$store.state.vilarity.settings;
		},

	//	account : function() {
	//		return this.$store.state.vilarity.account;
	//	},

		programNode : function()
		{
			return this.object;
		},

		programRecords : function()
		{
			return this.$parent.programRecords;
		},

		editProgramNode : function()
		{
			return this.object;
		},

		groups : function() {
			return this.$store.state.vilarity.groups;
		}
	},

	watch :
	{
		'object.type' : function(to,from)
		{
			this.InitTemplate();
		}
	},

	mounted : function()
	{
		this.InitTemplate();
	},

	methods:
	{
		InitTemplate : function()
		{
			if (this.object)
			  switch (this.object.type)
			{
			case 'series'  :
			case 'session' :
			case 'part'    :
			case 'point'   :
				this.RenderTemplate('vilarity/vue/components/program_node/'+this.object.type+'-panel');
				break;
			}
		},

		GetProgramTerm : function(programObj)
		{
		// Level 0:
			return this.settings.contentLevels['program'].label;
		},
		GetProgramNodeTerm : function(programNodeType)
		{
			if (programNodeType instanceof vilarity.ProgramNode) {
				programNodeType = programNodeType.type;
			}
			switch (programNodeType)
			{
			// Level 1:
			case 'series'  : return this.settings.contentLevels['series'].label;
			// Level 2:
			case 'session' : return this.settings.contentLevels['session'].label;
			// Level 3:
			case 'part'    : return this.settings.contentLevels['part'].label;
			// Level 4:
			case 'point'   : return this.settings.contentLevels['point'].label;

			default : return this.settings.contentLevels['default'].label;
			}
		},

		OnSaveProgramNode : function(programNodeObj,propertyName,debounceDelay)
		{
			if (!programNodeObj)
			{
				console.log('ERROR: No program node to save.');
				return false;
			}

		// Argument defaults:
			if (propertyName === undefined) {
				propertyName   = 'value';
			}
			if (debounceDelay === undefined) {
				debounceDelay   = this.inputSaveDebounceDelay;
			}
			if (debounceDelay < 0) {
				debounceDelay = 0;
			}

		// Block save when nothing has changed.
		// Note: This can occur when the "input" event fires before the user
		//       navigates away from an input and the "change" event also fires,
		//       but nothing has actually changed since the last save.
			if (programNodeObj[propertyName] == this.inputSaveDebounceLastInput[propertyName])
			{
				console.log('Changes already saved for node %s.',programNodeObj.UUID);
				return;
			}

		// Save the program record after the timeout.
			var vm = this;
			if (vm.inputSaveDebounceTimeoutId != null) {
				clearTimeout(vm.inputSaveDebounceTimeoutId);
			}
			vm.inputSaveDebounceTimeoutId = setTimeout(function()
			{
				var programNodeObj_copy = new vilarity.ProgramNode();
				programNodeObj_copy.SetProperties(programNodeObj);

				vm.inputSaveDebounceTimeoutId = null;
			//	programNodeObj.Save().then(() =>
				programNodeObj._.objStatus = 'saving';
				programNodeObj_copy.Save().then(() =>
				{
					programNodeObj._.objStatus = '';
					console.log('Saved program node %s.',programNodeObj.UUID);

				// If the node is new, then copy the IDs.
					if (programNodeObj.objId == 0 && programNodeObj_copy.objId != 0 &&
						programNodeObj.objId !=      programNodeObj_copy.objId)
					{	programNodeObj.objId  =      programNodeObj_copy.objId;	}
					if (programNodeObj.UUID  == 0 && programNodeObj_copy.UUID != 0 &&
						programNodeObj.UUID  !=      programNodeObj_copy.UUID)
					{	programNodeObj.UUID   =      programNodeObj_copy.UUID;	}

				// Debounce repeat saves for the same change.
					vm.inputSaveDebounceLastInput[propertyName] = programNodeObj[propertyName];
				}).catch((event) =>
				{
					programNodeObj._.objStatus = 'error';
					console.log('Error saving record %s. Retrying...',programNodeObj.UUID);

				// Retry the save after the standard debounce period.
					vm.OnSaveProgramNode(programNodeObj,propertyName,vm.inputSaveDebounceDelay);
				});
			},debounceDelay);
		}
	}
});
