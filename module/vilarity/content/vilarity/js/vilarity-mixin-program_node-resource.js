ss.vue.mixins['vilarity']['vilarity-program-node-resource'] =
{
	computed :
	{
	},

	watch :
	{
	},

	methods:
	{
		OnResourceUploadComplete(data,response)
		{
			if (data.photo)
			{
				return this.OnResourceUploadComplete_image(data.photo,response);
			}
			if (data.lvmentry)
			{
				return this.OnResourceUploadComplete_lvmentry(data.lvmentry,response);
			}
		},

		OnResourceDeleted(data,response)
		{
		//	if (data.photo)
			{
				return this.OnResourceDeleted_image(data.photo,response);
			}
		},

		OnResourceUploadComplete_image(photoObj, response)
		{
			this.programNode.title       = (photoObj.lvmEntry.title != '' ? photoObj.lvmEntry.title : 'Untitled');
			this.programNode.description = JSON.stringify(photoObj);
			this.programNode.Save();
		},

		OnResourceDeleted_image(photoObj, response)
		{
		// TODO: Delete the photo.

		// Forget the photo.
			this.programNode.title       = 'Untitled';
			this.programNode.description = '';
			this.programNode.Save();
		},

		OnResourceUploadComplete_lvmentry(lvmEntryObj, response)
		{
			this.programNode.title       = (lvmEntryObj.title != '' ? lvmEntryObj.title : 'Untitled');
			this.programNode.description = JSON.stringify(lvmEntryObj);
			this.programNode.Save();
		},

		OnResourceDeleted_lvmentry(lvmEntryObj, response)
		{
		// TODO: Delete the volume entry.

		// Forget the photo.
			this.programNode.title       = 'Untitled';
			this.programNode.description = '';
			this.programNode.Save();
		}
	}
};
