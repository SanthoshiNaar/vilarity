<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/templates/view/program_node"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div class="vilarity-program-view">
  <!-- navTitle: <h2>Dashboard</h2> -->
  <div v-if="editProgramNode._.objStatus == 'saving' || editProgramNode._.objStatus == 'deleting'"
       style="position: absolute; z-index:1; top: 0; left: 0; width: 100%; height: 100%; background-color_NO: rgba(0,0,0,0.25)">
    <i style="color: black; position: absolute; left: 50%; top: 50%; margin-left: -45px; margin-top: -45px;" uk-spinner="ratio:3" />
  </div>

  <div class="vilarity-program-view-column-right vilarity-panel"
       v-bind:class="$root.GetVilarityPanelCssClass('program-node-panel-2')"
       v-if="programNode.type != 'series' || $root.ui.mode === 'edit'">

    <webapp-panel-resizer
      v-bind:position="$root.GetVilarityPanelResizerPosition('program-node-panel-2')"
      v-bind:panel-name="'program-node-panel-2'"
      v-bind:panel-position="$root.GetVilarityPanelPosition('program-node-panel-2')" />

    <div class="vilarity-panel-header"></div>
    <div class="vilarity-panel-content">
      <div v-if="$root.ui.mode === 'edit'">
        <h4>{{ GetProgramNodeTerm(editProgramNode) }} Properties</h4>

        <vilarity-program-node-panel v-bind:object="editProgramNode" />
      </div>
      <div v-else=""
          v-on:input="OnSaveRecord(focusProgramRecord,'notes',inputSaveDebounceDelay);"
          v-on:change="OnSaveRecord(focusProgramRecord,'notes',/*debounceDelay=*/ 0);"
          class="vilarity-panel-program_node">
        <h4 v-if="editProgramNode.instructionsContent">Instructions</h4>
        <div class="vilarity-instructions" v-if="editProgramNode.instructionsContent">
          <span v-html="editProgramNode.instructionsContent" />
        </div>
        <h4>Notes</h4>
        <div style="display: flex; flex-direction: row; flex-basis: 100%; position: relative;">
          <textarea class="uk-textarea vilarity-notes" v-model="focusProgramRecord.notes"></textarea>
          <div v-if="focusProgramRecord._.objStatus === 'saving'" style="position: absolute; right: 0; top: -1px; color: #777;">
            <i uk-icon="database"
              title="Saving changes..."
              class="uk-icon uk-icon-database" />
          </div>
          <div v-if="focusProgramRecord._.objStatus === 'error'" style="position: absolute; right: 2px; top: -2px; color: #777;">
            <i uk-icon="warning"
              title="Failed to save changes. Retrying..."
              class="uk-icon uk-icon-database" />
          </div>
        </div>
      </div>
    </div>
    <div class="vilarity-panel-footer" v-if="$root.permissions.vilarity.programs.edit">
      <hr/>
      <div v-if="$root.ui.mode === 'edit'">
        <button
          v-on:click="$root.ui.mode = ''"
          class="uk-button uk-button-small uk-button-primary">Exit Editor</button>
        <button v-if="$root.permissions.vilarity.programs.edit"
          v-on:click="OnDeleteProgramNode(editProgramNode)"
          class="uk-button uk-button-small uk-button-danger uk-float-right">Delete</button>
      </div>
      <div v-else="">
        <button v-if="$root.permissions.vilarity.programs.edit"
          v-on:click="$root.ui.mode = 'edit'"
          class="uk-button uk-button-small uk-button-primary">Edit {{ GetProgramNodeTerm(editProgramNode) }}</button>
      </div>
    </div>
  </div>
  <div class="vilarity-program-view-content">
    <vilarity-program-node v-bind:object="programNode"
      v-on:focus-programnode="OnFocusProgramNode"/>
  </div>
</div>
</ss:template>
