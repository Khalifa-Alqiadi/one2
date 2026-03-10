<div class="p-a-2">
    <div class="form-group row">
        <label for="tag_title" class="col-sm-3 form-control-label">{!!  __('backend.tag') !!}
        </label>
        <div class="col-sm-9">
            <input type="text" autocomplete="off" name="title" id="tag_title" value="" required maxlength="191" placeholder="" class="form-control"/>
        </div>
    </div>
    <div class="form-group row">
        <label for="tag_seo_url" class="col-sm-3 form-control-label">{!!  __('backend.friendlyURL') !!}
        </label>
        <div class="col-sm-9">
            <input type="text" autocomplete="off" name="seo_url" id="tag_seo_url" value="" required maxlength="191" placeholder="" class="form-control"/>
        </div>
    </div>
    <div class="form-group row">
        <label for="tag_details" class="col-sm-3 form-control-label">{!!  __('backend.bannerDetails') !!}
        </label>
        <div class="col-sm-9">
            <textarea name="details" id="tag_details" class="form-control" placeholder="" rows="5"></textarea>
        </div>
    </div>
</div>
