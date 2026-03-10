<div class="p-a-2">
    <div class="form-group row">
        <label for="edit_country_title_ar" class="col-sm-3 form-control-label">{!!  __('backend.countryName') !!} (AR)
        </label>
        <div class="col-sm-9">
            <input type="text" autocomplete="off" name="title_ar" id="edit_country_title_ar" value="{{ $Country->title_ar }}" maxlength="191" placeholder="" class="form-control"/>
        </div>
    </div>
    <div class="form-group row">
        <label for="edit_country_title_en" class="col-sm-3 form-control-label">{!!  __('backend.countryName') !!} (EN)
        </label>
        <div class="col-sm-9">
            <input type="text" autocomplete="off" name="title_en" id="edit_country_title_en" value="{{ $Country->title_en }}" maxlength="191" placeholder="" class="form-control"/>
        </div>
    </div>
    <div class="form-group row">
        <label for="edit_country_code" class="col-sm-3 form-control-label">{!!  __('backend.countryCode') !!}
        </label>
        <div class="col-sm-9">
            <input type="text" autocomplete="off" name="code" id="edit_country_code" value="{{ $Country->code }}" maxlength="10" placeholder="" class="form-control"/>
        </div>
    </div>
    <div class="form-group row">
        <label for="edit_country_tel" class="col-sm-3 form-control-label">{!!  __('backend.countryTel') !!}
        </label>
        <div class="col-sm-9">
            <input type="text" autocomplete="off" name="tel" id="edit_country_tel" value="{{ $Country->tel }}" maxlength="50" placeholder="" class="form-control"/>
        </div>
    </div>
    <input type="hidden" name="country_id" value="{{ $Country->id }}">
</div>
