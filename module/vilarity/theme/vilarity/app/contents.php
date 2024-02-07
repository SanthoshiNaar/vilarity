<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="contents"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">

<ss:content name="viewport">width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui</ss:content>

<ss:content name="meta/viewport">
<?php _T::$_->qManifestURL = htmlspecialchars(_T::$_->GetURL('vilarity/app-manifest.json')); ?>
<link rel="manifest" href="&PARAM.qManifestURL;"/>

<meta name="mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="application-name" content="Vilarity"/>
<meta name="apple-mobile-web-app-title" content="Vilarity"/>
<meta name="theme-color" content="#000000"/>
<meta name="msapplication-navbutton-color" content="#000000"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<meta name="msapplication-starturl" content="/"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no, minimal-ui"/>
</ss:content>

<ss:content name="logo">
  <ss:link content=""><router-link to="/"><ss:content name="logo-darkbg" /></router-link></ss:link>
</ss:content>

<ss:content name="logo-darkbg">
<?php
	_T::$_->qAltTag = htmlspecialchars(_R::$_->APP_TITLE);

	if (_T::$_->GetFilePath('img/logo-darkbg.png') != ''):
	// Found a PNG:
?><ss:img src="img/logo-darkbg.png" alt="&PARAM.qAltTag;" class="custom-logo" /><?php
	elseif (_T::$_->GetFilePath('img/logo-darkbg.jpg') != ''):
	// Found a JPG:
?><ss:img src="img/logo-darkbg.jpg" alt="&PARAM.qAltTag;" class="custom-logo" /><?php
	elseif (_T::$_->GetFilePath('img/logo-darkbg.svg') != ''):
	// Found a SVG:
?><ss:img src="img/logo-darkbg.svg" alt="&PARAM.qAltTag;" class="custom-logo" /><?php
	endif;
?>
</ss:content>

<ss:content name="logo-lightbg">
<?php
	_T::$_->qAltTag = htmlspecialchars(_R::$_->APP_TITLE);

	if (_T::$_->GetFilePath('img/logo-lightbg.png') != ''):
	// Found a PNG:
?><ss:img src="img/logo-lightbg.png" alt="&PARAM.qAltTag;" class="custom-logo" /><?php
	elseif (_T::$_->GetFilePath('img/logo-lightbg.jpg') != ''):
	// Found a JPG:
?><ss:img src="img/logo-lightbg.jpg" alt="&PARAM.qAltTag;" class="custom-logo" /><?php
	elseif (_T::$_->GetFilePath('img/logo-lightbg.svg') != ''):
	// Found a SVG:
?><ss:img src="img/logo-lightbg.svg" alt="&PARAM.qAltTag;" class="custom-logo" /><?php
	endif;
?>
</ss:content>

<ss:content name="nav-menu"/>
<ss:content name="nav-search"/>
<ss:content name="nav-icons">
<ul v-cloak="" class="uk-navbar-nav uk-margin-right" uk-tab="">
  <li v-if="effectiveAccount &amp;&amp; effectiveAccount.objId != 0 &amp;&amp; effectiveAccount.objId != account.objId"
    class="uk-active" style="background:#fff; color:#000;">
    <a style="background:#fff; color:#000;">{{ effectiveAccount.user.name ? effectiveAccount.user.name : effectiveAccount.user.username }}
      <span style="background:#777; color:#fff;" class="uk-icon-button uk-margin-left uk-float-right"
        uk-icon="icon: close; ratio: 1"
        v-on:click="$root.SetEffectiveAccount(null);"></span>
    </a>
  </li>
</ul>
<ul class="uk-navbar-nav">
  <li>
    <a v-show="$root.user.busy.logout === false" href="#" data-uk-icon="icon: sign-out" title="Sign Out" v-on:click.prevent.stop="UserLogout"></a>
    <a v-show="$root.user.busy.logout === true" href="#" uk-spinner="ratio: 0.7" title="Signing Out..." v-on:click.prevent.stop=""></a>
  </li>
  <li class="uk-margin-left uk-hidden@m"><a class="uk-navbar-toggle" data-uk-toggle="" data-uk-navbar-toggle-icon="" href="#offcanvas-nav" title="Menu"></a></li>
</ul>
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
      <div v-cloak=""><span uk-spinner="ratio:3" /></div>
    </div>
  </div>

  <div v-show="$store.state.view === 'app'" v-cloak="" class="app-layout uk-height-1-1">
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

<ss:content name="main-nav-vertical">
  <div class="left-logo uk-flex uk-flex-middle">
    <ss:content name="logo"/>
  </div>
<!--
  <div class="left-content-box content-box-dark"></div>
-->
  <div class="left-nav-wrap" style="flex: 1;">
    <ul class="uk-nav uk-nav-default uk-nav-parent-icon" data-uk-nav="">
      <li class="uk-nav-header"></li>
<!-- Root Level: -->
      <template v-if="!currentProgram">
        <template v-for="programObj in $root.FilterByProgramAccess($root.viewAccount,programs)">
          <li v-bind:style="{'font-style' : (HasProgramAccess(viewAccount,programObj) ? '' : 'italic')}"
              style="font-size: 1.1em;">
            <a is="router-link"
              v-bind:to="'/'+programObj.key"
              v-bind:href="programObj.key"
              v-bind:title="programObj.title"><span data-uk-icon="icon: " class="uk-hidden uk-margin-small-right"></span>{{ programObj.title }}</a>
          </li>
        </template>
      </template>

<!-- Program Tree Level: -->
      <template v-else-if="currentProgram">
        <template v-for="program in $root.FilterByProgramAccess($root.viewAccount,programs)">
          <li v-bind:style="{'font-style' : (HasProgramAccess(viewAccount,program) ? '' : 'italic')}"
              style="font-size: 1.1em;">
            <a is="router-link"
              v-bind:to="'/'+program.key"
              v-bind:href="program.key"
              v-bind:title="program.title"><span data-uk-icon="icon: " class="uk-hidden uk-margin-small-right"></span>{{ program.title }}</a>
          </li>

          <template v-if="program.UUID === currentProgram.UUID">
            <template v-for="seriesProgramNode in $root.FilterByProgramAccess($root.viewAccount,program.childNodes)">
              <li v-if="seriesProgramNode.type === 'series'"
                  v-bind:style="{'font-style' : (HasProgramAccess(viewAccount,seriesProgramNode) ? '' : 'italic')}">
                <a is="router-link"
                  style="padding-left:40px;"
                  v-bind:to="'/'+program.key+'/'+seriesProgramNode.key"
                  v-bind:href="seriesProgramNode.key"
                  v-bind:title="seriesProgramNode.title"><span data-uk-icon="icon: " class="uk-hidden uk-margin-small-right"></span>{{ seriesProgramNode.title }}</a>
              </li>

              <template v-if="currentProgramNode &amp;&amp; (seriesProgramNode.UUID === currentProgramNode.UUID || seriesProgramNode.UUID === currentProgramNode.parent.UUID)">
                <template v-for="sessionProgramNode in $root.FilterByProgramAccess($root.viewAccount,seriesProgramNode.childNodes)">
                  <li v-if="sessionProgramNode.type === 'session'"
                      v-bind:style="{'font-style' : (HasProgramAccess(viewAccount,sessionProgramNode) ? '' : 'italic')}">
                    <a is="router-link"
                      style="padding-left:60px;"
                      v-bind:to="'/'+program.key+'/'+sessionProgramNode.key"
                      v-bind:href="sessionProgramNode.key"
                      v-bind:title="sessionProgramNode.title"><span data-uk-icon="icon: " class="uk-hidden uk-margin-small-right"></span>{{ sessionProgramNode.title }}</a>
                  </li>
                </template>
              </template>

           </template>
          </template>
        </template>
      </template>

<!-- NOTE: THESE MENU STYLE OPTIONS ARE NOT USED. -->
<!-- N.B.: IF REACTIVATED, THESE NODES DO NOT CHECK VILARITY GROUP PERMISSIONS. -->
<!-- Program Level: -->
      <template v-else-if="false &amp;&amp; !currentProgramNode">
        <template v-for="programObj in programs">
          <li style="">
            <a is="router-link"
              v-bind:to="'/'+programObj.key"
              v-bind:href="programObj.key"
              v-bind:title="programObj.title"><span data-uk-icon="icon: " class="uk-hidden uk-margin-small-right"></span>{{ programObj.title }}</a>
          </li>

          <template v-if="programObj.UUID === currentProgram.UUID">
           <template v-for="programNode in programObj.childNodes">
            <li v-if="programNode.type == 'series'">
              <a is="router-link"
                style="padding-left:40px;"
                v-bind:to="'/'+programObj.key+'/'+programNode.key"
                v-bind:href="programNode.key"
                v-bind:title="programNode.title"><span data-uk-icon="icon: " class="uk-hidden uk-margin-small-right"></span>{{ programNode.title }}</a>
            </li>
           </template>
          </template>
        </template>
      </template>

<!-- Series Level: -->
      <template v-else-if="false &amp;&amp; currentProgramNode &amp;&amp; currentProgramNode.type == 'series'">
        <template v-for="programNode in currentProgram.childNodes">
          <li v-if="programNode.type == 'series'" style="font-size: 1.1em;">
            <a is="router-link"
              v-bind:to="'/'+currentProgram.key+'/'+programNode.key"
              v-bind:href="programNode.key"
              v-bind:title="programNode.title"><span data-uk-icon="icon: " class="uk-hidden uk-margin-small-right"></span>{{ programNode.title }}</a>
          </li>

          <template v-if="programNode.UUID === currentProgramNode.UUID">
           <template v-for="childProgramNode in programNode.childNodes">
            <li v-if="childProgramNode.type == 'session'">
              <a is="router-link"
                style="padding-left:40px;"
                v-bind:to="'/'+currentProgram.key+'/'+childProgramNode.key"
                v-bind:href="childProgramNode.key"
                v-bind:title="childProgramNode.title"><span data-uk-icon="icon: " class="uk-hidden uk-margin-small-right"></span>{{ childProgramNode.title }}</a>
            </li>
           </template>
          </template>
        </template>
      </template>

<!-- Session Level: -->
      <template v-else-if="false &amp;&amp; currentProgramNode &amp;&amp; currentProgramNode.type == 'session' &amp;&amp; currentProgramNode.parent.objId">
        <template v-for="programNode in currentProgram.childNodes">
          <li style="font-size: 1.1em;">
            <a is="router-link"
              v-bind:to="'/'+currentProgram.key+'/'+programNode.key"
              v-bind:href="programNode.key"
              v-bind:title="programNode.title"><span data-uk-icon="icon: " class="uk-hidden uk-margin-small-right"></span>{{ programNode.title }}</a>
          </li>

          <template v-if="programNode.UUID === currentProgramNode.parent.UUID">
           <template v-for="childProgramNode in programNode.childNodes">
            <li style="">
              <a is="router-link"
                style="padding-left:40px;"
                v-bind:to="'/'+currentProgram.key+'/'+childProgramNode.key"
                v-bind:href="childProgramNode.key"
                v-bind:title="childProgramNode.title"><span data-uk-icon="icon: " class="uk-hidden uk-margin-small-right"></span>{{ childProgramNode.title }}</a>
            </li>
<!-- Part Level, not used.
            <template v-if="childProgramNode.UUID === currentProgramNode.UUID">
             <template v-for="childChildProgramNode in childProgramNode.childNodes">
              <li >
                <a is="router-link"
                  style="padding-left:60px;"
                  v-bind:to="'/'+currentProgram.key+'/'+childChildProgramNode.key"
                  v-bind:href="childChildProgramNode.key"
                  v-bind:title="childChildProgramNode.title"><span data-uk-icon="icon: " class="uk-hidden uk-margin-small-right"></span>{{ childChildProgramNode.title }}</a>
              </li>
             </template>
            </template>
-->
           </template>
          </template>
        </template>
      </template>

      <hr/>
    </ul>
  </div>

  <div class="bar-bottom" style="position: relative; padding-bottom: 30px;">
    <ul class="uk-nav uk-nav-default uk-nav-parent-icon" data-uk-nav="">
      <li><a is="router-link" to="/account" href="account" title="Account"><span data-uk-icon="icon: user" class="uk-margin-small-right"></span>Account</a></li>
      <li v-if="sessionUser.IsAllowed('vilarity.accounts')"><a is="router-link" to="/accounts" href="accounts" title="Manage Accounts"><span data-uk-icon="icon: users" class="uk-margin-small-right"></span>Manage Accounts</a></li>
      <li v-if="sessionUser.IsAllowed('vilarity.groups')"><a is="router-link" to="/groups" href="groups" title="Manage Groups"><span data-uk-icon="icon: list" class="uk-margin-small-right"></span>Manage Groups</a></li>
      <li v-if="sessionUser.IsAllowed('vilarity.settings')"><a is="router-link" to="/settings" href="settings" title="Settings"><span data-uk-icon="icon: cog" class="uk-margin-small-right"></span>Settings</a></li>
<?php.eval /* TODO: Not yet.
      <li><a is="router-link" to="/help" href="help" title="Help"><span data-uk-icon="icon: lifesaver" class="uk-margin-small-right"></span>Help</a></li>
*/ ?>
    </ul>
  </div>
</ss:content>

<ss:content name="column_left">
<!-- LEFT BAR: -->
<aside id="left-col" class="uk-light uk-visible@m" style="display: flex; flex-direction: column;">
  <ss:content name="main-nav-vertical"/>
</aside>
<!-- /LEFT BAR -->
</ss:content>

<ss:content name="offcanvas_right">
<!-- OFFCANVAS -->
<div id="offcanvas-nav" data-uk-offcanvas="flip: true; overlay: true">
  <div class="uk-offcanvas-bar uk-offcanvas-bar-animation uk-offcanvas-slide">
    <ss:content name="main-nav-vertical"/>
  </div>
</div>
<!-- /OFFCANVAS -->
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
  <div id="content" class="container-wrapper" data-uk-height-viewport-NO="expand: true">
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
  <div id="content" class="full-wrapper" data-uk-height-viewport-NO="expand: true">
    <ss:content name="content-body-header-NO" />
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

<ss:content name="theme_style-child">
</ss:content>

<ss:content name="theme_script-child">
</ss:content>

<ss:content name="framework_style-child">
</ss:content>

<ss:include template="&PARAM.template_parent;" />
</ss:template>
