<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/object/program_node/session"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div class="vilarity-program_node-content vilarity-program_node-session-content"
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

  <div class="vilarity-programnode-session-childnodes"
      v-bind:uk-sortable="$root.ui.mode === 'edit' ? 'handle: .uk-sortable-handle' : false"
      v-on="{'start'/*.uk.sortable*/ : ($event) => OnStartReorderProgramNodesFromDOM(programNode,$event),
             'moved'/*.uk.sortable*/ : ($event) => OnReorderProgramNodesFromDOM(programNode,$event)}">

  <template v-for="node in $root.FilterByProgramAccess($root.viewAccount,programNode.childNodes)">
  <div v-bind:key="node.UUID"
       v-bind:data-programnode-uuid="node.UUID">
    <template v-if="node.type === 'part'">
      <template v-if="node._.objStatus == 'saving' &amp;&amp; node.objId == 0">
        <span uk-spinner="" />
      </template>
      <template v-else="">
        <button v-if="$root.ui.mode === 'edit' &amp;&amp; programNode.childNodes.length &lt; $root.settings.maxNodes['part']"
          v-bind:title="'Add a '+GetProgramNodeTerm(node)"
          v-on:click.prevent.stop="OnNew(programNode,'part',node)"
          class="vilarity-button-add uk-button uk-button-small">+</button>
        <vilarity-program-node
          v-bind:object="node"
          v-on:focus-programnode="$emit('focus-programnode',$event)"/>
      </template>
    </template>
  </div>
  </template>

  </div>

  <button v-if="$root.ui.mode === 'edit' &amp;&amp; programNode.childNodes.length &lt; $root.settings.maxNodes['part']"
          v-bind:title="'Add a '+GetProgramNodeTerm('part')"
          v-on:click.prevent.stop="OnNew(programNode,'part')"
          class="vilarity-button-add uk-button uk-button-small">+</button>
</div>
</ss:template>
