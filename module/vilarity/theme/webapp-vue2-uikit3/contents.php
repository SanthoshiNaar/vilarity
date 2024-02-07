<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 ID         : webapp-vue2-uikit3:contents
 Created    : Fri, 13 Sep 2019 14:10:25 -0500
 Modified   : Fri, 13 Sep 2019 17:46:35 -0500
 Author     : stackoverlap
 IP Address : 192.168.1.2
 User Agent : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="contents"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<ss:content name="logo">
  <ss:img class="custom-logo" alt="Logo" src="img/logo-darkbg.svg" />
</ss:content>
<ss:content name="layout">
<div id="app">
  <div v-if="false" class="app-loading uk-position-fixed uk-position-cover uk-width-1-1 uk-flex uk-flex-center uk-flex-middle uk-cover-container uk-background-secondary uk-height-viewport uk-overflow-hidden" data-uk-height-viewport="">
    <div class="uk-text-center uk-margin">
      <ss:content define="1" render="1" name="loading-logo">
        <div class="" style="width: 25vw; max-width: 300px; margin-bottom: 3em;">
          <ss:content name="logo" />
        </div>
      </ss:content>
      <div v-if="false"><span uk-spinner="ratio:3" class="uk-icon uk-spinner uk-text-muted"><svg width="90" height="90" viewBox="0 0 30 30" data-svg="spinner"><circle fill="none" stroke="#000" cx="15" cy="15" r="14" style="stroke-width: 0.333333px;"></circle></svg></span></div>
      <div v-cloak=""><span uk-spinner="ratio:3" class="uk-text-muted" /></div>
    </div>
  </div>
  <span uk-spinner="ratio:3" />
  <div v-show="$store.state.view === 'app'" v-cloak="">
    <ss:content name="header" />
    <ss:content name="column_left" />
    <ss:content name="content" />
    <ss:content name="offcanvas_right" />
    <ss:content name="footer" />
  </div>

  <ss-user-user_login />
  <ss-user-user_password_reset />
</div>
</ss:content>
<ss:content name="column_left">
  <ss:include template="column_left" />
</ss:content>
<ss:content name="offcanvas_right">
  <ss:include template="offcanvas_right" />
</ss:content>
<ss:content name="example_content">
  <ss:include template="example_content" />
</ss:content>
<ss:content name="content">
  <ss:content name="content-header" />
  <ss:content name="content-body" />
  <ss:content name="content-footer" />
</ss:content>
<ss:content name="content-header" />
<ss:content name="content-body">
  <ss:content name="content-body-full" />
</ss:content>
<ss:content name="content-body-container">
  <!-- Wrap all page content -->
  <div class="body-wrapper" id="content">
    <ss:content name="content-body-header" />
    <section class="main-body">
      <div class="uk-container">
        <ss:content name="" />
        <ss:content name="example_content-NO" />
      </div>
    </section>
    <ss:content name="content-body-footer" />
  </div>
</ss:content>
<ss:content name="content-body-full">
  <!-- CONTENT -->
  <div id="content" data-uk-height-viewport-NO="expand: true">
    <ss:content name="content-body-header" />
    <section class="main-body-full">
      <ss:content name="" />
      <ss:content name="example_content-NO" />
    </section>
    <ss:content name="content-body-footer" />
  </div>
  <!-- /CONTENT -->
</ss:content>
<ss:content name="content-body-header">
  <ss:content name="page-header-pre" />
  <section class="page-title">
    <div class="uk-grid">
      <div class="uk-width-1-1 title">
        <h2 class="content-title">
          <ss:content name="page-title" />
        </h2>
        <div class="content-pathway">
          <ss:content name="content-header-pathway" />
        </div>
      </div>
    </div>
  </section>
  <ss:content name="page-header-post" />
</ss:content>
<ss:content name="content-footer" />
<ss:content name="content-header-pathway">
  <div class="pathway-location breadcrumb">
    <ss:pathway links="1" trim="" min="1" limit="3" />
  </div>
</ss:content>

<ss:content name="theme_style_head">
<ss:content name="theme_style_head-child"/>
</ss:content>
<ss:content name="theme_style">
<!-- Styles: -->
<ss:link name="css/webapp-vue2-uikit3.css" />
<ss:link name="css/webapp-vue2-uikit3-print.css" />
<ss:link name="css/webapp-scrollbars.css" />
<ss:content name="theme_style-child"/>
</ss:content>

<ss:content name="theme_script_head">
<ss:content name="theme_script_head-child"/>
</ss:content>
<ss:content name="theme_script">
<!-- Vue: -->
<ss:script name="script/siteshader-vue.js" />
<ss:script name="script/siteshader-vue-store.js" />
<ss:script name="script/siteshader-vue-component.js" />
<ss:script name="script/siteshader-vue-router.js" />
<ss:script name="script/siteshader-vue-app.js" />
<ss:script name="script/siteshader-object.js" />
<ss:script name="script/siteshader-object-vue.js" />
<ss:script name="script/siteshader-api.js" />

<!-- ES Classes: -->
<ss:es-object-class object-class="User" id="ssobject:user\\User" />
<script>
	ss.es.classes['ssobject:user\\User'] = ss.es.classes['ssobject:User'];
	delete ss.es.classes['ssobject:User'];
</script>

<!-- Vue Mixins: -->
<ss:vue-object-mixin object-class="User" id="ssobject:user\User" />
<script>
	ss.vue.mixins['ssobject:user\\User'] = ss.vue.mixins['ssobject:User'];
	delete ss.vue.mixins['ssobject:User'];
</script>

<!-- Vue Templates: -->
<ss:vue-template template="components/user_login" />
<ss:vue-template template="components/user_password_reset" />

<ss:vue-template template="user/vue/template/user-form-user-password" />
<ss:vue-template template="user/vue/template/user-form-user-properties" />

<ss:vue-template template="webapp/vue/template/webapp-panel-resizer" />

<!-- Environment: -->
<ss:script name="js/webapp-env.js" />
<ss:script name="js/webapp-user-env.js" />

<!-- Library : -->
<ss:script name="user/script/class_user.js" />

<ss:script name="user/vue/component/user-form-user-password.js" />
<ss:script name="user/vue/component/user-form-user-properties.js" />

<!-- App: -->
<ss:script name="js/webapp-vuex-store.js" />
<ss:script name="js/webapp-vue-mixins.js" />
<ss:script name="js/webapp-vue-components.js" />
<ss:script name="js/webapp-vue-router.js" />
<ss:script name="js/webapp-vue-app.js" />

<!-- Data: -->
<ss:api-data id="ss.api#user/session" call="user/api/v1/session" />

<ss:content name="theme_script-child"/>
</ss:content>

<ss:content name="theme_script-child">
<script>
// Start the app.
	var app = new ss.vue.apps['webapp-vue']();
	app.$mount(document.querySelector('#app'));
</script>
</ss:content>

<ss:content name="framework_style">
<!-- Uikit 3: -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.2.0/css/uikit.css" />
</ss:content>
<ss:content name="framework_script">
<!-- Promise Polyfill: -->
<script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.js"></script>

<!-- Axios: -->
<script src="https://cdn.jsdelivr.net/npm/axios@0.19.2/dist/axios.min.js" integrity="sha256-T/f7Sju1ZfNNfBh7skWn0idlCBcI3RwdLSS4/I7NQKQ=" crossorigin="anonymous"></script>

<!-- VueJS 2: -->
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.11/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuex@3.1.1/dist/vuex.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-router@3.3.2/dist/vue-router.js"></script>

<!-- Uikit 3: -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.2.0/js/uikit.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.2.0/js/uikit-icons.js"></script>
</ss:content>
<ss:include template="&PARAM.template_parent;" />
</ss:template>
