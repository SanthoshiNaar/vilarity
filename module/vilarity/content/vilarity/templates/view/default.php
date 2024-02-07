<?php if (!defined('ALLOW_INCLUDE_FILES')) die;
/**
 *
 */
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ss SYSTEM "&SS.XML_DTD_PATH;">
<ss:template name="#content:vilarity/templates/view/default"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:ss="http://www.basicmatrix.com/schema/ss">
<div style="height: 100%; display: flex; flex-direction: column; padding: 1em; overflow: auto;">
  <!-- navTitle: <h2>Dashboard</h2> -->

  <div class="uk-height-1-1 uk-child-width-1-4"
       style="display: flex; flex: 1; flex-wrap: wrap; flex-direction: row; align-items: stretch; gap: 1em;">

    <div style="display: flex; flex: 1; flex-wrap: wrap; flex-direction: row; align-items: stretch; gap: 1em;"
      v-bind:uk-sortable="$root.ui.mode === 'edit' ? '/*handle: .uk-sortable-handle*/' : false"
      v-on="{'start'/*.uk.sortable*/ : ($event) => OnStartReorderProgramsFromDOM($event),
             'moved'/*.uk.sortable*/ : ($event) => OnReorderProgramsFromDOM($event)}">

    <template v-for="programObj in $root.FilterByProgramAccess($root.viewAccount,programs)">
    <a v_NO-if="programObj.UUID in ui.edit !== true"
        class="vilarity-program-card"
        v-bind:key="programObj.UUID"
        v-bind:data-program-uuid="programObj.UUID"
        v-bind:class="$root.GetCardStyleClass(programObj)"
        is="router-link"
        v-bind:to="'/'+programObj.key">
    <div v-if="$root.ui.mode !== 'edit'">
      <h3>{{ programObj.title }}</h3>
      <p>{{ programObj.description }}</p>
    </div>
    <div v-if="$root.ui.mode === 'edit'" v-on:change="programObj.Save()" v-on:click.stop.prevent="">
      <h3><input class="uk-input" v-bind:data-drag-value="programObj.title" v-model="programObj.title" placeholder="Title" /></h3>
      <p><input class="uk-input" v-bind:data-drag-value="programObj.title" v-model="programObj.description" placeholder="Description" /></p>
    </div>

    <template v-for="pnObj in $root.FilterByProgramAccess($root.viewAccount,programObj.childNodes)">
    <div>
      <hr/>
      <h5>{{ pnObj.title }}</h5>
      <p>{{ pnObj.description }}</p>
    </div>
    </template>
    </a>
    </template>

    </div>

    <div v-if="$root.ui.mode === 'edit'" class="vilarity-program-card vilarity-program-card-darkgray">
      <h3><button class="uk-button" v-on:click="OnNewProgram()">+</button></h3>
      <p>Add New</p>
    </div>
    <div class="uk-width-auto">
      <button v-if="$root.permissions.vilarity.programs.edit &amp;&amp; $root.ui.mode !== 'edit'"
              v-on:click="$root.ui.mode = 'edit'" class="uk-button uk-button-small">Edit</button>
      <button v-if="$root.ui.mode === 'edit'" v-on:click="$root.ui.mode = ''" class="uk-button uk-button-small">Exit<br/>Editor</button>
    </div>
  </div>
  <div>
    <div class="uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-grid-small uk-grid-match" uk-grid="">
<!-- TODO: Not yet.
      <div>
        <div is="router-link" to="users" class="uk-card uk-card-secondary uk-card-body">
          <h3 class="uk-card-title">
            <span data-uk-icon="icon: comments; ratio: 2" class="uk-margin-small-right"></span>
            Users
          </h3>
        </div>
      </div>
-->
    </div>
    <div class="uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-grid-small uk-grid-match" uk-grid="">
<!-- TODO: Not yet.
      <div>
        <div is="router-link" to="help" class="uk-card uk-card-default uk-card-body">
          <h3 class="uk-card-title">
            <span data-uk-icon="icon: lifesaver; ratio: 2" class="uk-margin-small-right"></span>
            Help
          </h3>
        </div>
	  </div>
-->
    </div>
  </div>
</div>
</ss:template>
