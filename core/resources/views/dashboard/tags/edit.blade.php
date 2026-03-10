<div class="p-a-2">
    <div class="form-group row">
        <label for="edit_tag_title" class="col-sm-3 form-control-label">{!!  __('backend.tag') !!}
        </label>
        <div class="col-sm-9">
            <input type="text" autocomplete="off" name="title" id="edit_tag_title" value="{{ $Tag->title }}" required maxlength="191" placeholder="" class="form-control"/>
        </div>
    </div>
    <div class="form-group row">
        <label for="edit_tag_seo_url" class="col-sm-3 form-control-label">{!!  __('backend.friendlyURL') !!}
        </label>
        <div class="col-sm-9">
            <input type="text" autocomplete="off" name="seo_url" id="edit_tag_seo_url" value="{{ $Tag->seo_url }}" required maxlength="191" placeholder="" class="form-control"/>
        </div>
    </div>
    <div class="form-group row">
        <label for="edit_tag_details" class="col-sm-3 form-control-label">{!!  __('backend.bannerDetails') !!}
        </label>
        <div class="col-sm-9">
            <textarea name="details" id="edit_tag_details" class="form-control" placeholder="" rows="5">{{ $Tag->details }}</textarea>
        </div>
    </div>
    <input type="hidden" name="tag_id" value="{{ $Tag->id }}">
</div>
