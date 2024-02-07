// Vue Router:
var appNode    = document.getElementById('app');
var baseTags   = document.getElementsByTagName('base');
var appPath    = baseTags.length ? baseTags[0].href.substr(location.origin.length) : '';
var relPath    = appNode ? appNode.getAttribute('data-router-base') : '';
var routerBase = appPath;

var routerTitleSeparator = ' - ';

if (relPath)
	routerBase = routerBase.replace(/\/+$/,'')+'/'+relPath;

var router = new VueRouter(
{
	mode: 'history',
	base: routerBase,

	linkActiveClass: 'router-link-active uk-active',
	linkExactActiveClass: 'router-link-exact-active',

	routes:
	[
	]
});
