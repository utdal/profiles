<?php
$id = $id ?? str_slug($model->getRouteKey());
$tags = $model->tags;
$model_class = get_class($model);
$all_tags = Spatie\Tags\Tag::getWithType($model_class);
$random_tags = $all_tags->random(min(15, $all_tags->count()))->pluck('name')->all();
?>

{{-- Tag List --}}
<span id="{{ $id }}_current_tags" class="tags">
    @include('tags.badge')
</span>

{{-- Tag Editor --}}
<div id="{{ $id }}_tags_editor" class="tags-editor modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-body">
        <div class="container-fluid">
          <div class="alert alert-info">
            <p>Please type your desired tags, e.g. <strong>{{ implode(', ', $random_tags) }}</strong>, and etc.</p>
            <p>Press the 'enter' key or type a comma (,) after each new tag.</p>
          </div>
          {!! Form::select($id.'_tags[]', $tags->pluck('name','name')->all(), null, ['id' => $id.'_tags[]', 'multiple', 'data-token' => csrf_token(), 'data-model-name' => $id, 'data-url' => route('tags.api.update'), 'data-model-id' => $model->getKey(), 'data-model' => $model_class]) !!}
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary tagsInsertBtn">Update Tags</button>
      </div>

    </div>
  </div>
</div>
