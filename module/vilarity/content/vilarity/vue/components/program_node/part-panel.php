<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/object/program_node/part-panel"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div v-on:change="editProgramNode.Save();">
      <p>
        <h6 class="uk-form-label">{{ GetProgramNodeTerm(editProgramNode) }} Title</h6>
        <input class="uk-input uk-text-small" v-bind:title="GetProgramNodeTerm(editProgramNode)+' Title'" v-bind:placeholder="GetProgramNodeTerm(editProgramNode)+' Title'" v-model="editProgramNode.title" />
      </p>
      <p>
        <h6 class="uk-form-label">Visibility</h6>
        <select class="uk-select uk-text-small" title="Group Visibility" placeholder="Group Visibility" v-model="editProgramNode.groupVisibility">
          <option value="0">All Groups</option>
          <option value="-1">Hidden (No Access)</option>
          <option v-for="groupObj in groups" v-bind:value="groupObj.objId">{{ groupObj.title }}</option>
        </select>
      </p>
      <p>
        <h6 class="uk-form-label">Instructions</h6>
        <div style="display: flex; flex-direction: row; flex-basis: 100%; position: relative;">
          <ckeditor style="width: 100%; height: 100%;"
            v-bind:config="ui.instructionsEditorConfig"
            v-on:input="OnSaveProgramNode(editProgramNode,'instructionsContent',inputSaveDebounceDelay);"
            v-on:change="OnSaveProgramNode(editProgramNode,'instructionsContent',/*debounceDelay=*/ 0);"
            v-model="editProgramNode.instructionsContent" />
          <div v-if="editProgramNode._.objStatus === 'saving'" style="position: absolute; right: 0; top: -1px; color: #777;">
            <i uk-icon="database"
              title="Saving changes..."
              class="uk-icon uk-icon-database" />
          </div>
          <div v-if="editProgramNode._.objStatus === 'error'" style="position: absolute; right: 2px; top: -2px; color: #777;">
            <i uk-icon="warning"
              title="Failed to save changes. Retrying..."
              class="uk-icon uk-icon-database" />
          </div>
        </div>
      </p>
</div>
</ss:template>
