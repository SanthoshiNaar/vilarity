// Define the "webapp" namespace.
if (typeof webapp === 'undefined') {
	webapp = {};
}

// Extend the "ss" namespace with "webapp" entries.
if (typeof ss.vue.components['webapp'] === 'undefined') {
	ss.vue.components['webapp'] = {};
}
if (typeof ss.vue.mixins['webapp'] === 'undefined') {
	ss.vue.mixins['webapp'] = {};
}
if (typeof ss.vue.templates['webapp'] === 'undefined') {
	ss.vue.templates['webapp'] = {};
}

// Define the app environment.
var app_env =
{
	mode : null, // 'production', // 'dev'

	productionDomain : '\\.com$'
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
