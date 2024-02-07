<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#sitemap:binding/vilarity/admin"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss"
    xmlns:tab="http://www.basicmatrix.com/schema/ss/tab">
<ss:tree name="binding/vilarity/admin">
  <ss:node id="&PARAM.parent_id;">
    <ss:node id="&PARAM.id;"
         module="vilarity"
           name="&LOCALE.VILARITY;"
          title="&LOCALE.VILARITY;"
      tab:label="&LOCALE.VILARITY;"
     tabcontent="vilarity/admin"
obj-ui-tabgroup="vilarity/admin"
           icon="icons/16x16/vilarity.png">
      <ss:handle id="vilarity/admin" />

      <ss:policy name="permission" value="vilarity.backend.read" action="accept" />
      <ss:policy name="default" action="deny" />

      <ss:bind name="object/edit/ui">
        <ss:param name="tabgroup">vilarity/admin</ss:param>
      </ss:bind>
    </ss:node>
  </ss:node>
</ss:tree>
</ss:template>
