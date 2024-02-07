<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#sitemap:binding/vilarity/api/v1"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss"
    xmlns:tab="http://www.basicmatrix.com/schema/ss/tab">
<ss:tree name="binding/vilarity/api">

<!-- Vilarity API v1: -->
  <ss:node id="&PARAM.id;/v1">
    <ss:handle id="api/v1" />

    <ss:bind name="api/v1">
      <ss:param name="api-version">1.0.0</ss:param>
    </ss:bind>

<!--<ss:policy name="permission" value="api.read" action="accept" />-->
    <ss:policy name="default" action="accept" />

    <ss:node id="&PARAM.id;/v1/settings">
      <ss:handle id="api/v1/settings.json" />
      <ss:pattern>#^&PARAM.id;/v1/settings(\.json)?$#</ss:pattern>
      <ss:bind name="api/v1/endpoint">
        <ss:param name="template">vilarity/api-v1/settings</ss:param>
      </ss:bind>
    </ss:node>

    <ss:node id="&PARAM.id;/v1/user">
      <ss:bind name="user/api/v1">
      </ss:bind>
    </ss:node>

    <ss:node id="&PARAM.id;/v1/account">
      <ss:handle id="api/v1/account.json" />
      <ss:pattern>#^&PARAM.id;/v1/account(\.json)?$#</ss:pattern>
      <ss:bind name="api/v1/object">
        <ss:param name="object-class">vilarity\Account</ss:param>
      </ss:bind>

      <ss:node id="&PARAM.id;/v1/account/session">
        <ss:handle id="api/v1/account/session.json" />
        <ss:pattern>#^&PARAM.id;/v1/account/session(\.json)?$#</ss:pattern>
        <ss:bind name="api/v1/endpoint">
          <ss:param name="template">vilarity/api-v1/account/session</ss:param>
        </ss:bind>
      </ss:node>
    </ss:node>

    <ss:node id="&PARAM.id;/v1/group">
      <ss:handle id="api/v1/group.json" />
      <ss:pattern>#^&PARAM.id;/v1/group(\.json)?$#</ss:pattern>
      <ss:bind name="api/v1/object">
        <ss:param name="object-class">vilarity\Group</ss:param>
      </ss:bind>
    </ss:node>

    <ss:node id="&PARAM.id;/v1/resource">
      <ss:handle id="api/v1/resource.json" />
      <ss:pattern>#^&PARAM.id;/v1/resource(\.json)?$#</ss:pattern>
      <ss:bind name="api/v1/endpoint">
        <ss:param name="template">vilarity/api-v1/resource</ss:param>
      </ss:bind>

      <ss:node id="&PARAM.id;/v1/resource/photo">
        <ss:handle id="api/v1/resource/photo.json" />
        <ss:pattern>#^&PARAM.id;/v1/resource/photo(\.json)?$#</ss:pattern>
        <ss:bind name="api/v1/resource">
          <ss:param name="resource-type">gallery</ss:param>
          <ss:param name="resource-gallery">vilarity-program-photos</ss:param>
        </ss:bind>
      </ss:node>

      <ss:node id="&PARAM.id;/v1/resource/file">
        <ss:handle id="api/v1/resource/file.json" />
        <ss:pattern>#^&PARAM.id;/v1/resource/file(\.json)?$#</ss:pattern>
        <ss:bind name="api/v1/resource">
          <ss:param name="resource-type">volume</ss:param>
          <ss:param name="resource-volume">vilarity-program-files</ss:param>
        </ss:bind>
      </ss:node>
<!--
      <ss:node id="&PARAM.id;/v1/resource/upload">
        <ss:handle id="api/v1/resource/upload.json" />
        <ss:pattern>#^&PARAM.id;/v1/resource/upload(\.json)?$#</ss:pattern>
        <ss:bind name="api/v1/resource">
          <ss:param name="resource-type">file</ss:param>
          <ss:param name="resource-file-path"></ss:param>
        </ss:bind>
      </ss:node>
-->
<!--
      <ss:node id="&PARAM.id;/v1/resource/upload">
        <ss:handle id="api/v1/resource/upload.json" />
        <ss:pattern>#^&PARAM.id;/v1/resource/upload(\.json)?$#</ss:pattern>
        <ss:bind name="api/v1/endpoint">
          <ss:param name="template">vilarity/api-v1/resource/upload</ss:param>
        </ss:bind>
      </ss:node>
-->
    </ss:node>

    <ss:node id="&PARAM.id;/v1/program">
      <ss:handle id="api/v1/program" />
      <ss:handle id="api/v1/program.json" />
      <ss:pattern>#^&PARAM.id;/v1/program(\.json)?$#</ss:pattern>
      <ss:bind name="api/v1/object">
        <ss:param name="object-class">vilarity\Program</ss:param>
      </ss:bind>
    </ss:node>

    <ss:node id="&PARAM.id;/v1/program/node">
      <ss:handle id="api/v1/program/node" />
      <ss:handle id="api/v1/program/node.json" />
      <ss:pattern>#^&PARAM.id;/v1/program/node(\.json)?$#</ss:pattern>
      <ss:bind name="api/v1/object">
        <ss:param name="object-class">vilarity\ProgramNode</ss:param>
      </ss:bind>
    </ss:node>

    <ss:node id="&PARAM.id;/v1/program/record">
      <ss:handle id="api/v1/program/record" />
      <ss:handle id="api/v1/program/record.json" />
      <ss:pattern>#^&PARAM.id;/v1/program/record(\.json)?$#</ss:pattern>
      <ss:bind name="api/v1/object">
        <ss:param name="object-class">vilarity\ProgramRecord</ss:param>
      </ss:bind>
    </ss:node>
  </ss:node>

</ss:tree>
</ss:template>
