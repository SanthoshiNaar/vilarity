<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/templates/view/accounts"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div class="uk-height-1-1 uk-overflow uk-padding-small" style="position: relative;">
  <h3>All Accounts</h3>
  <a is="router-link" to="/accounts/new" class="uk-float-right uk-button uk-button-small uk-button-secondary">+</a>
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
        <th style="width:100px;">View As...</th>
        <th>Status</th>
        <th>Username</th>
        <th>Group</th>
        <th>Name</th>
        <th>Email</th>
        <th>Company</th>
      </tr>
      </thead>
      <tbody>
      <template v-for="(obj,key,idx) in objectList.objects">
      <tr v-if="obj.user._.objStatus != 'loading'">
        <td>{{key+1}}.</td>
        <td>
          <a v-if="effectiveAccount.objId != obj.objId" v-on:click="$root.SetEffectiveAccount(obj);"><span uk-icon="icon: sign-in"/></a>
          <span v-else-if="effectiveAccount.objId == obj.objId"><span uk-icon="icon: check"/></span>
        </td>
        <td title="Status" v-bind:class="{ 'user-status-inactive' : (obj &amp;&amp; obj.user &amp;&amp; obj.user.status == 0) }">
          <span v-if="obj &amp;&amp; obj.user &amp;&amp; obj.user.status == 0" class="user-status-inactive">Inactive</span>
          <span v-else="">Active</span>
        </td>
        <td><a is="router-link" v-bind:to="'/accounts/'+obj.objId">{{ obj.user.username }}</a></td>
        <td>
        <div>{{ (obj.group.objId == 0 &amp;&amp; obj.groups.length == 0) ? '&mdash;' : obj.group.title }}</div>
        <div v-for="(groupObj,key,idx) in obj.groups">
          <template v-if="groupObj.objId == obj.group.objId"></template>
          <template v-else-if="groupObj.title != ''">{{ groupObj.title }}</template>
          <template v-else="">Group {{ groupObj.objId }}</template>
        </div>
        </td>
        <td>{{ obj.user.name }}</td>
        <td>{{ obj.user.email }}</td>
        <td>{{ obj.user.company }}</td>
      </tr>
      </template>

      </tbody>
      </table>
    </div>
  </div>
</div>
</ss:template>
