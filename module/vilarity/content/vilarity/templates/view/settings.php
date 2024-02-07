<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/templates/view/settings"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div class="uk-height-1-1 uk-overflow uk-padding-small" style="position: relative;">
  <h3>Vilarity Settings</h3>
  <hr/>
  <h4>Program Content Labels</h4>
  <p>Vilarity programs are structured content. Sometimes that content is mentioned by name in the app. You may change the labels
    to fit your own presentation needs.</p>
  <form class="uk-form-stacked">
    <div v-for="levelData in contentLevels" class="uk-width-1-2@s">
      <label class="uk-form-label"><h5>Content Level {{ levelData.level+1 }} &ndash; {{levelData.defaultLabel}}</h5></label>
      <div class="uk-form-controls">
        <div class="uk-inline uk-width-1-1 uk-margin-small-bottom">
          <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: "></span>
          <input
            v-model.lazy="levelData.label"
            type="input"
            class="uk-input uk-border-pill" />
        </div>
      </div>
    </div>
  </form>
</div>
</ss:template>
