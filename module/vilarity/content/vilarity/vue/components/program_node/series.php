<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/object/program_node/series"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div class="vilarity-program_node-content"
    v-on:click.stop="($root.ui.mode !== 'edit' ? $emit('focus-programnode',programNode) : false)">
  <div class="uk-float-right">
    <button v-if="$root.permissions.vilarity.programs.edit &amp;&amp; $root.ui.mode !== 'edit'"
            v-on:click="$root.ui.mode = 'edit'" class="uk-button uk-button-small">Edit</button>
  </div>

  <div class="uk-float-right" v-if="$root.ui.mode !== 'edit' &amp;&amp; programNode.type != 'series'">
    <a v-if="programNode.instructionsContent"
      class="uk-button uk-button-small"
      style="padding: 3px; line-height:14px;"
      title="Instructions"><i uk-icon="info" /></a>
    <a class="uk-button uk-button-small"
      style="padding: 3px; line-height:14px;"
      title="Notes">
      <i v-if="GetProgramNodeRecord(programNode).notes == false" uk-icon="comment" />
      <i v-else="" uk-icon="commenting" />
    </a>
  </div>

  <h3 style="display:flex; flex-wrap: wrap; gap: 0.5em;" v-on:change="programNode.Save();">
    <span style="white-space:nowrap;">{{ GetProgramNodeTerm(programNode) }} {{ programNode.number }}:</span>
    <template v-if="$root.ui.mode === 'edit'">
    <input
      style="position: relative; top: -1px; height: auto; padding: 0 2px;"
      class="uk-input"
      v-bind:data-drag-value="programNode.title"
      v-model="programNode.title"
      v-on:focus="$emit('focus-programnode',programNode)"
      v-on:blur="$emit('focus-programnode',null)"/>
    </template>
    <span v-else="">{{ programNode.title }}</span>
  </h3>

  <p style="display:flex; gap: 0.5em;" v-on:change="programNode.Save();">
    <template v-if="$root.ui.mode === 'edit'">
    <input
      style="position: relative; top: -1px; height: auto; padding: 0 2px;"
      class="uk-input"
      v-bind:data-drag-value="programNode.description"
      v-model="programNode.description"
      v-on:focus="$emit('focus-programnode',programNode)"
      v-on:blur="$emit('focus-programnode',null)"/>
    </template>
    <span v-else="">{{ programNode.description }}</span>
  </p>

  <h4>{{ GetProgramNodeTerm(programNode) }} {{ programNode.number }} Overview</h4>

  <div class="vilarity-programnode-series-childnodes vilarity-programnode-cards"
      v-bind:uk-sortable="$root.ui.mode === 'edit' ? '/*handle: .uk-sortable-handle*/' : false"
      v-on="{'start'/*.uk.sortable*/ : ($event) => OnStartReorderProgramNodesFromDOM(programNode,$event),
             'moved'/*.uk.sortable*/ : ($event) => OnReorderProgramNodesFromDOM(programNode,$event)}">

  <template v-for="pnObj in $root.FilterByProgramAccess($root.viewAccount,programNode.childNodes)">
  <div v-bind:key="pnObj.UUID"
       v-bind:data-programnode-uuid="pnObj.UUID">
    <div style="display:flex; flex-wrap: wrap; gap: 0.5em; align-items: center; justify-content: space-between;">
      <button v-if="$root.ui.mode === 'edit' &amp;&amp; programNode.childNodes.length &lt; $root.settings.maxNodes['session']"
              v-bind:title="'Add a '+GetProgramNodeTerm(pnObj)"
              v-on:click.prevent.stop="OnNew(programNode,'session',pnObj)"
              class="vilarity-button-add uk-button uk-button-small">+</button>
      <span v-if="$root.ui.mode === 'edit'"
        class="uk-sortable-handle uk-margin-small-left uk-text-center"
        uk-icon="icon: table"
        v-bind:title="'Reorder '+GetProgramNodeTerm(programNode) + ' ' + programNode.number"></span>
    </div>
    <a v-if="pnObj.UUID in ui.edit === false"
        class="vilarity-program-series-row"
        is="router-link"
        v-bind:to="'/'+programNode.program.key+'/'+pnObj.key">
      <div class="vilarity-programnode-card uk-width-1-3@s" v-bind:class="$root.GetCardStyleClass(programNode.program)">
        <h4>{{ pnObj.title }}</h4>
        <p>{{ pnObj.description }}</p>
      </div>

      <div style="display: flex; flex-direction: row" class="uk-width-2-3">
        <template v-for="childPnObj in $root.FilterByProgramAccess($root.viewAccount,pnObj.childNodes)">
        <div v-if="childPnObj.type === 'part'" class="vilarity-programnode-card" style="flex-grow: 1; flex-basis: 0;">
          {{ childPnObj.title }}
        </div>
        </template>
      </div>
    </a>
  </div>
  </template>

  </div>

  <button v-if="$root.ui.mode === 'edit' &amp;&amp; programNode.childNodes.length &lt; $root.settings.maxNodes['session']"
          v-bind:title="'Add a '+GetProgramNodeTerm('session')"
          v-on:click.prevent.stop="OnNew(programNode,'session')"
          class="vilarity-button-add uk-button uk-button-small">+</button>
</div>
</ss:template>
