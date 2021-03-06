<?php $view->script('sermon-edit', 'sermons:app/bundle/sermon-edit.js', ['vue', 'editor']) ?>
<?php $view->script('input-date-am-pm', 'sermons:app/bundle/input-date-am-pm.js', ['vue', 'uikit']) ?>
<?php $view->script('input-audio', 'sermons:app/bundle/input-audio.js', ['vue', 'uikit']) ?>

<form id="sermon" class="uk-form" v-validator="form" @submit.prevent="save | valid" v-cloak>

  <div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
    <div data-uk-margin>

      <h2 class="uk-margin-remove" v-if="sermon.id">{{ 'Edit Sermon' | trans }}</h2>
      <h2 class="uk-margin-remove" v-else>{{ 'Add Sermon' | trans }}</h2>

    </div>
    <div data-uk-margin>

      <a class="uk-button uk-margin-small-right" :href="$url.route('admin/sermons')">{{ sermon.id ? 'Close' : 'Cancel' | trans }}</a>
      <button class="uk-button uk-button-primary" type="submit">{{ 'Save' | trans }}</button>

    </div>
  </div>

  <div class="uk-grid pk-grid-large pk-width-sidebar-large uk-form-stacked" data-uk-grid-margin>
    <div class="pk-width-content">

      <div class="uk-form-row">
        <label for="title" class="uk-form-label">{{ 'Title' | trans }}</label>
        <input class="uk-width-1-1 uk-form-large" type="text" name="title" :placeholder="'Enter Title' | trans" v-model="sermon.title" v-validate:required>
        <p class="uk-form-help-block uk-text-danger" v-show="form.title.invalid">{{ 'Title cannot be blank.' | trans }}</p>
      </div>
      <div class="uk-form-row">
        <label class="uk-form-label">{{ 'Audio' | trans }}</label>
        <div class="uk-form-controls">
          <input-audio :source.sync="sermon.mp3_source" class="pk-image-max-height"></input-audio>
        </div>
      </div>
      <div class="uk-form-row">
        <label for="sermon-description" class="uk-form-label">{{ 'Description' | trans }}</label>
        <textarea class="uk-width-1-1 uk-form-large" id="sermon-description" v-model="sermon.description"></textarea>
      </div>
      <div class="uk-form-row">
        <label for="sermon-sermon_notes" class="uk-form-label">{{ 'Notes' | trans }}</label>
        <v-editor id="sermon-sermon_notes" :value.sync="sermon.sermon_notes"></v-editor>
      </div>

    </div>
    <div class="pk-width-sidebar">

      <div class="uk-panel">
        <div class="uk-form-row">
          <label for="form-image" class="uk-form-label">{{ 'Image' | trans }}</label>
          <div class="uk-form-controls">
            <input-image-meta :image.sync="sermon.data.image" class="pk-image-max-height"></input-image-meta>
          </div>
        </div>

        <div class="uk-form-row">
          <label for="form-slug" class="uk-form-label">{{ 'Slug' | trans }}</label>
          <div class="uk-form-controls">
            <input id="form-slug" class="uk-width-1-1" type="text" v-model="sermon.slug">
          </div>
        </div>
        <div class="uk-form-row">
          <label for="form-status" class="uk-form-label">{{ 'Status' | trans }}</label>
          <div class="uk-form-controls">
            <select id="form-status" class="uk-width-1-1" v-model="sermon.status">
              <option v-for="(id, status) in data.statuses" :value="id">{{status}}</option>
            </select>
          </div>
        </div>
        <div class="uk-form-row">
          <span class="uk-form-label">{{ 'Date' | trans }}</span>
          <div class="uk-form-controls">
            <input-date-am-pm :datetime.sync="sermon.date"></input-date-am-pm>
          </div>
        </div>
        <div class="uk-form-row">
          <label for="form-preacher" class="uk-form-label">{{ 'Preacher' | trans }}</label>
          <div class="uk-form-controls">
            <select id="form-preacher" class="uk-width-1-1" v-model="sermon.preacher_id">
              <option v-for="preacher in data.preachers" :value="preacher.id">{{preacher.name}}</option>
            </select>
          </div>
        </div>
        <div class="uk-form-row">
          <label for="form-series" class="uk-form-label">{{ 'Series' | trans }}</label>
          <div class="uk-form-controls">
            <select id="form-series" class="uk-width-1-1" v-model="sermon.sermon_series_id">
              <option v-for="series in data.series" :value="series.id">{{series.name}}</option>
            </select>
          </div>
        </div>
        <div class="uk-form-row">
          <label for="form-bible_passage" class="uk-form-label">{{ 'Bible Passage' | trans }}</label>
          <div class="uk-form-controls">
            <input id="form-bible_passage" class="uk-width-1-1" type="text" v-model="sermon.bible_passage">
          </div>
        </div>
        <div class="uk-form-row">
          <label for="form-topic" class="uk-form-label">{{ 'Topics' | trans }}</label>
          <div class="uk-form-controls">
            <select id="form-topic" class="uk-width-1-1" v-model="selected.topic">
              <option v-for="topic in data.topics" :value="topic">{{topic.name}}</option>
            </select>
          </div>
          <div v-for="topic in sermon.topics">
            <img src="packages/beejjacobs/sermons/assets/ic_remove_circle_outline_black_24px.svg" width="20" v-on:click="removeTopic(topic)">
            {{topic.name}}
          </div>
        </div>
        <div class="uk-form-row">
          <label for="form-bible_book" class="uk-form-label">{{ 'Books of the Bible' | trans }}</label>
          <div class="uk-form-controls">
            <select id="form-bible_book" class="uk-width-1-1" v-model="selected.bible_book">
              <option v-for="bible_book in data.bible_books" :value="bible_book">{{bible_book.name}}</option>
            </select>
          </div>
          <div v-for="bible_book in sermon.bible_books">
            <img src="packages/beejjacobs/sermons/assets/ic_remove_circle_outline_black_24px.svg" width="20" v-on:click="removeBibleBook(bible_book)">
            {{bible_book.name}}
          </div>
        </div>
      </div>

    </div>
  </div>

</form>
