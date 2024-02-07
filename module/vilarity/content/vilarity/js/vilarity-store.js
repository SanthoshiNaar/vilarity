(function()
{
	const store =
	{
		namespaced : true,
		state: () => (
		{
			account           : new vilarity.Account(),
			effectiveAccount  : new vilarity.Account(),
			effectiveGroup    : new vilarity.Group(),

			programs : [],
			groups   : [],

			settings :
			{
				contentLevels :
				{
					'program' :
					{
						'level'        : 0,
						'defaultLabel' : 'Program',
						'label'        : '',
					},
					'series' :
					{
						'level'        : 1,
						'defaultLabel' : 'Series',
						'label'        : '',
					},
					'session' :
					{
						'level'        : 2,
						'defaultLabel' : 'Session',
						'label'        : '',
					},
					'part' :
					{
						'level'        : 3,
						'defaultLabel' : 'Part',
						'label'        : '',
					},
					'point' :
					{
						'level'        : 4,
						'defaultLabel' : 'Point',
						'label'        : '',
					}
				}
			}
		}),
		mutations:
		{
			'account' : (state,accountObj) =>
			{
				state.account = accountObj;
			},
			'effectiveAccount' : (state,accountObj) => {
				state.effectiveAccount = accountObj;
			},

			'effectiveGroup' : (state,groupObj) => {
				state.effectiveGroup = groupObj;
			},

			'programs' : (state,programs) => {
				state.programs = programs;
			},

			'groups' : (state,groups) => {
				state.groups = groups;
			},

			'settings' : (state,settings) => {
				state.settings = settings;
			},

			'settings/contentLevels' : (state,contentLevels) => {
				state.settings.contentLevels = contentLevels;
			}
		},
		actions: {},
		getters: {}
	};

	vilarity.storeSpec = store;
})();
