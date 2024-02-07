<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/templates/view/program"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div class="vilarity-program-view">
  <!-- navTitle: <h2>Dashboard</h2> -->
  <div v-if="program._.objStatus == 'saving' || program._.objStatus == 'deleting'"
       style="position: absolute; z-index:1; top: 0; left: 0; width: 100%; height: 100%; background-color_NO: rgba(0,0,0,0.25)">
    <i style="color: black; position: absolute; left: 50%; top: 50%; margin-left: -45px; margin-top: -45px;" uk-spinner="ratio:3" />
  </div>

  <div v-if="$root.ui.mode === 'edit'" v-on:change="$root.OnSaveProgram(program);"
       class="vilarity-program-view-column-right vilarity-panel"
       v-bind:class="$root.GetVilarityPanelCssClass('program-node-panel-2')">

    <webapp-panel-resizer
      v-bind:position="$root.GetVilarityPanelResizerPosition('program-node-panel-2')"
      v-bind:panel-name="'program-node-panel-2'"
      v-bind:panel-position="$root.GetVilarityPanelPosition('program-node-panel-2')" />

    <div class="vilarity-panel-header"></div>
    <div class="vilarity-panel-content">
      <button v-if="$root.ui.mode === 'edit'"
        v-on:click="$root.ui.mode = ''"
        class="uk-button uk-button-small uk-button-primary uk-float-right">Exit Editor</button>
      <h4>{{ GetProgramTerm(programNode) }} Properties</h4>
      <p>
        <h6 class="uk-form-label">{{ GetProgramTerm(program) }} Title</h6>
        <input class="uk-input uk-text-small" v-bind:title="GetProgramTerm(programNode)+' Title'" v-bind:placeholder="GetProgramTerm(programNode)+' Title'" v-model="program.title" />
      </p>
      <p>
        <h6 class="uk-form-label">Description</h6>
        <input class="uk-input uk-text-small" title="Short Description" placeholder="Short Description" v-model="program.description" />
      </p>
      <p>
        <h6 class="uk-form-label">Visibility</h6>
        <select class="uk-select uk-text-small" title="Group Visibility" placeholder="Group Visibility" v-model="program.groupVisibility">
          <option value="0">All Groups</option>
          <option value="-1">Hidden (No Access)</option>
          <option v-for="groupObj in groups" v-bind:value="groupObj.objId">{{ groupObj.title }}</option>
        </select>
      </p>
      <p>
        <h6 class="uk-form-label">URL Link</h6>
        <input class="uk-input uk-text-small" title="URL Link" placeholder="URL Link" v-model="program.key" />
      </p>
      <p>
        <h6 class="uk-form-label">Card Color</h6>
        <select class="uk-select uk-text-small" title="Program Card Color" placeholder="Program Card Color" v-model="program.cardColor">
          <option value="white">White</option>
          <option value="blue">Blue</option>
        </select>
      </p>
    </div>
    <div class="vilarity-panel-footer">
      <button class="uk-button uk-button-small uk-button-danger uk-float-right"
        v-on:click="OnDeleteProgram(program)">Delete</button>
    </div>
  </div>
  <div class="vilarity-program-view-column-left">
<!--<h3>{{ program.title }}</h3>-->
    <p class="uk-text-small" v-if="!programNode">{{ program.description }}</p>

    <div class="vilarity-program-childnodes vilarity-programnode-cards"
      v-bind:uk-sortable="$root.ui.mode === 'edit' ? '/*handle: .uk-sortable-handle*/' : false"
      v-on="{'start'/*.uk.sortable*/ : ($event) => OnStartReorderProgramNodesFromDOM(program,$event),
             'moved'/*.uk.sortable*/ : ($event) => OnReorderProgramNodesFromDOM(program,$event)}">

    <template v-for="pnObj in $root.FilterByProgramAccess($root.viewAccount,program.childNodes)">
    <a v-if="pnObj.UUID in ui.edit === false"
        class="vilarity-programnode-card"
        v-bind:key="pnObj.UUID"
        v-bind:data-programnode-uuid="pnObj.UUID"
        v-bind:class="$root.GetCardStyleClass(program)"
        is="router-link"
        v-bind:to="'/'+program.key+'/'+pnObj.key">
      <div v-if="$root.ui.mode === 'edit'"
          class="vilarity-card-buttongroup">
        <span class="vilarity-card-button uk-button uk-button-secondary uk-button-small"
          v-on:click.prevent.stop="OnEdit(pnObj)" uk-icon="icon: pencil; ratio: 1.0" />
      </div>
      <h4>{{ pnObj.title }}</h4>
      <p>{{ pnObj.description }}</p>
    </a>
    <div v-else="/*pnObj.UUID in ui.edit === true*/"
        class="vilarity-programnode-card"
        v-bind:class="$root.GetCardStyleClass(program)">
      <div class="vilarity-card-buttongroup">
        <span class="vilarity-card-button uk-button uk-button-small"
          v-on:click.prevent.stop="OnSave(pnObj)">Save<i
            v-if="pnObj._.objStatus === 'saving'"
            uk-spinner="ratio: 0.5"/></span><span
          class="vilarity-card-button uk-button uk-button-secondary uk-button-small"
          v-on:click.prevent.stop="OnDiscardChanges(pnObj)">X</span>
      </div>
      <p><input class="uk-input uk-text-small" title="Series Title" placeholder="Series Title" v-bind:data-drag-value="ui.edit[pnObj.UUID].title" v-model="ui.edit[pnObj.UUID].title" /></p>
      <p><input class="uk-input uk-text-small" title="URL Link" placeholder="URL Link" v-bind:data-drag-value="ui.edit[pnObj.UUID].key" v-model="ui.edit[pnObj.UUID].key" /></p>
      <p><input class="uk-input uk-text-small" title="Short Description" placeholder="Short Description" v-bind:data-drag-value="ui.edit[pnObj.UUID].description" v-model="ui.edit[pnObj.UUID].description" /></p>
    </div>
    </template>
    </div>
    <div v-if="$root.ui.mode === 'edit'"
        class="">
      <button class="uk-button" v-on:click="OnNew">+</button>
    </div>
  </div>
  <div class="vilarity-program-view-content">
    <div class="uk-float-right">
      <button v-if="$root.permissions.vilarity.programs.edit &amp;&amp; $root.ui.mode !== 'edit'"
              v-on:click="$root.ui.mode = 'edit'" class="uk-button uk-button-small">Edit</button>
    </div>
    <template v-if="programNode">
      <vilarity-program-node v-bind:object="programNode" />
    </template>
  </div>
</div>
</ss:template>
