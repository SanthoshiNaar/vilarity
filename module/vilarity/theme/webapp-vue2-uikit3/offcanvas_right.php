<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 ID         : webapp-vue2-uikit3:offcanvas_right
 Created    : Fri, 13 Sep 2019 14:57:01 -0500
 Modified   : Fri, 13 Sep 2019 16:57:01 -0500
 Author     : stackoverlap
 IP Address : 192.168.1.2
 User Agent : Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="offcanvas_right"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<!-- OFFCANVAS -->
<div id="offcanvas-nav" data-uk-offcanvas="flip: true; overlay: true">
  <div class="uk-offcanvas-bar uk-offcanvas-bar-animation uk-offcanvas-slide">
    <button class="uk-offcanvas-close uk-close uk-icon" type="button" data-uk-close=""></button>
    <ul class="uk-nav uk-nav-default">
      <li class="uk-active"><a href="#">Active</a></li>
      <li class="uk-parent"><a href="#">Parent</a>
        <ul class="uk-nav-sub">
          <li><a href="#">Sub item</a></li>
          <li><a href="#">Sub item</a></li>
        </ul>
      </li>
      <li class="uk-nav-header">Header</li>
      <li><a href="#js-options"><span class="uk-margin-small-right uk-icon" data-uk-icon="icon: table"></span> Item</a></li>
      <li><a href="#"><span class="uk-margin-small-right uk-icon" data-uk-icon="icon: thumbnails"></span> Item</a></li>
      <li class="uk-nav-divider"></li>
      <li><a href="#"><span class="uk-margin-small-right uk-icon" data-uk-icon="icon: trash"></span> Item</a></li>
    </ul>

    <h3>Title</h3>

    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
      tempor
      incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
      quis
      nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
      consequat.</p>
  </div>
</div>
<!-- /OFFCANVAS -->
</ss:template>
