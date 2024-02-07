<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/templates/view/groups"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div class="uk-height-1-1 uk-overflow uk-padding-small" style="position: relative;">
  <h3>All Groups</h3>
  <a is="router-link" to="/groups/new" class="uk-float-right uk-button uk-button-small uk-button-secondary">+</a>
  <hr/>

  <div style="position: relative;">
    <div v-if="objectList.objects._.objStatus == 'loading'"
         style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5)">
      Loading... <i uk-spinner="ratio: 1"/>
    </div>
    <div v-else="">
      <table class="uk-table uk-table-small uk-table-striped">
      <thead>
      <tr>
        <th style="width:50px;"></th>
        <th v-if="0" style="width:100px;">View As...</th>
        <th>Group</th>
      </tr>
      </thead>
      <tbody v-if="objectList.objects._.objCount === 0">
      <tr>
        <td colspan="3" class="uk-text-small uk-text-center">This list is empty.</td>
      </tr>
      </tbody>
      <tbody v-else="">
      <template v-for="(obj,key,idx) in objectList.objects">
      <tr v-if="obj._.objStatus != 'loading'">
        <td>{{key+1}}.</td>
        <td v-if="0">
          <a v-if="effectiveGroup.objId !== obj.objId" v-on:click="$root.SetEffectiveGroup(obj);"><span uk-icon="icon: sign-in"/></a>
          <span v-else-if="effectiveGroup.objId === obj.objId"><span uk-icon="icon: check"/></span>
        </td>
        <td><a is="router-link" v-bind:to="'/groups/'+obj.objId+':edit'">
          <template v-if="obj.title != ''">{{ obj.title }}</template>
          <template v-else="">Group {{ obj.objId }}</template>
        </a></td>
      </tr>
      </template>

      </tbody>
      </table>
    </div>
  </div>
</div>
</ss:template>
