<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="webapp/vue/template/webapp-panel-resizer"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<span class="webapp-panel-resizer"
      v-bind:class="'webapp-panel-resizer-' + position">
  <span v-if="CanPanelExpand(panelName)" class="webapp-panel-resizer-expand" title="Expand"
        v-on:click="OnExpandPanel(panelName)">
    <i v-if="panelPosition == 'top'"    class="expand-down"  uk-icon="icon: chevron-down;  ratio: 1.0" />
    <i v-if="panelPosition == 'right'"  class="expand-left"  uk-icon="icon: chevron-left;  ratio: 1.0" />
    <i v-if="panelPosition == 'bottom'" class="expand-up"    uk-icon="icon: chevron-up;    ratio: 1.0" />
    <i v-if="panelPosition == 'left'"   class="expand-right" uk-icon="icon: chevron-right; ratio: 1.0" />
  </span>
  <span v-if="CanPanelCollapse(panelName)" class="webapp-panel-resizer-collapse" title="Collapse"
        v-on:click="OnCollapsePanel(panelName)">
    <i v-if="panelPosition == 'top'"    class="collapse-up"    uk-icon="icon: chevron-up;    ratio: 1.0" />
    <i v-if="panelPosition == 'right'"  class="collapse-right" uk-icon="icon: chevron-right; ratio: 1.0" />
    <i v-if="panelPosition == 'bottom'" class="collapse-down"  uk-icon="icon: chevron-down;  ratio: 1.0" />
    <i v-if="panelPosition == 'left'"   class="collapse-left"  uk-icon="icon: chevron-left;  ratio: 1.0" />
  </span>
</span>
</ss:template>
