<div class="row" style="margin: 0;">
    <div class="col-sm-4 text-center primary h6 text-white wizard-step" style="padding: 0.7rem">
        <i class="material-icons">&#xe24d;</i> {{__("backend.uploadDataFile")}}
    </div>
    <div class="col-sm-4 text-center grey-300 dk h6 b-l b-r" style="padding: 0.7rem">
        <i class="material-icons">&#xe8b5;</i> {{__("backend.chooseDataFields")}}
    </div>
    <div class="col-sm-4 text-center grey-300 dk h6" style="padding: 0.7rem">
        <i class="material-icons">&#xe8b5;</i> {{__("backend.importData")}}
    </div>
</div>
<div class="p-a-2">
    <div class="form-group row m-b-5p">
        <div class="col-sm-12">
            <div class="text-muted">{!!  __('backend.dataXLSFile') !!}</div>
            <input type="file" name="file" id="file" class="form-control" accept=".xls,.xlsx" required>

            <div class="m-t">
                @if (Auth::user()->permissionsGroup->delete_status)
                    <label class="md-check">
                        <input type="checkbox" name="old_data_delete" id="old_data_delete" value="1">
                        <i class="red"></i><label
                            for="old_data_delete">
                            &nbsp;<span class="text-muted">{{ __('backend.deleteCurrentData') }}</span></label>
                    </label>
                    <br>
                @endif
            </div>
        </div>
    </div>
    <input type="hidden" name="step" value="1">
    <input type="hidden" name="section_id" value="{{ encrypt($WebmasterSection->id) }}">
</div>
