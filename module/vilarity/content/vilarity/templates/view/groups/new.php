<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/templates/view/groups/new"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div class="uk-height-1-1 uk-overflow uk-padding-small" style="position: relative;">
  <h3>New Group</h3>
  <a is="router-link" v-bind:to="ss.route.parentPath" class="uk-float-right uk-button uk-button-small uk-button-secondary">Go Back</a>
  <hr/>
  <div class="uk-width-1-3@xl uk-width-1-2@l uk-width-2-3@s uk-width-1-1">
    <vilarity-form-group-properties v-bind:group="currentGroup"
      v-on:form_success="OnNewGroup" />
  </div>
</div>
</ss:template>
