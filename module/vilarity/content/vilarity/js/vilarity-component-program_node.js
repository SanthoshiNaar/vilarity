ss.vue.components['vilarity']['vilarity-program-node'] = Vue.extend(
{
	name   : 'vilarity-program-node',
//	mixins : [ss.vue.mixins['ssobject:vilarity\\ProgramNode']],
	mixins :
	[
		ss.vue.mixins['ss\\Object'],
		ss.vue.mixins['vilarity']['vilarity-program-node-resource']
	],
	template   : '<div></div>',
	components : ss.vue.components['vilarity'],

	props : {
	//	'programNode' : Object
	},

	data : function()
	{
		return {
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

		programNode : function()
		{
			return this.object;
		},

		programRecords : function()
		{
			return this.$parent.programRecords;
		},

		focusProgramRecord : function()
		{
			return this.$parent.focusProgramRecord;
		},

		programRecord : function()
		{
		// Return an existing node.
			if ('node_'+this.object.UUID in this.programRecords) {
				return this.programRecords['node_'+this.object.UUID];
			}

		// Implicitly add a record for this program node.
			var nodeObj = new vilarity.ProgramRecord();
				nodeObj.UUID        = uuidv4();
				nodeObj.account     = this.account;
				nodeObj.program     = this.object.program;
				nodeObj.programNode = this.object;

			this.programRecords['node_'+this.object.UUID] = nodeObj;
			return nodeObj;
		},

		programNode_embedURL : function()
		{
			if (this.programNode.subType != 'audio' &&
				this.programNode.subType != 'video' &&
				this.programNode.subType != 'file')
			{
				return null;
			}

			if (!this.programNode.description || this.programNode.description.match(/^{/) == null) {
				return null;
			}
			var obj = JSON.parse(this.programNode.description);
			if (!obj)
				return null;
			if (obj.hasOwnProperty('embedURL') === false)
				return null;
			return obj.embedURL;
		},

		programNode_photoObj : function()
		{
			if (this.programNode.subType != 'image') {
				return null;
			}
			var obj = this.programNode.GetResource();
			if (!obj)
				return null;
			if (obj.hasOwnProperty('gallery') === false || obj.hasOwnProperty('lvmEntry') === false)
				return null;
			return obj;
		},

		programNode_volumeEntryObj : function()
		{
			if (this.programNode.subType != 'audio' &&
				this.programNode.subType != 'video' &&
				this.programNode.subType != 'file')
			{
				return null;
			}
			var obj = this.programNode.GetResource();
			if (!obj)
				return null;
			if (obj.hasOwnProperty('volume') === false || obj.hasOwnProperty('lvmPath') === false)
				return null;
			return obj;
		}
	},

	watch :
	{
		'object' : function(to,from)
		{
			this.InitTemplate();
		},

		'programRecord.objId' : function(to,from)
		{
			if (from == 0 &&
				to   != 0)
			{
			//	this.programRecords['node_'+this.programRecord.UUID] = this.programRecord;
				this.programRecords['node_'+this.programRecord.UUID] = this.programRecord;
			}
		}
	},

	mounted : function()
	{
		this.InitTemplate();
	},

	destroyed : function()
	{
		if (this.bListeningForResize)
		{
			window.removeEventListener('resize',this.OnViewportResize);
			this.bListeningForResize = undefined;
		}
	},

	updated: function()
	{
	// Auto-size iframes.
		if (this.object &&
			this.object.type === 'point')
		{
			this.DoIframeHeightResize();
			this.DoTextareaHeightResize();
		}
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
				this.RenderTemplate('vilarity/vue/components/program_node/'+this.object.type);
				break;
			}

		// Auto-size iframes.
			if (this.bListeningForResize)
			{
				window.removeEventListener('resize',this.OnViewportResize);
				this.bListeningForResize = undefined;
			}
			if (this.object &&
				this.object.type === 'point')
			{
				window.addEventListener('resize',this.OnViewportResize);
				this.bListeningForResize = true;
			}
		},

		OnViewportResize : function()
		{
			this.DoIframeHeightResize();
			this.DoTextareaHeightResize();
		},

		DoIframeHeightResize : function()
		{
			var iframes = this.$el.querySelectorAll('iframe');
			for (var i = 0; i < iframes.length; ++i)
			{
				var iframe = iframes[i];
				iframe.style.minHeight = '';

				var iframeHeight      = iframe.clientHeight;
				var iframeExtraHeight = iframe.offsetHeight - iframe.clientHeight;
				var iframeScroll      = iframe.scrollHeight;
				if (iframeHeight < iframeScroll)
				{
				// If a scrollbar is possible, then resize so it is eliminated.
					iframe.style.minHeight = iframeScroll + iframeExtraHeight + 'px';
				}

			// Ausha does not allow any scrolling in the iframe, so determine a fixed scroll
			// height instead based on the known player height.
				if (iframe.src.match(/player\.ausha\.co/))
				{
					if (iframe.clientWidth >= 500)
					{	iframe.style.minHeight = '220px';	}
					else
					{	iframe.style.minHeight = '500px';	}
				}
			}
		},

		DoTextareaHeightResize : function()
		{
			var textareas = this.$el.querySelectorAll('textarea');
			for (var i = 0; i < textareas.length; ++i)
			{
				var textarea = textareas[i];
				textarea.style.minHeight = '';

				var textareaHeight      = textarea.clientHeight;
				var textareaExtraHeight = textarea.offsetHeight - textarea.clientHeight;
				var textareaScroll      = textarea.scrollHeight;
				if (textareaHeight < textareaScroll)
				{
				// If a scrollbar is possible, then resize so it is eliminated.
					textarea.style.minHeight = (textareaScroll + textareaExtraHeight + 'px');
				}
			}
		},

		GetLayoutWidthClass : function()
		{
			switch (this.object.layoutWidth)
			{
			case '1-1' : return 'uk-width-1-1';

			case '1-2' : return 'uk-width-1-2';

			case '1-3' : return 'uk-width-1-3';
			case '2-3' : return 'uk-width-2-3';

			case '1-4' : return 'uk-width-1-4';
			case '3-4' : return 'uk-width-3-4';

			case '1-5' : return 'uk-width-1-5';
			case '2-5' : return 'uk-width-2-5';
			case '3-5' : return 'uk-width-3-5';
			case '4-5' : return 'uk-width-4-5';

			case '1-6' : return 'uk-width-1-6';
			case '5-6' : return 'uk-width-5-6';
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

		GetProgramNodeRecord : function(programNodeObj)
		{
			return this.$parent.GetProgramNodeRecord(programNodeObj);
		},

		GetTextAreaRowValue : function(textValue,maxRows)
		{
			if (maxRows === undefined) {
				maxRows   = 5;
			}
			var lineCount = (textValue.match(/(?:\r?\n|\r)/g) || '').length + 1;
			if (lineCount > maxRows) {
				return maxRows;
			}
			else {
				return lineCount;
			}
		},

		OnNew : function(parentProgramNode,nodeType,insertBeforeNode)
		{
			var order = 1;
			if (insertBeforeNode)
			{
				order = insertBeforeNode.order;

			// Cascade to adjacent nodes.
				var adjNode = insertBeforeNode.next;
				adjOrder = order;
				while (adjNode && adjNode.objId != 0)
				{
					adjOrder++;
					adjNode.order  = adjOrder;
					adjNode.number = adjOrder + 1;
					adjNode = adjNode.next;
				}
			}

			var newNode = new vilarity.ProgramNode();
				newNode.UUID     = uuidv4();
				newNode.key      = 'node-'+newNode.UUID.slice(0,8);
				newNode.type     = nodeType;
				newNode.order    = order;
				newNode.number   = order + 1;
				newNode.program  = parentProgramNode.program;
				newNode.parent   = parentProgramNode;
				newNode.previous = new vilarity.ProgramNode();
				newNode.next     = new vilarity.ProgramNode();
				newNode.title    = 'Untitled';

			if (nodeType === 'point')
			{
				newNode.subType = 'input';

				newNode.layoutWidth = '1-1';
				if (insertBeforeNode) {
					newNode.layoutWidth = insertBeforeNode.layoutWidth;
				}

				newNode.description = 'New Point';
			}

			var siblings = parentProgramNode.childNodes;
			if (insertBeforeNode instanceof vilarity.ProgramNode)
			{
				var idx = siblings.indexOf(insertBeforeNode);
				siblings.splice(idx,0,newNode);
			}
			else {
				siblings.push(newNode);
			}

			var program = newNode.program;
			var prev    = newNode.previous;
			var next    = newNode.next;
			var parent  = newNode.parent;

			var origUUID = newNode.UUID;
			if (newNode.GetID() === 0) {
				newNode.UUID = ''; // Clear so the SS object API doesn't use it
			}
			return newNode.Save().then((obj) =>
			{
			// Restore our object pointers.
			// BUG: This is necessary because Object::Save() might replace property objects
			//      with new objects when the server returns new data instead of just updating
			//      the existing object properties.
			// TODO: This might be a save promise race condition, too.
			// TODO: Remove once the Object::Save() bug is fixed.
				obj.program  = program;
				obj.previous = prev;
				obj.next     = next;
				obj.parent   = parent;
				return parentProgramNode.ReorderChildNodes().then(() =>
				{
					return this;
				});
			});
		},

		OnStartReorderProgramNodesFromDOM : function(programNodeObj,eventObj)
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

		OnReorderProgramNodesFromDOM : function(programNodeObj,eventObj)
		{
		// Reorder all the program nodes according to the DOM order.
			var parentNode      = eventObj.target;
			var allNodes        = parentNode.children;
			var programNodes    = programNodeObj.childNodes;
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
			programNodeObj.childNodes = newProgramNodes;
			programNodeObj.ReorderChildNodes();

		// Stop the reordering from being reprocessed higher in the DOM.
			eventObj.stopPropagation();
			eventObj.preventDefault();
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
