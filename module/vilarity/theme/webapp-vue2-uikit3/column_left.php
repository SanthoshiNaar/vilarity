<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 ID         : webapp-vue2-uikit3:column_left
 Created    : Fri, 13 Sep 2019 14:57:01 -0500
 Modified   : Fri, 13 Sep 2019 17:16:17 -0500
 Author     : stackoverlap
 IP Address : 192.168.1.2
 User Agent : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="column_left"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<!-- LEFT BAR -->
<aside id="left-col" class="noprint uk-light uk-visible@m">
  <div class="left-logo uk-flex uk-flex-middle">
    <ss:content name="logo"/>
  </div>

  <div class="left-content-box  content-box-dark">
    <ss:img alt="" class="uk-border-circle profile-img" src="img/avatar.svg" />
    <h4 class="uk-text-center uk-margin-remove-vertical text-light">John Doe</h4>

    <div class="uk-position-relative uk-text-center uk-display-block">
      <a class="uk-text-small uk-text-muted uk-display-block uk-text-center" data-uk-icon="icon: triangle-down; ratio: 0.7" href="#">Admin</a>
      <!-- user dropdown -->

      <div class="uk-dropdown user-drop" data-uk-dropdown="mode: click; pos: bottom-center; animation: uk-animation-slide-bottom-small; duration: 150">
        <ul class="uk-nav uk-dropdown-nav uk-text-left">
          <li><a href="#"><span data-uk-icon="icon: info"></span> Summary</a></li>
          <li><a href="#"><span data-uk-icon="icon: refresh"></span> Edit</a></li>
          <li><a href="#"><span data-uk-icon="icon: settings"></span> Configuration</a></li>
          <li class="uk-nav-divider"></li>
          <li><a href="#"><span data-uk-icon="icon: image"></span> Your Data</a></li>
          <li class="uk-nav-divider"></li>
          <li><a href="#"><span data-uk-icon="icon: sign-out"></span> Sign Out</a></li>
        </ul>
      </div>
      <!-- /user dropdown -->
    </div>
  </div>

  <div class="left-nav-wrap">
    <ul class="uk-nav uk-nav-default uk-nav-parent-icon" data-uk-nav="">
      <li class="uk-nav-header">ACTIONS</li>
      <li><a href="#"><span data-uk-icon="icon: comments" class="uk-margin-small-right"></span>Messages</a></li>
      <li><a href="#"><span data-uk-icon="icon: users" class="uk-margin-small-right"></span>Friends</a></li>
      <li class="uk-parent"><a href="#"><span data-uk-icon="icon: thumbnails" class="uk-margin-small-right"></span>Templates</a>
        <ul class="uk-nav-sub">
          <li><a title="Article" href="#">Article</a></li>
          <li><a title="Album" href="#">Album</a></li>
          <li><a title="Cover" href="#">Cover</a></li>
          <li><a title="Cards" href="#">Cards</a></li>
          <li><a title="News Blog" href="#">News Blog</a></li>
          <li><a title="Price" href="#">Price</a></li>
          <li><a title="Login" href="#">Login</a></li>
          <li><a title="Login-Dark" href="#">Login - Dark</a></li>
        </ul>
      </li>
      <li><a href="#"><span data-uk-icon="icon: album" class="uk-margin-small-right"></span>Albums</a></li>
      <li><a href="#"><span data-uk-icon="icon: thumbnails" class="uk-margin-small-right"></span>Featured Content</a></li>
      <li><a href="#"><span data-uk-icon="icon: lifesaver" class="uk-margin-small-right"></span>Tips</a></li>
      <li class="uk-parent"><a href="#"><span data-uk-icon="icon: comments" class="uk-margin-small-right"></span>Reports</a>
        <ul class="uk-nav-sub">
          <li><a href="#">Sub item</a></li>
          <li><a href="#">Sub item</a></li>
        </ul>
      </li>
    </ul>

    <div class="left-content-box uk-margin-top">
      <h5>Daily Reports</h5>

      <div>
        <span class="uk-text-small">Traffic <small>(+50)</small></span>
        <progress class="uk-progress" value="50" max="100"></progress>
      </div>

      <div>
        <span class="uk-text-small">Income <small>(+78)</small></span>
        <progress class="uk-progress success" value="78" max="100"></progress>
      </div>

      <div>
        <span class="uk-text-small">Feedback <small>(-12)</small></span>
        <progress class="uk-progress warning" value="12" max="100"></progress>
      </div>
    </div>
  </div>

  <div class="bar-bottom">
    <ul class="uk-subnav uk-flex uk-flex-center uk-child-width-1-5" data-uk-grid="">
      <li></li>
      <li></li>
      <li></li>
      <li></li>
    </ul>
  </div>
</aside>
<!-- /LEFT BAR -->
</ss:template>
