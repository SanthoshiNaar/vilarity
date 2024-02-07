<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/object/program_node/part"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div class="vilarity-program_node-content vilarity-program_node-part-content"
    v-on:click.stop="($root.ui.mode !== 'edit' ? $emit('focus-programnode',programNode) : false)">

  <div class="uk-float-right" v-if="$root.ui.mode !== 'edit'">
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

  <h4 style="display:flex; flex-wrap: wrap; gap: 0.5em; align-items: center" v-on:change="programNode.Save();">
    <span style="white-space:nowrap;">{{ GetProgramNodeTerm(programNode) }} {{ programNode.number }}:</span>
    <input v-if="$root.ui.mode === 'edit'"
      style="flex: 1; position: relative; top: -1px; height: auto; padding: 0 2px;"
      class="uk-input"
      v-bind:data-drag-value="programNode.title"
      v-model="programNode.title"
      v-on:focus="$emit('focus-programnode',programNode)"
      v-on:blur="$emit('focus-programnode',null)"/>
    <span v-else="" style="flex: 1;">{{ programNode.title }}</span>

    <span v-if="$root.ui.mode === 'edit'"
      class="uk-sortable-handle uk-margin-small-left uk-text-center"
      uk-icon="icon: table"
      v-bind:title="'Reorder '+GetProgramNodeTerm(programNode) + ' ' + programNode.number"></span>
  </h4>

  <p v-if="programNode.description" v-html="programNode.description"></p>

  <div class="uk-grid uk-grid-small vilarity-programnode-part-childnodes" style="justify-content: inherit;"
      v-bind:uk-sortable="$root.ui.mode === 'edit' ? 'handle: .uk-sortable-handle' : false"
      v-on="{'start'/*.uk.sortable*/ : ($event) => OnStartReorderProgramNodesFromDOM(programNode,$event),
             'moved'/*.uk.sortable*/ : ($event) => OnReorderProgramNodesFromDOM(programNode,$event)}">

    <template v-for="node in $root.FilterByProgramAccess($root.viewAccount,programNode.childNodes)">
      <template v-if="node.type === 'point'">
        <vilarity-program-node
          v-bind:key="node.UUID"
          v-bind:data-programnode-uuid="node.UUID"
          v-bind:object="node"
          v-on:focus-programnode="$emit('focus-programnode',$event)"/>
      </template>
    </template>
    <button v-if="$root.ui.mode === 'edit' &amp;&amp; programNode.childNodes.length &lt; $root.settings.maxNodes['point']"
            v-bind:title="'Add a '+GetProgramNodeTerm('point')"
            v-on:click.prevent.stop="OnNew(programNode,'point')"
            style="margin: 0 auto;"
            class="vilarity-button-add uk-button uk-button-small">+</button>

  </div>

</div>
</ss:template>
