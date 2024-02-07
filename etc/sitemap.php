<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 * sitemap.php
 *     : Defines a sitemap for use with a Site Shader profile.
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:tree name="sitemap"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<!-- Frontend: -->
  <ss:node id="" title="Vilarity">
    <ss:bind name="vilarity/app">
    </ss:bind>
  </ss:node>

<!-- API -->
  <ss:node id="api">
    <ss:bind name="vilarity/api">
    </ss:bind>
  </ss:node>

<!-- Platform Backend: -->
  <ss:node id="admin/">
    <ss:bind name="admin" />
  </ss:node>
</ss:tree>
