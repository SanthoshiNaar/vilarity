<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 ID         : webapp-vue2-uikit3:user/vue/template/user-form-user-password
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="user/vue/template/user-form-user-password"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div class="app-component-user_form_user_password">
 <div class="">
  <!-- change password form -->
  <form class="uk-form-stacked" v-on:submit.prevent.stop="form.action.call(this,$event)">
    <div>
      <label class="uk-form-label"><h5>Username</h5></label>
      <div class="uk-form-controls">
        <div class="uk-inline uk-width-1-1 uk-margin-small-bottom">
          <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: "></span>
          <div class="uk-input uk-border-pill uk-form-blank" title="Username">{{ user.username }}</div>
        </div>
      </div>
    </div>
    <div v-for="field in [form.fields['passwordCurrent'], form.fields['password'], form.fields['passwordVerify']]">
      <label class="uk-form-label"><h5>{{ field.label }}</h5></label>
      <div class="uk-form-controls">
        <div class="uk-inline uk-width-1-1 uk-margin-small-bottom">
          <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: "></span>
          <input v-model="field.value"
            class="uk-input uk-border-pill"
            v-bind:placeholder="field.placeholder ? field.placeholder : field.label"
            v-bind:required="field.required"
            v-bind:type="field.type" />
        </div>
      </div>
    </div>
    <div v-if="form.messages" class="uk-margin">
      <h6 v-for="(messageStr,messageId) in form.messages">{{ messageStr }}</h6>
    </div>
    <div v-if="form.errors" class="uk-margin">
      <h6 style="color: #c00;" v-for="error in form.errors">{{ error.text }}</h6>
    </div>
    <div class="uk-margin">
      <button type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">Change Password</button>
    </div>
  </form>
  <!-- /change password form -->
 </div>
</div>
</ss:template>
