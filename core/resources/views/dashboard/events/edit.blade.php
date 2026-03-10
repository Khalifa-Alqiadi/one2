@if($EStatus=="edit")
    <div id="mmn-edit" class="modal fade"
         data-backdrop="true">
        <div class="modal-dialog" id="animate">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title"><i class="material-icons">&#xe3c9;</i> {{ __('backend.edit') }}
                    </h5>
                </div>
                <form method="POST" action="{{ route("calendarUpdate",$EditEvent->id) }}" class="dashboard-form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-lg">
                        <div class="p-a">
                            <div class="form-group row">
                                <label for="edit_type0"
                                       class="col-sm-3 form-control-label">{!!  __('backend.eventType') !!}</label>
                                <div class="col-sm-9">
                                    <div class="radio">
                                        <label class="md-check" id="etype0l">
                                            <input type="radio" name="type" value="0" class="has-value" {{ ($EditEvent->type==0)?"checked":"" }} id="edit_type0">
                                            <i class="primary"></i>
                                            {{ __('backend.eventNote') }}
                                        </label>
                                        &nbsp;
                                        <label class="md-check" id="etype1l">
                                            <input type="radio" name="type" value="1" class="has-value" {{ ($EditEvent->type==1)?"checked":"" }} id="edit_type1">
                                            <i class="green"></i>
                                            {{ __('backend.eventMeeting') }}
                                        </label>
                                        &nbsp;
                                        <label class="md-check" id="etype2l">
                                            <input type="radio" name="type" value="2" class="has-value" {{ ($EditEvent->type==2)?"checked":"" }} id="edit_type2">
                                            <i class="danger"></i>
                                            {{ __('backend.eventEvent') }}
                                        </label>
                                        &nbsp;
                                        <label class="md-check" id="etype3l">
                                            <input type="radio" name="type" value="3" class="has-value" {{ ($EditEvent->type==3)?"checked":"" }} id="edit_type3">
                                            <i class="info"></i>
                                            {{ __('backend.eventTask') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="e_date"
                                 class="form-group row  {!! ($EditEvent->type !=0) ? "displayNone":"" !!}">
                                <label for="edit_date"
                                       class="col-sm-3 form-control-label">{!!  __('backend.topicDate') !!}
                                </label>
                                <div class="col-sm-9">
                                    <div>
                                        <div class='input-group date' ui-jp="datetimepicker" ui-options="{
                format: '{{ Helper::jsDateFormat() }}',
                icons: {
                  time: 'fa fa-clock-o',
                  date: 'fa fa-calendar',
                  up: 'fa fa-chevron-up',
                  down: 'fa fa-chevron-down',
                  previous: 'fa fa-chevron-left',
                  next: 'fa fa-chevron-right',
                  today: 'fa fa-screenshot',
                  clear: 'fa fa-trash',
                  close: 'fa fa-remove'
                },
            allowInputToggle: true,
            locale:'{{ @Helper::currentLanguage()->code }}'
              }">
                                            <input type="text" name="date" id="edit_date" value="{{ Helper::formatDate($EditEvent->start_date) }}" class="form-control">
                                            <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div id="e_date_at"
                                 class="form-group row {!! ($EditEvent->type !=1) ? "displayNone":"" !!}">
                                <label for="edit_date_at"
                                       class="col-sm-3 form-control-label">{!!  __('backend.eventAt') !!}
                                </label>
                                <div class="col-sm-9">
                                    <div>
                                        <div class='input-group date' ui-jp="datetimepicker" ui-options="{
                format: '{{ Helper::jsDateFormat() }} hh:mm A',
                icons: {
                  time: 'fa fa-clock-o',
                  date: 'fa fa-calendar',
                  up: 'fa fa-chevron-up',
                  down: 'fa fa-chevron-down',
                  previous: 'fa fa-chevron-left',
                  next: 'fa fa-chevron-right',
                  today: 'fa fa-screenshot',
                  clear: 'fa fa-trash',
                  close: 'fa fa-remove'
                },
            allowInputToggle: true,
            locale:'{{ @Helper::currentLanguage()->code }}'
              }">
                                            <input type="text" name="date_at" id="edit_date_at" value="{{ Helper::formatDate($EditEvent->start_date)." ".date("h:i A", strtotime($EditEvent->start_date)) }}" class="form-control">
                                            <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="e_from_to_time" {!! ($EditEvent->type !=2) ? "class='displayNone'":"" !!}>

                                <div class="form-group row">
                                    <label for="edit_time_start"
                                           class="col-sm-3 form-control-label">{!!  __('backend.eventStart') !!}
                                    </label>
                                    <div class="col-sm-9">
                                        <div>
                                            <div class='input-group date' ui-jp="datetimepicker" ui-options="{
                format: '{{ Helper::jsDateFormat() }} hh:mm A',
                icons: {
                  time: 'fa fa-clock-o',
                  date: 'fa fa-calendar',
                  up: 'fa fa-chevron-up',
                  down: 'fa fa-chevron-down',
                  previous: 'fa fa-chevron-left',
                  next: 'fa fa-chevron-right',
                  today: 'fa fa-screenshot',
                  clear: 'fa fa-trash',
                  close: 'fa fa-remove'
                },
            allowInputToggle: true,
            locale:'{{ @Helper::currentLanguage()->code }}'
              }">
                                                <input type="text" name="time_start" id="edit_time_start" value="{{ Helper::formatDate($EditEvent->start_date)." ".date("h:i A", strtotime($EditEvent->start_date)) }}" class="form-control">
                                                <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="edit_time_end"
                                           class="col-sm-3 form-control-label">{!!  __('backend.eventEnd') !!}
                                    </label>
                                    <div class="col-sm-9">
                                        <div>
                                            <div class='input-group date' ui-jp="datetimepicker" ui-options="{
                format: '{{ Helper::jsDateFormat() }} hh:mm A',
                icons: {
                  time: 'fa fa-clock-o',
                  date: 'fa fa-calendar',
                  up: 'fa fa-chevron-up',
                  down: 'fa fa-chevron-down',
                  previous: 'fa fa-chevron-left',
                  next: 'fa fa-chevron-right',
                  today: 'fa fa-screenshot',
                  clear: 'fa fa-trash',
                  close: 'fa fa-remove'
                },
            allowInputToggle: true,
            locale:'{{ @Helper::currentLanguage()->code }}'
              }">
                                                <input type="text" name="time_end" id="edit_time_end" value="{{ Helper::formatDate($EditEvent->end_date)." ".date("h:i A", strtotime($EditEvent->end_date)) }}" class="form-control">
                                                <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="e_from_to_date" {!! ($EditEvent->type !=3) ? "class='displayNone'":"" !!}>

                                <div class="form-group row">
                                    <label for="edit_date_start"
                                           class="col-sm-3 form-control-label">{!!  __('backend.eventStart2') !!}
                                    </label>
                                    <div class="col-sm-9">
                                        <div>
                                            <div class='input-group date' ui-jp="datetimepicker" ui-options="{
                format: '{{ Helper::jsDateFormat() }}',
                icons: {
                  time: 'fa fa-clock-o',
                  date: 'fa fa-calendar',
                  up: 'fa fa-chevron-up',
                  down: 'fa fa-chevron-down',
                  previous: 'fa fa-chevron-left',
                  next: 'fa fa-chevron-right',
                  today: 'fa fa-screenshot',
                  clear: 'fa fa-trash',
                  close: 'fa fa-remove'
                },
            allowInputToggle: true,
            locale:'{{ @Helper::currentLanguage()->code }}'
              }">
                                                <input type="text" name="date_start" id="edit_date_start" value="{{ Helper::formatDate($EditEvent->start_date) }}" class="form-control">
                                                <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="edit_date_end"
                                           class="col-sm-3 form-control-label">{!!  __('backend.eventEnd2') !!}
                                    </label>
                                    <div class="col-sm-9">
                                        <div>
                                            <div class='input-group date' ui-jp="datetimepicker" ui-options="{
                format: '{{ Helper::jsDateFormat() }}',
                icons: {
                  time: 'fa fa-clock-o',
                  date: 'fa fa-calendar',
                  up: 'fa fa-chevron-up',
                  down: 'fa fa-chevron-down',
                  previous: 'fa fa-chevron-left',
                  next: 'fa fa-chevron-right',
                  today: 'fa fa-screenshot',
                  clear: 'fa fa-trash',
                  close: 'fa fa-remove'
                },
            allowInputToggle: true,
            locale:'{{ @Helper::currentLanguage()->code }}'
              }">
                                                <input type="text" name="date_end" id="edit_date_end" value="{{ Helper::formatDate($EditEvent->end_date) }}" class="form-control">
                                                <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="edit_title"
                                       class="col-sm-3 form-control-label">{!!  __('backend.eventTitle') !!}
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" name="title" id="edit_title" value="{{ $EditEvent->title }}" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="edit_details"
                                       class="col-sm-3 form-control-label">{!!  __('backend.eventDetails') !!}
                                </label>
                                <div class="col-sm-9">
                                    <textarea name="details" id="edit_details" rows="3" class="form-control">{{ $EditEvent->details }}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">

                        <button class="btn btn-sm warning pull-left" data-toggle="modal"
                                data-target="#m-delete" ui-toggle-class="bounce"
                                ui-target="#animate" data-dismiss="modal">
                            <small><i class="material-icons">&#xe872;</i> {{ __('backend.eventDelete') }}
                            </small>
                        </button>

                        <button type="button"
                                class="btn dark-white p-x-md"
                                data-dismiss="modal">{{ __('backend.cancel') }}</button>
                        <button type="submit"
                                class="btn btn-primary p-x-md">{{ __('backend.save') }}</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div>
    </div>

    <!-- Delete modal -->
    <div id="m-delete" class="modal fade" data-backdrop="true">
        <div class="modal-dialog" id="animate">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('backend.confirmation') }}</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <h5 class="m-b-0">
                        {{ __('backend.confirmationDeleteMsg') }}
                        <br>
                        <strong>[ {{ $EditEvent->title }} ]</strong>
                    </h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark-white p-x-md"
                            data-dismiss="modal" data-toggle="modal"
                            data-target="#mmn-edit" ui-toggle-class="bounce"
                            ui-target="#animate">{{ __('backend.no') }}</button>
                    <a href="{{ route("calendarDestroy",["id"=>$EditEvent->id]) }}"
                       class="btn danger p-x-md">{{ __('backend.yes') }}</a>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
    <!-- / .modal -->
@endif
