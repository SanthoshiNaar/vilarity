<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 ID         : webapp-vue2-uikit3:user/vue/components/vilarity-form-account-properties
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="user/vue/components/vilarity-form-account-properties"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div class="app-component-user-form-user-properties"
     v-bind:class="{ 'user-status-inactive' : (user &amp;&amp; user.status == 0) }">
 <div>
  <!-- change password form -->
  <form class="uk-form-stacked" v-on:submit.prevent.stop="form.action.call(this,$event)">
    <div>
      <label class="uk-form-label"><h5>Status</h5></label>
      <div class="uk-form-controls">
        <div class="uk-inline uk-width-1-1 uk-margin-small-bottom">
          <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: "></span>
          <div v-if="mode === 'view' || sessionUser.IsAllowed('user.admin') == false" class="uk-input uk-border-pill uk-form-blank" title="Status">
            <span v-if="user &amp;&amp; user.status == 0" class="user-status-inactive">Inactive</span>
            <span v-else="">Active</span>
          </div>
          <select v-else-if="mode === 'edit'"
            v-model="form.fields['status'].value"
            class="uk-select uk-border-pill"
            v-bind:name="form.fields['status'].name"
            v-bind:placeholder="form.fields['status'].placeholder ? form.fields['status'].placeholder : form.fields['status'].label"
            v-bind:required="form.fields['status'].required"
            v-bind:type="form.fields['status'].type">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
      </div>
    </div>
    <div>
      <label class="uk-form-label"><h5>Username</h5></label>
      <div class="uk-form-controls">
        <div class="uk-inline uk-width-1-1 uk-margin-small-bottom">
          <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: "></span>
          <div v-if="mode === 'view' || sessionUser.objId == user.objId || sessionUser.IsAllowed('user.admin') == false" class="uk-input uk-border-pill uk-form-blank" title="Username">{{ user.username }}</div>
          <input v-else-if="mode === 'edit'"
            v-model="form.fields['username'].value"
            class="uk-input uk-border-pill"
            v-on:change_NO="user[form.fields['username'].property] = form.fields['username'].value"
            v-bind:name="form.fields['username'].name"
            v-bind:value="form.fields['username'].value"
            v-bind:placeholder="form.fields['username'].placeholder ? form.fields['username'].placeholder : form.fields['username'].label"
            v-bind:required="form.fields['username'].required"
            v-bind:type="form.fields['username'].type" />
        </div>
      </div>
    </div>
    <div>
      <label class="uk-form-label"><h5>Vilarity Groups</h5></label>
      <div class="uk-form-controls">
        <div class="uk-inline uk-width-1-1 uk-margin-small-bottom">
          <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: "></span>
          <div v-if="mode === 'view' || sessionUser.IsAllowed('user.admin') == false" class="uk-textarea uk-form-blank" title="Vilarity Groups">
            <div>{{ (account.group.objId == 0 &amp;&amp; account.groups.length == 0) ? '&mdash;' : account.group.title }}</div>
            <div v-for="(groupObj,key,idx) in account.groups">
              <template v-if="groupObj.objId == account.group.objId"></template>
              <template v-else-if="groupObj.title != ''">{{ groupObj.title }}</template>
              <template v-else="">Group {{ groupObj.objId }}</template>
            </div>
          </div>
          <template v-else-if="mode === 'edit'">
            <div v-for="(groupObj,idx) in vilarityGroups">
              <label>
                <input
                  class="uk-checkbox"
                  v-bind:name="form.fields['vilarityGroups'].name"
                  v-bind:value="groupObj.objId"
                  v-model="form.fields['vilarityGroups'].value"
                  v-bind:placeholder="form.fields['vilarityGroups'].placeholder ? form.fields['vilarityGroups'].placeholder : form.fields['vilarityGroups'].label"
                  v-bind:required="form.fields['vilarityGroups'].required"
                  v-bind:type="form.fields['vilarityGroups'].type"/>
                <template v-if="groupObj.title != ''">{{ groupObj.title }}</template>
                <template v-else="">Group {{ groupObj.objId }}</template>
              </label>
            </div>
          </template>
        </div>
      </div>
    </div>
<!--<div v-for="field in [form.fields['username'], form.fields['email'], form.fields['name']]">-->
    <div v-for="field in form.fields">
     <template v-if="['username','status','vilarityGroups','groups'].indexOf(field.name) === -1">
      <label class="uk-form-label"><h5>{{ field.label }}</h5></label>
      <div class="uk-form-controls">
        <div class="uk-inline uk-width-1-1 uk-margin-small-bottom">
          <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: "></span>
          <input v-if="mode === 'edit'"
            v-model="field.value"
            class="uk-input uk-border-pill"
            v-on:change_NO="user[field.property] = field.value"
            v-bind:name="field.name"
            v-bind:value="field.value"
            v-bind:placeholder="field.placeholder ? field.placeholder : field.label"
            v-bind:required="field.required"
            v-bind:type="field.type" />
          <span v-else-if="mode === 'view'"
            class="uk-input uk-border-pill uk-form-blank">{{ user[field.property] ? user[field.property] : '&mdash;' }}</span>
        </div>
      </div>
     </template>
    </div>
    <div v-if="0 &amp;&amp; sessionUser.IsAllowed('user.admin')">
      <label class="uk-form-label"><h5>User Group</h5></label>
      <div class="uk-form-controls">
        <div class="uk-inline uk-width-1-1 uk-margin-small-bottom">
          <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: "></span>
{{ user.groups }}
          <div class="uk-border-pill uk-form-blank" title="Groups">
            <div v-for="userGroup in userGroups">
              <label>
              <input
                class="uk-checkbox"
                v-on:change="form.fields['groups'].value[userGroup.objId] = this.value"
                v-bind:name="form.fields['groups'].name"
                v-bind:value="userGroup.objId"
                v-bind:placeholder="form.fields['groups'].placeholder ? form.fields['groups'].placeholder : form.fields['groups'].label"
                v-bind:required="form.fields['groups'].required"
                v-bind:type="form.fields['groups'].type" /> {{ userGroup.title }}</label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="form.messages" class="uk-margin">
      <h6 v-for="(messageStr,messageId) in form.messages" v-if="messageId >= 100">{{ messageStr }}</h6>
    </div>
    <div v-if="form.errors" class="uk-margin">
      <h6 style="color: #c00;" v-for="error in form.errors">{{ error.text }}</h6>
    </div>
    <div class="uk-margin" v-if="mode === 'edit'">
      <button type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">Submit</button>
    </div>
  </form>
  <!-- /change password form -->
 </div>
</div>
</ss:template>
