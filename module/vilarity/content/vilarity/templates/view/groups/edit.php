<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/templates/view/groups/edit"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div class="uk-height-1-1 uk-overflow uk-padding-small" style="position: relative;">
<?php.eval /*
  <vilarity-group v-bind:group="currentGroup" vueTemplate="edit" />
*/ ?>
  <div style="position: relative;">
    <div v-if="ui.busy.loading"
         style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5)">
      Loading... <i uk-spinner="ratio: 1"/>
    </div>
    <div v-else="">
      <h3><!--<a is="router-link" v-bind:to="ss.route.basePath">All Groups</a> > --><span v-if="currentGroup.title != ''">{{ currentGroup.title }}</span></h3>
      <a is="router-link" v-bind:to="ss.route.parentPath" class="uk-float-right uk-button uk-button-small uk-button-secondary">Go Back</a>
      <hr/>

      <div class="uk-width-1-3@xl uk-width-1-2@l uk-width-2-3@s uk-width-1-1">
        <vilarity-form-group-properties v-bind:group="currentGroup" />
      </div>
    </div>
  </div>
</div>
</ss:template>
