<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 ID         : webapp-vue2-uikit3:components/user_login
 Created    : Fri, 13 Sep 2019 17:40:28 -0500
 Modified   : Fri, 13 Sep 2019 18:34:37 -0500
 Author     : stackoverlap
 IP Address : 192.168.1.2
 User Agent : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="components/user_login"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div data-ss-form-name="user/login"
     v-show="$store.state.view === 'user_login'" v-cloak=""
     style="z-index:10000;"
     class="app-component-user_login user_login_actions uk-position-fixed uk-position-cover uk-width-1-1 uk-flex uk-flex-center uk-flex-middle uk-cover-container uk-background-secondary uk-height-viewport uk-overflow-hidden" data-uk-height-viewport="">
 <div class="">
  <div class="uk-text-center uk-margin">
    <ss:content name="user_login-logo">
      <ss:content name="logo"/>
    </ss:content>
  </div>

  <!-- login -->
  <form class="" action="" v-on:submit.prevent.stop="$root.UserLogin(this,$event)">
    <fieldset class="uk-fieldset">
      <div class="uk-margin-small">
        <div class="uk-inline uk-width-1-1">
          <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
          <input v-bind:disabled="$root.user.busy.login" v-model="$root.forms['user/login'].fields.username" class="uk-input uk-border-pill" required="" placeholder="Username" type="text" />
        </div>
      </div>

      <div class="uk-margin-small">
        <div class="uk-inline uk-width-1-1">
          <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: lock"></span>
          <input v-bind:disabled="$root.user.busy.login" v-model="$root.forms['user/login'].fields.password" class="uk-input uk-border-pill" required="" placeholder="Password" type="password" />
        </div>
      </div>

      <div class="uk-margin-small uk-light">
        <label><input v-bind:disabled="$root.user.busy.login" v-model="$root.forms['user/login'].fields.persist" class="uk-checkbox" type="checkbox" /> Stay Logged In</label>
      </div>

      <div class="uk-margin-small">
        <div class="uk-text-danger" v-for="(error,code) in $root.forms['user/login'].errors">{{ error }} </div>
      </div>

      <div class="uk-margin-bottom">
        <div class="uk-inline uk-width-1-1">
          <span v-show="$root.user.busy.login" class="uk-form-icon uk-form-icon-flip uk-form-icon-spinning" uk-spinner="ratio:1"></span>
          <button type="submit" v-bind:disabled="$root.user.busy.login" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">LOG IN</button>
        </div>
      </div>
    </fieldset>
  </form>
  <!-- /login -->

  <!-- action buttons -->
  <div v-if="false">
    <div class="uk-light uk-text-center">
      <a class="uk-link-reset uk-text-small toggle-class" v-on:click.prevent.stop="$root.ShowUserPasswordReset">Forgot your password?</a>
    </div>
  </div>
  <!-- action buttons -->
 </div>
</div>
</ss:template>
