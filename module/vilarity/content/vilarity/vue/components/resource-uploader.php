<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/vue/components/resource-uploader"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div>
  <div>
    <p><button v-if="settings.bAllowDelete &amp;&amp; resource != undefined"
      v-on:click="OnReplace"
      class="uk-button uk-button-danger uk-button-small">Replace</button></p>
    <div v-if="type === 'image'" style="flex-basis: 100%;">
      <img v-if="resource &amp;&amp; resource.lvmEntry" v-bind:src="'data/'+resource.lvmEntry.lvmPath"
        v-bind:title="resource.name"
        style="max-height: 25vh;" />
    </div>
    <div v-else-if="type === 'video'" style="flex-basis: 100%;">
      <video v-if="resource" style="width:100%;" controls="controls" v-bind:title="resource.name">
        <source v-bind:src="'file/'+resource.lvmPath"
                v-bind:type="resource.type" />
      </video>
    </div>
    <div v-else-if="type === 'audio'" style="flex-basis: 100%;">
      <audio v-if="resource" style="width:100%;" controls="controls" v-bind:title="resource.name">
        <source v-bind:src="'file/'+resource.lvmPath"
                v-bind:type="resource.type" />
      </audio>
    </div>
    <div v-else-if="type === 'file'" style="flex-basis: 100%;">
      <a v-if="resource" target="_blank"
         v-bind:href="'file/'+resource.lvmPath"
         v-bind:title="resource.name">{{ resource.name }}</a>
    </div>
    <div v-else="" v-bind:title="'Unknown type: '+type" style="flex-basis: 100%;">
      ?
    </div>
  </div>
  <div v-if="resource == undefined">
    <div class="js-upload uk-placeholder uk-text-center">
      <span uk-icon="icon: cloud-upload"></span>
      <span class="uk-text-middle">Drop or</span>
      <div uk-form-custom="uk-form-custom">
        <input type="file" multiple=""/>
        <a class="uk-link">select a file</a> to upload
      </div>
    </div>
    <progress class="uk-progress js-progressbar" value="0" max="100" hidden="hidden"></progress>
  </div>
</div>
</ss:template>
