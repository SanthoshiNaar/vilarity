ss.vue.components['vilarity']['view-default'] = Vue.extend(
{
	template   : ss.vue.templates['vilarity/templates/view/default'],
	components : ss.vue.components['vilarity'],

	data : function()
	{
		return {
			ui : {
				edit: {}
			}
		}
	},
	computed :
	{
		programs : function() {
			return this.$store.state.vilarity.programs;
		}
	},

	methods :
	{
		OnNewProgram : function()
		{
			var programObj = new vilarity.Program();
			//	programObj.UUID      = uuidv4();
				programObj.key       = 'program-'+uuidv4().slice(0,8);
				programObj.order     = this.programs.length+1;
				programObj.number    = this.programs.length+1;
				programObj.cardColor = 'white';

			this.programs.push(programObj);

			Vue.set(this.ui.edit,programObj.UUID,programObj);
		},

		OnStartReorderProgramsFromDOM : function(eventObj)
		{
		// Fixup the drag DOM, which won't have the "value" attributes Vue suppresses.
			var parentNode   = eventObj.target;
			var originalNode = eventObj.detail[1];
			var dragNode     = eventObj.detail[0].drag;
				dragNode.style.width     = originalNode.clientWidth+'px';
				dragNode.style.height    = originalNode.clientHeight+'px';
				dragNode.style.minWidth  = 0;
				dragNode.style.maxHeight = '90vh';
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

		OnReorderProgramsFromDOM : function(eventObj)
		{
		// Reorder all the programs according to the DOM order.
			var parentNode  = eventObj.target;
			var allNodes    = parentNode.children;
			var programs    = this.programs;
			var newPrograms = [];
			for (var i = 0; i < allNodes.length; ++i)
			{
				var domNode     = allNodes[i];
				var programUUID = domNode.getAttribute('data-program-uuid');
				var idx = programs.findIndex((program) => program.UUID == programUUID);
				if (idx >= 0)
				{	newPrograms.push(programs[idx]);	}
			}

		// Update the programs and relink/reorder them.
			this.$store.commit('vilarity/programs',newPrograms);
			this.ReorderPrograms();

		// Stop the reordering from being reprocessed higher in the DOM.
			eventObj.stopPropagation();
			eventObj.preventDefault();
		},

		ReorderPrograms()
		{
			var siblings = this.programs;

			// Renumber.
			var promises = [];
			for (var i = 0; i < siblings.length; ++i)
			{
				var program  = siblings[i];
				var bChanged = false;
			// Order:
				if (program.order != i)
				{	program.order  = i;
					bChanged       = true;
				}
			// Number:
				if (program.number != (i+1))
				{	program.number  = (i+1);
					bChanged        = true;
				}
			// Previous:
				if (i === 0 && (program.previous instanceof vilarity.Program && program.previous.objId != 0))
				{	program.previous = null;
					bChanged = true;
				}
				if (i !== 0 && (program.previous instanceof vilarity.Program === false || program.previous.objId != siblings[i-1].objId))
				{	program.previous = siblings[i-1];
					bChanged = true;
				}
			// Next:
				if (i === (siblings.length-1) && (program.next instanceof vilarity.Program && program.next.objId != 0))
				{	program.next = null;
					bChanged  = true;
				}
				if (i !== (siblings.length-1) && (program.next instanceof vilarity.Program === false || program.next.objId != siblings[i+1].objId))
				{	program.next = siblings[i+1];
					bChanged = true;
				}
			// Save?
				if (bChanged)
				{
					var prev   = program.previous;
					var next   = program.next;
				//	var parent = program.parent;
					promises.push(program.Save().then((obj) =>
					{
					// Restore our object pointers.
					// BUG: This is necessary because Object::Save() might replace property objects
					//      with new objects when the server returns new data instead of just updating
					//      the existing object properties.
					// TODO: This might be a save promise race condition, too.
					// TODO: Remove once the Object::Save() bug is fixed.
						obj.previous = prev;
						obj.next     = next;
					//	obj.parent   = parent;

						return program;
					}));
				}
			}

		// Wait for all promises to resolve.
			return new Promise((resolve,reject) =>
			{
				Promise.all(promises).then((values) =>
				{
					resolve({values:values});
				});
			});
		}
	}
});
