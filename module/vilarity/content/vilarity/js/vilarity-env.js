// Define the "vilarity" namespace.
if (typeof vilarity === 'undefined') {
	vilarity = {};
}

// Extend the "ss" namespace with "vilarity" entries.
if (typeof ss.vue.components['vilarity'] === 'undefined') {
	ss.vue.components['vilarity'] = {};
}
if (typeof ss.vue.mixins['vilarity'] === 'undefined') {
	ss.vue.mixins['vilarity'] = {};
}
if (typeof ss.vue.templates['vilarity'] === 'undefined') {
	ss.vue.templates['vilarity'] = {};
}

// Define the app environment.
var app_env =
{
	mode : null, // 'production', // 'dev'

	productionDomain : 'vilarity\\.com$'
};

// Autodetect the environment mode.
if (! app_env.mode)
{
	app_env.mode = 'production';
	if (window && window.location && window.location.hostname &&
		window.location.hostname.match(new RegExp(app_env.productionDomain)) === null)
	{
		app_env.mode = 'dev';
	}
}
app_env.mode = 'production';
