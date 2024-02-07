<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#sitemap:binding/vilarity/app"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss"
    xmlns:tab="http://www.basicmatrix.com/schema/ss/tab">
<ss:tree name="binding/vilarity/app">

<!-- 404 Response: Handles non-app requests -->
  <ss:node id="404" content="null" theme="null" status="201">
  </ss:node>

<!-- Vilarity App: -->
  <ss:node id="&PARAM.id;" content="vilarity/app" name="Vilarity" title="">
    <ss:pattern>#.*#</ss:pattern>
  </ss:node>

</ss:tree>
</ss:template>
