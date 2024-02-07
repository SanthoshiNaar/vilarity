<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 ID         : webapp-vue2-uikit3:components/user_password_reset
 Created    : Fri, 13 Sep 2019 17:42:30 -0500
 Modified   : Fri, 13 Sep 2019 18:35:08 -0500
 Author     : stackoverlap
 IP Address : 192.168.1.2
 User Agent : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="components/user_password_reset"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div v-show="$store.state.view === 'user_password_reset'" v-cloak=""
	style="z-index:10000;"
	class="app-component-user_password_reset user_login_actions uk-position-fixed uk-position-cover uk-width-1-1 uk-cover-container uk-background-secondary uk-flex uk-flex-center uk-flex-middle uk-height-viewport uk-overflow-hidden" data-uk-height-viewport="">
 <div class="">
  <div class="uk-text-center uk-margin">
    <ss:content name="user_password_reset-logo">
      <ss:content name="logo"/>
    </ss:content>
  </div>

  <!-- recover password -->
  <form class="" action="" v-on:submit.prevent.stop="$root.UserPasswordReset(this,$event)">
    <div class="uk-margin-small">
      <div class="uk-inline uk-width-1-1">
        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: mail"></span>
        <input class="uk-input uk-border-pill" placeholder="E-mail" required="required" type="text" />
      </div>
    </div>
    <div class="uk-margin-bottom">
      <button type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">RESET PASSWORD</button>
    </div>
  </form>
  <!-- /recover password -->

  <!-- action buttons -->
  <div>
    <div class="uk-light uk-text-center">
      <a class="uk-link-reset uk-text-small toggle-class" v-on:click.prevent.stop="$root.ShowUserLogin"><span data-uk-icon="arrow-left"></span> Back to Login</a>
    </div>
  </div>
  <!-- action buttons -->
 </div>
</div>
</ss:template>
