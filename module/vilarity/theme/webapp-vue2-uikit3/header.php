<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 ID         : webapp-vue2-uikit3:header
 Created    : Fri, 13 Sep 2019 14:27:51 -0500
 Modified   : Fri, 13 Sep 2019 18:21:06 -0500
 Author     : stackoverlap
 IP Address : 192.168.1.2
 User Agent : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="header"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<!--HEADER-->
<header id="top-head" class="uk-position-fixed">
  <div class="uk-container uk-container-expand uk-background-primary">
    <nav class="uk-navbar uk-light" data-uk-navbar="mode:click; duration: 250">
      <div class="uk-navbar-left">
        <ss:content name="nav-logo">
        <div class="nav-logo uk-navbar-item uk-hidden@m">
          <ss:content name="user_login-logo">
            <span class="uk-logo">
              <ss:content name="logo"/>
            </span>
          </ss:content>
        </div>
        </ss:content>
        <ss:content name="nav-title"><h2 v-text="navTitle" class="nav-title uk-margin-remove"></h2></ss:content>
        <ss:content name="nav-menu">
        <ul class="nav-menu noprint uk-navbar-nav uk-visible@m">
          <li><a href="#">Accounts</a></li>
          <li>
            <a href="#">Settings <span data-uk-icon="icon: triangle-down"></span></a>
            <div class="uk-navbar-dropdown">
              <ul class="uk-nav uk-navbar-dropdown-nav">
                <li class="uk-nav-header">YOUR ACCOUNT</li>
                <li><a href="#"><span data-uk-icon="icon: info"></span> Summary</a></li>
                <li><a href="#"><span data-uk-icon="icon: refresh"></span> Edit</a></li>
                <li><a href="#"><span data-uk-icon="icon: settings"></span> Configuration</a></li>
                <li class="uk-nav-divider"></li>
                <li><a href="#"><span data-uk-icon="icon: image"></span> Your Data</a></li>
                <li class="uk-nav-divider"></li>
                <li><a href="#"><span data-uk-icon="icon: sign-out"></span> Logout</a></li>
              </ul>
            </div>
          </li>
        </ul>
        </ss:content>
        <ss:content name="nav-search">
        <div class="nav-search noprint uk-navbar-item uk-visible@s">
          <form action="" class="uk-search uk-search-default">
            <span></span>
            <input class="uk-search-input search-field" type="search" placeholder="Search" />
          </form>
        </div>
        </ss:content>
      </div>
      <div class="uk-navbar-right">
        <ss:content name="nav-icons">
        <ul class="nav-icons noprint uk-navbar-nav">
          <li><a href="#" data-uk-icon="icon:user" title="Your profile"></a></li>
          <li><a href="#" data-uk-icon="icon: settings" title="Settings"></a></li>
          <li>
             <a v-show="$root.user.busy.logout === false" href="#" data-uk-icon="icon: sign-out" title="Sign Out" v-on:click.prevent.stop="UserLogout"></a>
             <a v-show="$root.user.busy.logout === true" href="#" uk-spinner="ratio: 0.7" title="Signing Out..." v-on:click.prevent.stop=""></a>
          </li>
          <li><a class="uk-navbar-toggle" data-uk-toggle="" data-uk-navbar-toggle-icon="" href="#offcanvas-nav" title="Offcanvas"></a></li>
        </ul>
        </ss:content>
      </div>
    </nav>
  </div>
</header>
<!--/HEADER-->
</ss:template>
