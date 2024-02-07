<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#sitemap:object/vilarity/api-v1/program"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss"
    xmlns:tab="http://www.basicmatrix.com/schema/ss/tab">
<ss:tree name="object/vilarity/api-v1/program">
  <ss:node id="&PARAM.parent_id;">
    <ss:node id="&PARAM.id;">
      <ss:node id="&PARAM.id;/nodes">
        <ss:object objClass="&PARAM.objClass;" objId="&PARAM.objId;" />
        <ss:handle id="api/v1/program/nodes.json" />
        <ss:pattern>#^&PARAM.id;/nodes(\.json)?$#</ss:pattern>
        <ss:bind name="api/v1/endpoint">
          <ss:param name="template">vilarity/api-v1/program/nodes</ss:param>
        </ss:bind>
      </ss:node>
    </ss:node>
  </ss:node>
</ss:tree>
</ss:template>
