ss.vue.components['vilarity']['view-program'] = Vue.extend(
{
	name       : 'vilarity-view-program',
//	mixins     : ss.vue.mixins['vilarity']['object-list'],
	mixins     : [ss.vue.mixins['ss\\Object']],
	template   : ss.vue.templates['vilarity/templates/view/program'],
	components : ss.vue.components['vilarity'],

	props : {
	},

	data : function()
	{
		return {
			program     : new vilarity.Program(),
			programNode : new vilarity.ProgramNode(),

			editProgramNode : null,

		//	nodes : {},

			programRecords : {
			},

			ui : {
				edit: {}
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

		account : function()
		{
		// Use the effective account if there is one set.
			if (this.$store.state.vilarity.effectiveAccount &&
				this.$store.state.vilarity.effectiveAccount.objId)
			{	return this.$store.state.vilarity.effectiveAccount;	}
		// Otherwise use the current account.
			return this.$store.state.vilarity.account;
		},

		programs : function() {
			return this.$store.state.vilarity.programs;
		},

		focusProgramRecord : function()
		{
			return this.GetProgramNodeRecord(this.editProgramNode);
		},

		groups : function() {
			return this.$store.state.vilarity.groups;
		},

		nodes : function()
		{
		// Load the child nodes.
			if (this.programNode)
			{
				if (this.programNode.childNodes) {
					return this.programNode.childNodes;
				}
			}
			else if (this.program)
			{
				if (this.program.childNodes) {
					return this.program.childNodes;
				}
			}
			return
		}
	},

	watch :
	{
		'$route.meta.program' : function(value)
		{
		// Update the program when the route changes.
			this.program     = this.$route.meta.program;
			this.programNode = this.$route.meta.programNode;
		},

		'$route.meta.programNode' : function(value)
		{
		// Update the program node when the route changes.
			this.program     = this.$route.meta.program;
			this.programNode = this.$route.meta.programNode;
		},

		'account' : function()
		{
			this.LoadAccountProgramRecords();
		},

		'program' : function(value)
		{
			if (this.programNode == null) {
				this.InitNodes();
			}
			this.LoadAccountProgramRecords();
		},

		'programNode' : function(value)
		{
			this.InitNodes();

		// Default to editing the program node.
			this.editProgramNode = this.programNode;

		// The program route leads program nodes to the program component.
		// Use a different view for program nodes so the layout mark can
		// be more concise.
			if (value == null) {
				this.RenderTemplate('vilarity/templates/view/program');
			}
			else {
				this.RenderTemplate('vilarity/templates/view/program_node');
			}
		},

		nodes : function(value)
		{
			if (!this.nodes) {
				return;
			}

		/*	var len = 0;
			while ((len = Object.keys(this.nodes).length) < 10)
			{
				var programNodeObj = new vilarity.ProgramNode();
					programNodeObj.program = this.programObj;
					programNodeObj.title   = '+';
				//	programNodeObj.description = 'Describe series '+(len+1);
				this.nodes[len] = programNodeObj;
				break;
			} */
		}
	},

	mounted : function()
	{
	// Set the data from the route meta data.
		this.program     = this.$route.meta.program;
		this.programNode = this.$route.meta.programNode;

		console.log(this.program);
		console.log(this.programNode);
	},

	methods:
	{
		GetProgramTerm(programObj)
		{
		// Level 0:
			return this.settings.contentLevels['program'].label;
		},
		GetProgramNodeTerm(programNodeObj)
		{
			if (!programNodeObj) {
				return this.settings.contentLevels['default'].label;
			}
			switch (programNodeObj.type)
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

		GetProgramNodeRecord(programNodeObj)
		{
		// Return an existing node.
			if ('node_'+programNodeObj.UUID in this.programRecords) {
				return this.programRecords['node_'+programNodeObj.UUID];
			}

		// New programNode IDs instead of UUIDs sneak into the program records.
		// Detect these. This is a work-around so new ProgramRecords for new ProgramNodes
		// are not repeatedly created.
		// TODO: Why does this happen??
		// Note: It appears to be something unexpected from the ObjectBase.Find API,
		//       providing an unloaded object by ID instead of a loaded object with a UUID/
		//       Then the LoadAccountProgramRecords() loop receives an objId instead of UUID.
		// N.B.: This is possibly fixed now with proper object mapping and UUID lookups in
		//       LoadAccountProgramRecords(). Leaving the check in place for now for further testing.
			if ('node_'+programNodeObj.objId in this.programRecords)
			{
			// Improve the mapping.
				this.programRecords['node_'+programNodeObj.UUID] = this.programRecords['node_'+programNodeObj.objId];
			// Remap.
			// Note: It will return everytime LoadAccountProgramRecords() is called.
				delete this.programRecords['node_'+programNodeObj.objId];
			// Return it.
				return this.programRecords['node_'+programNodeObj.UUID];
			}

		// Implicitly add a record for this program node.
			var recordObj = new vilarity.ProgramRecord();
				recordObj.UUID        = uuidv4();
				recordObj.account     = this.account;
				recordObj.program     = programNodeObj.program;
				recordObj.programNode = programNodeObj;
				recordObj.notes       = '';

			this.programRecords['node_'+programNodeObj.UUID] = recordObj;
			return recordObj;
		},

		InitNodes()
		{
			return;

			this.nodes = []; // clear

		// Load the child nodes.
			if (this.programNode)
			{
				if (this.programNode.childNodes && this.programNode.childNodes.length) {
					this.nodes = [...this.programNode.childNodes];
				}
				else
				{
					this.programNode.LoadChildNodes().then((objs) =>
					{
						if (objs.length) {
							this.nodes = objs;
						}
					});
				}
			}
			else if (this.program)
			{
				if (this.program.childNodes && this.program.childNodes.length) {
					this.nodes = [...this.program.childNodes];
				}
				else
				{
					this.program.LoadChildNodes().then((objs) =>
					{
						if (objs.length) {
							this.nodes = objs;
						}
					});
				}
			}
		},

		LoadAccountProgramRecords()
		{
			return this.account.LoadProgramRecords(this.program/* Note: not per node... ,this.programNode*/).then((programRecords) =>
			{
			// Process the records.
				var recordsByProgramNodeUUID = {};
				for (var i = 0; i < programRecords.length; ++i)
				{
				// Work with object IDs instead of objects.
					var obj = programRecords[i];
					obj._.objPropGetMode = 'id';

				// Validate the server response.
				// Discard invalid data if it is found.
					if (obj.account != this.account.objId && obj.account != this.account.UUID)
					{
						obj._.objPropGetMode = 'obj';
						continue;
					}
					if (obj.program != this.program.objId && obj.program != this.program.UUID)
					{
						obj._.objPropGetMode = 'obj';
						continue;
					}

				// Map the subobjects to known objects.
					obj.account     = this.account;
					obj.program     = this.program;
					obj.programNode = this.program.nodesById[obj.programNode];

				// Restore object access on subproperties.
					obj._.objPropGetMode = 'obj';

				// Index the ProgramRecords by the ProgramNode they belong to.
					if (obj.programNode.objId == 0)
					{
					// This is an orphaned ProgramRecord. It cannot be mapped.
						continue;
					}
					var programNodeUUID = obj.programNode.UUID;
					recordsByProgramNodeUUID['node_'+programNodeUUID] = obj;
				}
				this.programRecords = recordsByProgramNodeUUID;
			});
		},

		OnNew : function()
		{
			var programNodeObj = new vilarity.ProgramNode();
				programNodeObj.UUID    = uuidv4();
				programNodeObj.key     = 'node-'+programNodeObj.UUID.slice(0,8);
				programNodeObj.type    = 'series';
				programNodeObj.order   = this.nodes.length;
				programNodeObj.number  = this.nodes.length+1;
				programNodeObj.program = this.program;
				programNodeObj.parent  = this.programNode;
				programNodeObj.title   = 'Untitled';

			this.nodes.push(programNodeObj);

		// Work with an editing copy.
			var editObj = new vilarity.ProgramNode();
				editObj.SetProperties(programNodeObj);
			Vue.set(this.ui.edit,programNodeObj.UUID,editObj);
		},

		OnEdit : function(programNodeObj)
		{
		// Work with an editing copy.
			var editObj = new vilarity.ProgramNode();
				editObj.SetProperties(programNodeObj);
			Vue.set(this.ui.edit,programNodeObj.UUID,editObj);
		},

		OnSave : function(programNodeObj)
		{
		// Work with the editing copy.
			var editObj = this.ui.edit[programNodeObj.UUID];
			if (editObj.title == '')
			{
				console.log('Cannot save without a title.');
				alert('Cannot save without a title.');
				return;
			}
			if (editObj.key == '')
			{
				console.log('Cannot save without a URL ley.');
				alert('Cannot save without a URL link.');
				return;
			}

			var origUUID = editObj.UUID;
			if (editObj.GetID() === 0) {
				editObj.UUID = ''; // Clear so the SS object API doesn't use it
			}
			this.$root.OnSaveProgramNode(editObj).then((result) =>
			{
				programNodeObj.SetProperties(editObj);
				console.log(result);
				Vue.delete(this.ui.edit,origUUID);

			// Sync the nodes into the parent tree.
				this.SyncNodeListToTree();
			});
		},

		OnDeleteProgram : function(program)
		{
			UIkit.modal.confirm(
				'<p>Are you sure you want to delete this '+
				this.GetProgramTerm(program) +
				'?</p><p><em>Warning: This cannot be undone!</em></p>').then(()=>
			{
				program.Delete().then(() =>
				{
					var programs = this.programs.slice(); // (copy)
					var idx = programs.indexOf(program);
					programs.splice(idx,1);
					this.$store.commit('vilarity/programs',programs);

					this.$router.push('/');
				});
			});
		},

		OnDeleteProgramNode : function(programNode)
		{
			UIkit.modal.confirm(
				'<p>Are you sure you want to delete this '+
				this.GetProgramNodeTerm(programNode) +
				'?</p><p><em>Warning: This cannot be undone!</em></p>').then(()=>
			{
				var parentNode = programNode.parent;
				programNode.Delete().then(() =>
				{
				// Manage the editing list, if this node was in it.
					var idx = this.nodes.indexOf(programNode);
					if (idx >= 0)
					{
						this.nodes.splice(idx,1);

					// Sync the nodes into the parent tree.
						this.SyncNodeListToTree();
					}

				// Change the editing node if it was just deleted.
					if (this.editProgramNode.objId == programNode.objId) {
						this.editProgramNode = parentNode;
					}

				// Redirect after deleting?
					switch (programNode.type)
					{
					case 'program' :
					case 'series'  :
					case 'session' :
					// Redirect to a parent level if a page level was just deleted.
						if (this.editProgramNode) {
							this.$router.push('/'+this.program.key+(this.editProgramNode.key != '' ? '/'+this.editProgramNode.key : ''));
						}
						else {
							this.$router.push('/');
						}
						break;
					}
				});
			});
		},

		OnStartReorderProgramNodesFromDOM : function(programObj,eventObj)
		{
		// Fixup the drag DOM, which won't have the "value" attributes Vue suppresses.
			var parentNode   = eventObj.target;
			var originalNode = eventObj.detail[1];
			var dragNode     = eventObj.detail[0].drag;
				dragNode.style.width  = originalNode.clientWidth+'px';
				dragNode.style.height = originalNode.clientHeight+'px';
				dragNode.style.setProperty('z-index','5','important');
			var processNodes = [dragNode,...dragNode.querySelectorAll('input')];
			for (var i = 0; i < processNodes.length; ++i)
			{
				var domNode = processNodes[i];
				if (domNode.nodeName.toLowerCase() === 'input' &&
					domNode.hasAttribute('data-drag-value'))
				{
					domNode.setAttribute('value',domNode.getAttribute('data-drag-value'));
				}
			}

		// Stop the reordering from being reprocessed higher in the DOM.
			eventObj.stopPropagation();
			eventObj.preventDefault();
		},

		OnReorderProgramNodesFromDOM : function(programObj,eventObj)
		{
		// Reorder all the program nodes according to the DOM order.
			var parentNode      = eventObj.target;
			var allNodes        = parentNode.children;
			var programNodes    = programObj.childNodes;
			var newProgramNodes = [];
			for (var i = 0; i < allNodes.length; ++i)
			{
				var domNode         = allNodes[i];
				var programNodeUUID = domNode.getAttribute('data-programnode-uuid');
				var idx = programNodes.findIndex((programNode) => programNode.UUID == programNodeUUID);
				if (idx >= 0)
				{	newProgramNodes.push(programNodes[idx]);	}
			}

		// Update the child nodes and relink/reorder them.
			programObj.childNodes = newProgramNodes;
			programObj.ReorderChildNodes();

		// Stop the reordering from being reprocessed higher in the DOM.
			eventObj.stopPropagation();
			eventObj.preventDefault();
		},

		OnDiscardChanges : function(programNodeObj)
		{
		// Roll back.
		//	programNodeObj.SetProperties(this.ui.edit[programNodeObj.UUID]);

			Vue.delete(this.ui.edit,programNodeObj.UUID);

			if (programNodeObj.GetID() === 0)
			{
			// Discard empty nodes.
				var index = this.nodes.indexOf(programNodeObj);
				if (index > -1) {
					this.nodes.splice(index,1);
				}
			}
		},

		OnFocusProgramNode : function(programNode)
		{
			if (programNode == null) {
			//	this.editProgramNode = this.programNode;
			}
			else {
				this.editProgramNode = programNode;
			}
		},

		SyncNodeListToTree : function()
		{
			var treeNodes = [];
			for (var i = 0; i < this.nodes.length; ++i)
			{
				if (this.nodes[i].objId != 0) {
					treeNodes.push(this.nodes[i]);
				}
			}
			if (this.programNode) {
				this.programNode.childNodes = treeNodes;
			}
			else {
				this.program.childNodes = treeNodes;
			}
		},

		OnSaveRecord : function(programRecordObj,propertyName,debounceDelay)
		{
			if (!programRecordObj)
			{
				console.log('ERROR: No program record to save.');
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
			if (programRecordObj[propertyName] == this.inputSaveDebounceLastInput[propertyName])
			{
				console.log('Changes already saved for record %s.',programRecordObj.UUID);
				return;
			}

		// Save the program record after the timeout.
			var vm = this;
			if (vm.inputSaveDebounceTimeoutId != null) {
				clearTimeout(vm.inputSaveDebounceTimeoutId);
			}
			vm.inputSaveDebounceTimeoutId = setTimeout(function()
			{
				var programRecordObj_copy = new vilarity.ProgramRecord();
				programRecordObj_copy.SetProperties(programRecordObj);

				vm.inputSaveDebounceTimeoutId = null;
			//	programRecordObj.Save().then(() =>
				programRecordObj._.objStatus = 'saving';
				programRecordObj_copy.Save().then(() =>
				{
					programRecordObj._.objStatus = '';
					console.log('Saved record %s.',programRecordObj.UUID);

				// If the record is new, then copy the IDs.
					if (programRecordObj.objId == 0 && programRecordObj_copy.objId != 0 &&
						programRecordObj.objId !=      programRecordObj_copy.objId)
					{	programRecordObj.objId  =      programRecordObj_copy.objId;	}
					if (programRecordObj.UUID  == 0 && programRecordObj_copy.UUID != 0 &&
						programRecordObj.UUID  !=      programRecordObj_copy.UUID)
					{	programRecordObj.UUID   =      programRecordObj_copy.UUID;	}

				// Debounce repeat saves for the same change.
					vm.inputSaveDebounceLastInput[propertyName] = programRecordObj[propertyName];
				}).catch((event) =>
				{
					programRecordObj._.objStatus = 'error';
					console.log('Error saving record %s. Retrying...',programRecordObj.UUID);

				// Retry the save after the standard debounce period.
					vm.OnSaveRecord(programRecordObj,propertyName,vm.inputSaveDebounceDelay);
				});
			},debounceDelay);
		}
	}
});
