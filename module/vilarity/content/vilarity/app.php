<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 * vilarity/app.php
 *     : Vilarity App.
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="vilarity/app"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<ss:content name="">
<div v-if="$root.$route.name === null">
  <div v-if="$root.ui.bShowLoadingThrobber"
       style="position: absolute; z-index:1; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.25)">
    <i style="color: black; position: absolute; left: 50%; top: 50%; margin-left: -45px; margin-top: -45px;" uk-spinner="ratio:3" />
  </div>
</div>
<router-view v-else=""></router-view>
</ss:content>

<ss:content name="page_style">
<ss:link name="vilarity/css/vilarity-app.css" />
<ss:link name="vilarity/css/vilarity-app-extend.css" />
</ss:content>

<ss:content name="page_script">
<!-- Imports: -->
<script src="https://cdn.jsdelivr.net/npm/uuid@latest/dist/umd/uuidv4.min.js"></script>

<!-- CKEditor: -->
<script src="https://cdn.jsdelivr.net/npm/ckeditor4@4.17.1/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ckeditor4-vue@1.4.0/dist/ckeditor.js"></script>
<script>
Vue.use(CKEditor);
</script>

<!-- ES Classes: -->
<ss:es-object-class object-class="vilarity\Account" />
<ss:es-object-class object-class="vilarity\Group" />
<ss:es-object-class object-class="vilarity\GroupMember" />
<ss:es-object-class object-class="vilarity\Program" />
<ss:es-object-class object-class="vilarity\ProgramNode" />
<ss:es-object-class object-class="vilarity\ProgramRecord" />

<!-- Vue Mixins: -->
<ss:vue-object-mixin object-class="vilarity\Account" />
<ss:vue-object-mixin object-class="vilarity\Group" />
<ss:vue-object-mixin object-class="vilarity\GroupMember" />
<ss:vue-object-mixin object-class="vilarity\Program" />
<ss:vue-object-mixin object-class="vilarity\ProgramNode" />
<ss:vue-object-mixin object-class="vilarity\ProgramRecord" />

<!-- Vue Templates: -->
<ss:vue-template template="vilarity/vue/components/object-list" />

<ss:vue-template template="vilarity/vue/components/resource-uploader" />

<ss:vue-template template="vilarity/vue/components/program" />

<ss:vue-template template="vilarity/vue/components/program_node" />
<ss:vue-template template="vilarity/vue/components/program_node/series" />
<ss:vue-template template="vilarity/vue/components/program_node/session" />
<ss:vue-template template="vilarity/vue/components/program_node/part" />
<ss:vue-template template="vilarity/vue/components/program_node/point" />

<ss:vue-template template="vilarity/vue/components/program_node/series-panel" />
<ss:vue-template template="vilarity/vue/components/program_node/session-panel" />
<ss:vue-template template="vilarity/vue/components/program_node/part-panel" />
<ss:vue-template template="vilarity/vue/components/program_node/point-panel" />

<ss:vue-template template="vilarity/vue/components/vilarity-form-account-properties" />
<ss:vue-template template="vilarity/vue/components/vilarity-form-group-properties" />

<ss:vue-template template="vilarity/templates/view/default" />
<ss:vue-template template="vilarity/templates/view/program" />
<ss:vue-template template="vilarity/templates/view/program_node" />

<ss:vue-template template="vilarity/templates/view/account" />
<ss:vue-template template="vilarity/templates/view/account/edit" />
<ss:vue-template template="vilarity/templates/view/account/password" />

<ss:vue-template template="vilarity/templates/view/accounts" />
<ss:vue-template template="vilarity/templates/view/accounts/new" />
<ss:vue-template template="vilarity/templates/view/accounts/view" />
<ss:vue-template template="vilarity/templates/view/accounts/edit" />
<ss:vue-template template="vilarity/templates/view/accounts/password" />

<ss:vue-template template="vilarity/templates/view/groups" />
<ss:vue-template template="vilarity/templates/view/groups/new" />
<ss:vue-template template="vilarity/templates/view/groups/edit" />

<ss:vue-template template="vilarity/templates/view/settings" />

<!-- Environment: -->
<ss:script name="vilarity/js/vilarity-env.js" />

<!-- Library : -->
<ss:script name="vilarity/js/class_account.js" />
<ss:script name="vilarity/js/class_group.js" />
<ss:script name="vilarity/js/class_group_member.js" />
<ss:script name="vilarity/js/class_program.js" />
<ss:script name="vilarity/js/class_program_node.js" />
<ss:script name="vilarity/js/class_program_record.js" />
<ss:script name="vilarity/js/vilarity-draggable.js" />

<!-- App: -->
<ss:script name="vilarity/js/vilarity-store.js" />
<ss:script name="vilarity/js/vilarity-mixins.js" />
<ss:script name="vilarity/js/vilarity-mixin-program_node-resource.js" />
<ss:script name="vilarity/js/vilarity-components.js" />
<ss:script name="vilarity/js/vilarity-component-object-list.js" />
<ss:script name="vilarity/js/vilarity-component-resource-uploader.js" />
<ss:script name="vilarity/js/vilarity-component-program_node.js" />
<ss:script name="vilarity/js/vilarity-component-program_node-panel.js" />
<ss:script name="vilarity/js/vilarity-form-account-properties.js" />
<ss:script name="vilarity/js/vilarity-form-group-properties.js" />
<ss:script name="vilarity/js/vilarity-view-default.js" />
<ss:script name="vilarity/js/vilarity-view-program.js" />
<ss:script name="vilarity/js/vilarity-view-account.js" />
<ss:script name="vilarity/js/vilarity-view-accounts.js" />
<ss:script name="vilarity/js/vilarity-view-groups.js" />
<ss:script name="vilarity/js/vilarity-view-settings.js" />
<ss:script name="vilarity/js/vilarity-routes.js" />
<ss:script name="vilarity/js/vilarity-app.js" />
<ss:script name="vilarity/js/vilarity-app-extend.js" />

<!-- Data: -->
<ss:api-data id="vilarity.api#account/session" call="api/v1/account/session" />
<ss:api-data id="vilarity.api#settings" call="api/v1/settings" />
<ss:api-data id="vilarity.api#programs" call="api/v1/program" />
<ss:api-data id="vilarity.api#groups" call="api/v1/group" />

<script>
// Start the app.
	var app = new ss.vue.apps['vilarity']();
	app.$mount(document.querySelector('#app'));
</script>
</ss:content>
</ss:template>
