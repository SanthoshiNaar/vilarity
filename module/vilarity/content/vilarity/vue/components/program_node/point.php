<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/object/program_node/point"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div class="vilarity-program_node-content vilarity-program_node-point-content"
    v-bind:data-programnode-uuid="programNode.UUID"
    v-bind:class="[GetLayoutWidthClass()]"
    style="display:flex; flex-direction: column;">
<div v-if="$root.ui.mode === 'edit'" v-on:change="programNode.Save();"
    style="display:flex; flex: 1 1 auto; "
    v-on:click="$emit('focus-programnode',programNode)"
    v-on:blur="$emit('focus-programnode',null)">
  <span>
    <span v-if="$root.ui.mode === 'edit'"
      class="uk-sortable-handle uk-margin-small-top uk-margin-small-right uk-text-center"
      uk-icon="icon: table"
      v-bind:title="'Reorder '+GetProgramNodeTerm(programNode) + ' ' + programNode.number"></span><br/>
    <button v-if="$root.ui.mode === 'edit' &amp;&amp; programNode.childNodes.length &lt; $root.settings.maxNodes['point']"
            v-bind:title="'Add a '+GetProgramNodeTerm(programNode)"
            v-on:click.prevent.stop="OnNew(programNode.parent,'point',programNode)"
            class="vilarity-button-add uk-button uk-button-small">+</button>
  </span>
  <div style="display:flex; flex: 1 1 auto; flex-direction: column;">
  <h5 v-if="(programNode.subType === 'input' || programNode.subType === 'text')">
    <input
      style="flex-basis: 100%;"
      class="uk-input"
      placeholder="Title"
      v-bind:data-drag-value="object.title"
      v-model="object.title"
      v-on:focus="$emit('focus-programnode',programNode)"
      v-on:blur="$emit('focus-programnode',null)"/>
  </h5>
  <input v-if="programNode.subType === 'input'"
    style="flex-basis: 100%;"
    class="uk-input"
    placeholder="Input Placeholder"
	v-bind:data-drag-value="object.description"
    v-model="object.description"
    v-on:focus="$emit('focus-programnode',programNode)"
    v-on:blur="$emit('focus-programnode',null)"/>
  <div v-else-if="programNode.subType === 'text'"
       style="display: flex; flex-direction: row;">
    <textarea
      style="flex-basis: 100%;"
      class="uk-textarea"
      placeholder="Text Content"
      v-model="object.description"
      v-on:input="DoTextareaHeightResize()"
      v-bind:rows="GetTextAreaRowValue(object.description,100)"
      v-on:focus="$emit('focus-programnode',programNode)"
      v-on:blur="$emit('focus-programnode',null)"/>
  </div>
  <div v-else-if="programNode.subType === 'image'"
      v-on:click="$emit('focus-programnode',programNode)"
      v-on:focus="$emit('focus-programnode',programNode)"
      style="flex-basis: 100%; position: relative;">
    <span class="vilarity-program_node-point-badge">Image</span>
    <img v-if="programNode_photoObj"
      v-bind:src="'data/'+programNode_photoObj.lvmEntry.lvmPath"
      v-bind:title="programNode_photoObj.name"/>
    <img v-else-if="programNode.GetResourceURL() != null"
      v-bind:src="programNode.GetResourceURL()"
      v-bind:title="programNode.title" />
    <div v-else="" style="position: relative;">
      <vilarity-resource-uploader
        v-bind:type="programNode.subType"
        v-bind:resource="programNode.GetResource()"
        v-on:complete="OnResourceUploadComplete"
        v-on:deleted="OnResourceDeleted"/>
    </div>
  </div>
  <div v-else-if="programNode.subType === 'video'"
      v-on:click="$emit('focus-programnode',programNode)"
      v-on:focus="$emit('focus-programnode',programNode)"
      style="flex-basis: 100%; position: relative;">
    <span class="vilarity-program_node-point-badge">Video</span>
    <video v-if="programNode_volumeEntryObj"
        style="width:100%;" controls="controls"
        v-on:click="$emit('focus-programnode',programNode)"
        v-on:focus="$emit('focus-programnode',programNode)">
      <source
              v-bind:src="'file/'+programNode_volumeEntryObj.lvmPath"
              v-bind:type="programNode_volumeEntryObj.type" />
    </video>
    <div v-else-if="programNode.GetYoutubeEmbedURL()" class="vilarity-program_node-point-youtube">
      <iframe v-bind:src="programNode.GetYoutubeEmbedURL()"
        title="YouTube video player" frameborder="0"
        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen=""></iframe>
      <div class="vilarity-program_node-point-notouch"
        v-on:click="$emit('focus-programnode',programNode)"
        v-on:focus="$emit('focus-programnode',programNode)" />
    </div>
    <div v-else-if="programNode.GetVimeoEmbedURL()" class="vilarity-program_node-point-vimeo">
      <iframe v-bind:src="programNode.GetVimeoEmbedURL()"
        width="100%" height="" frameborder="0" allow="fullscreen; picture-in-picture" allowfullscreen=""></iframe>
      <div class="vilarity-program_node-point-notouch"
        v-on:click="$emit('focus-programnode',programNode)"
        v-on:focus="$emit('focus-programnode',programNode)" />
    </div>
    <div v-else="" style="position: relative;">
      <vilarity-resource-uploader
        v-bind:type="programNode.subType"
        v-bind:resource="programNode.GetResource()"
        v-on:complete="OnResourceUploadComplete"
        v-on:deleted="OnResourceDeleted"/>
    </div>
  </div>
  <div v-else-if="programNode.subType === 'audio'"
      v-on:click="$emit('focus-programnode',programNode)"
      v-on:focus="$emit('focus-programnode',programNode)"
      style="flex-basis: 100%; position: relative;">
    <span class="vilarity-program_node-point-badge">Audio</span>
    <div v-if="programNode_embedURL"
         style="position: relative;">
      <iframe
        v-bind:src="programNode_embedURL"
        class="vilarity-program_node-point-audio"
        v-on:click="$emit('focus-programnode',programNode)"
        v-on:focus="$emit('focus-programnode',programNode)">
      </iframe>
      <div class="vilarity-program_node-point-notouch"
        v-on:click="$emit('focus-programnode',programNode)"
        v-on:focus="$emit('focus-programnode',programNode)" />
    </div>
    <audio v-else-if="programNode_volumeEntryObj"
        class="vilarity-program_node-point-audio" controls="controls"
        v-on:click="$emit('focus-programnode',programNode)"
        v-on:focus="$emit('focus-programnode',programNode)">
      <source v-bind:src="'file/'+programNode_volumeEntryObj.lvmPath"
              v-bind:type="programNode_volumeEntryObj.type" />
    </audio>
    <audio v-else-if="programNode.GetResourceURL() != null"
        class="vilarity-program_node-point-audio" controls="controls"
        v-on:click="$emit('focus-programnode',programNode)"
        v-on:focus="$emit('focus-programnode',programNode)">
      <source v-bind:src="programNode.GetResourceURL()"
              v-bind:type="''" />
    </audio>
    <div v-else="" style="position: relative;">
      <vilarity-resource-uploader
        v-bind:type="programNode.subType"
        v-bind:resource="programNode.GetResource()"
        v-on:complete="OnResourceUploadComplete"
        v-on:deleted="OnResourceDeleted"/>
    </div>
  </div>
  <div v-else-if="programNode.subType === 'file'"
      v-on:click="$emit('focus-programnode',programNode)"
      v-on:focus="$emit('focus-programnode',programNode)"
      style="flex-basis: 100%; min-height: 1em; position: relative;">
    <span class="vilarity-program_node-point-badge">Download</span>
    <div v-if="programNode_volumeEntryObj || programNode.GetResourceURL() != null" style="position: relative;">
      <a v-if="programNode_volumeEntryObj" target="_blank"
        v-bind:href="'file/'+programNode_volumeEntryObj.lvmPath"
        v-bind:title="programNode.title">{{ programNode.title }}</a>
      <a v-else-if="programNode.GetResourceURL() != null" target="_blank"
        v-bind:href="programNode.GetResourceURL()"
        v-bind:title="programNode.title">{{ programNode.title }}</a>
      <div class="vilarity-program_node-point-notouch"
        v-on:click="$emit('focus-programnode',programNode)"
        v-on:focus="$emit('focus-programnode',programNode)" />
    </div>
    <div v-else="" style="position: relative;">
      <vilarity-resource-uploader
        v-bind:type="programNode.subType"
        v-bind:resource="programNode.GetResource()"
        v-on:complete="OnResourceUploadComplete"
        v-on:deleted="OnResourceDeleted"/>
    </div>
  </div>
  <div v-else=""
      v-on:click="$emit('focus-programnode',programNode)"
      v-on:focus="$emit('focus-programnode',programNode)"
      v-bind:title="'Unknown type: '+programNode.subType"
      style="flex-basis: 100%; position: relative;">
    <span class="vilarity-program_node-point-badge">Unknown Type</span>
    ?
  </div>
  </div>
</div>
<template v-else="">
  <h5 v-if="(programNode.subType === 'input' || programNode.subType === 'text') &amp;&amp; object.title != 'Untitled'">
    {{ object.title }}
  </h5>
  <div v-if="programNode.subType === 'input'"
       style="display: flex; flex-direction: row; position: relative;">
    <textarea
      style="flex-basis: 100%;"
      class="uk-textarea"
      v-bind:placeholder="object.description"
      v-bind:rows="GetTextAreaRowValue(programRecord.value,5)"
      v-model="programRecord.value"
      v-on:input="OnSaveRecord(programRecord,'value',inputSaveDebounceDelay); DoTextareaHeightResize();"
      v-on:change="OnSaveRecord(programRecord,'value',/*debounceDelay=*/ 0);" />
    <div v-if="programRecord._.objStatus === 'saving'" style="position: absolute; right: 0; top: 1px; color: #777; z-index: 10;">
      <i uk-icon="database"
        title="Saving changes..."
        class="uk-icon uk-icon-database" />
    </div>
    <div v-if="programRecord._.objStatus === 'error'" style="position: absolute; right: 2px; top: -2px; color: #777; z-index: 10;">
      <i uk-icon="warning"
        title="Failed to save changes. Retrying..."
        class="uk-icon uk-icon-database" />
    </div>
  </div>
  <div v-else-if="programNode.subType === 'text'"
    style="flex-basis: 100%; white-space: pre-wrap;">{{ programNode.description }}</div>
  <div v-else-if="programNode.subType === 'image'" style="flex-basis: 100%;">
    <img v-if="programNode_photoObj"
      v-bind:src="'data/'+programNode_photoObj.lvmEntry.lvmPath"
      v-bind:title="programNode_photoObj.name" />
    <img v-else-if="programNode.GetResourceURL() != null"
      v-bind:src="programNode.GetResourceURL()"
      v-bind:title="programNode.title" />
  </div>
  <div v-else-if="programNode.subType === 'video'" style="flex-basis: 100%;">
    <video v-if="programNode_volumeEntryObj" style="width:100%;" controls="controls" v-bind:title="programNode_volumeEntryObj.title">
      <source v-bind:src="'file/'+programNode_volumeEntryObj.lvmPath"
              v-bind:type="programNode_volumeEntryObj.type" />
    </video>
    <div v-else-if="programNode.GetYoutubeEmbedURL()" class="vilarity-program_node-point-youtube">
      <iframe v-bind:src="programNode.GetYoutubeEmbedURL()"
        title="YouTube video player" frameborder="0"
        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen=""></iframe>
    </div>
    <div v-else-if="programNode.GetVimeoEmbedURL()" class="vilarity-program_node-point-vimeo">
      <iframe v-bind:src="programNode.GetVimeoEmbedURL()"
        width="100%" height="" frameborder="0" allow="fullscreen; picture-in-picture" allowfullscreen=""></iframe>
    </div>
  </div>
  <div v-else-if="programNode.subType === 'audio'" style="flex-basis: 100%;">
    <iframe v-if="programNode_embedURL" v-bind:src="programNode_embedURL" class="vilarity-program_node-point-audio" scrolling="yes">
    </iframe>
    <audio v-else-if="programNode_volumeEntryObj" class="vilarity-program_node-point-audio" controls="controls" v-bind:title="programNode.title">
      <source v-bind:src="'file/'+programNode_volumeEntryObj.lvmPath"
              v-bind:type="programNode_volumeEntryObj.type" />
    </audio>
    <audio v-else-if="programNode.GetResourceURL() != null" class="vilarity-program_node-point-audio" controls="controls" v-bind:title="programNode.title">
      <source v-bind:src="programNode.GetResourceURL()"
              v-bind:type="''" />
    </audio>
  </div>
  <div v-else-if="programNode.subType === 'file'" style="flex-basis: 100%;">
    <a v-if="programNode_volumeEntryObj" target="_blank"
       v-bind:href="'file/'+programNode_volumeEntryObj.lvmPath"
       v-bind:title="programNode.title">{{ programNode.title }}</a>
    <a v-else-if="programNode.GetResourceURL() != null" target="_blank"
       v-bind:href="programNode.GetResourceURL()"
       v-bind:title="programNode.title">{{ programNode.title }}</a>
  </div>
  <div v-else="" v-bind:title="'Unknown type: '+programNode.subType" style="flex-basis: 100%;">
    ?
  </div>
</template>
</div>
</ss:template>
