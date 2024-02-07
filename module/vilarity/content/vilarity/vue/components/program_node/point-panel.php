<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/object/program_node/point-panel"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div v-on:change="editProgramNode.Save();">
      <div class="uk-margin-top" style="clear:both">
        <h6 class="uk-form-label">Point Type</h6>
        <div style="display: flex; flex-wrap: wrap;">
         <button v-for="(label,value) in {input: 'Input', text: 'Text', image: 'Image', video: 'Video', audio: 'Audio', file: 'Download'}"
           style="flex-basis: 33.3%"
           class="uk-width-auto uk-button uk-button-small uk-button-secondary"
           v-bind:class="(editProgramNode.subType === value ? 'uk-button-primary' : '')"
           v-on:click="editProgramNode.subType = value; editProgramNode.Save();">{{label}}</button>
        </div>
        <hr/>
      </div>
      <div>
        <h6 class="uk-form-label">Visibility</h6>
        <select class="uk-select uk-text-small" title="Group Visibility" placeholder="Group Visibility" v-model="editProgramNode.groupVisibility">
          <option value="0">All Groups</option>
          <option value="-1">Hidden (No Access)</option>
          <option v-for="groupObj in groups" v-bind:value="groupObj.objId">{{ groupObj.title }}</option>
        </select>
      </div>
      <div v-if="editProgramNode.subType === 'input'" class="uk-margin-top">
        <h6 class="uk-form-label">Input Title</h6>
        <input class="uk-input uk-text-small" title="Input Title" placeholder="" v-model="editProgramNode.title" />
        <h6 class="uk-form-label">Placeholder Text</h6>
        <input class="uk-input uk-text-small" title="Placeholder Text" placeholder="" v-model="editProgramNode.description" />
      </div>
      <div v-if="editProgramNode.subType === 'text'" class="uk-margin-top">
        <h6 class="uk-form-label">Text Title</h6>
        <input class="uk-input uk-text-small" title="Text Title" placeholder="" v-model="editProgramNode.title" />
        <h6 class="uk-form-label">Text Content</h6>
        <textarea class="uk-textarea uk-text-small" title="Text Content" placeholder="Add some text..."
          rows="5"
          v-model="editProgramNode.description" />
      </div>

      <div v-if="(['image','video','audio','file'].indexOf(editProgramNode.subType) !== -1)" class="uk-margin-top">
        <div v-if="editProgramNode.subType == 'image'" class="uk-margin-top">
          <h6 class="uk-form-label">Image Title</h6>
          <input class="uk-input uk-text-small" title="Image Title" placeholder=""
            v-model="editProgramNode.title" />
        </div>
        <div v-if="editProgramNode.subType == 'video'" class="uk-margin-top">
          <h6 class="uk-form-label">Video Title</h6>
          <input class="uk-input uk-text-small" title="Video Title" placeholder=""
            v-model="editProgramNode.title" />
        </div>
        <div v-if="editProgramNode.subType == 'audio'" class="uk-margin-top">
          <h6 class="uk-form-label">Audio Title</h6>
          <input class="uk-input uk-text-small" title="Audio Title" placeholder=""
            v-model="editProgramNode.title" />
        </div>
        <div v-if="editProgramNode.subType == 'file'" class="uk-margin-top">
          <h6 class="uk-form-label">Link Text</h6>
          <input class="uk-input uk-text-small" title="Link Text" placeholder=""
            v-model="editProgramNode.title" />
        </div>
      </div>

      <div v-if="(['image','video','audio','file'].indexOf(editProgramNode.subType) !== -1)" class="uk-margin-top">
        <div v-if="editProgramNode.subType == 'image' &amp;&amp; editProgramNode.GetResource() == null" class="uk-margin-top">
          <h6 class="uk-form-label">External Image (Link)</h6>
          <input class="uk-input uk-text-small" title="External Image (Link)" placeholder="Link to an image"
            v-bind:value="editProgramNode.GetResourceURL()"
            v-on:change="editProgramNode.SetResourceURL($event.target.value)" />
        </div>
        <div v-if="editProgramNode.subType == 'video' &amp;&amp; editProgramNode.GetResource() == null" class="uk-margin-top">
          <h6 class="uk-form-label">External Video (Link)</h6>
          <input class="uk-input uk-text-small" title="External Video (Link)" placeholder="YouTube or Vimeo link"
            v-bind:value="editProgramNode.GetResourceURL()"
            v-on:change="editProgramNode.SetResourceURL($event.target.value)" />
        </div>
        <div v-if="editProgramNode.subType == 'audio' &amp;&amp; editProgramNode.GetResource() == null" class="uk-margin-top">
          <h6 class="uk-form-label">External Audio (Link)</h6>
          <input class="uk-input uk-text-small" title="External Audio (Link)" placeholder="MP3, MPA, or AAC link"
            v-bind:value="editProgramNode.GetResourceURL()"
            v-on:change="editProgramNode.SetResourceURL($event.target.value)" />
        </div>
        <div v-if="editProgramNode.subType == 'file' &amp;&amp; editProgramNode.GetResource() == null" class="uk-margin-top">
          <h6 class="uk-form-label">External Download (Link)</h6>
          <input class="uk-input uk-text-small" title="External Download (Link)" placeholder="Link to a file"
            v-bind:value="editProgramNode.GetResourceURL()"
            v-on:change="editProgramNode.SetResourceURL($event.target.value)" />
        </div>
        <div v-if="" class="uk-margin-top">
          <h6 v-if="editProgramNode.subType == 'image'" class="uk-form-label">Image Upload</h6>
          <h6 v-else-if="editProgramNode.subType == 'video'" class="uk-form-label">Video Upload</h6>
          <h6 v-else-if="editProgramNode.subType == 'audio'" class="uk-form-label">Audio Upload</h6>
          <h6 v-else-if="editProgramNode.subType == 'file'" class="uk-form-label">File Upload</h6>
          <h6 v-else="" class="uk-form-label">Resource Upload</h6>
          <vilarity-resource-uploader
            v-if=""
            v-bind:type="editProgramNode.subType"
            v-bind:resource="editProgramNode.GetResource()"
            v-on:complete="OnResourceUploadComplete"
            v-on:deleted="OnResourceDeleted"/>
        </div>
      </div>

      <div class="uk-margin-top">
        <h6 class="uk-form-label">Layout Width</h6>
        <select class="uk-select uk-text-small" v-model="editProgramNode.layoutWidth">
<!--<option value="1-6">16.7%</option>-->
<!--<option value="5-6">83.3%</option>-->
<optgroup label="Full Width">
  <option value="1-1">100%</option>
</optgroup>
<optgroup label="Fifths">
  <option value="1-5">20%</option>
  <option value="2-5">40%</option>
  <option value="3-5">60%</option>
  <option value="4-5">80%</option>
</optgroup>
<optgroup label="Quarters">
  <option value="1-4">25%</option>
  <option value="1-2">50%</option>
  <option value="3-4">75%</option>
</optgroup>
<optgroup label="Thirds">
  <option value="1-3">33.3%</option>
  <option value="2-3">66.7%</option>
</optgroup>
         </select>
      </div>

</div>
</ss:template>
