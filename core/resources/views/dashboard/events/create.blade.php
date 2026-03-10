<div id="mmn-new" class="modal fade"
     data-backdrop="true">
    <div class="modal-dialog" id="animate">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="material-icons">&#xe02e;</i> {{ __('backend.newEvent') }}
                </h5>
            </div>
            <form method="POST" action="{{ route("calendarStore") }}" class="dashboard-form">
                @csrf
                <div class="modal-body p-lg">
                    <div class="p-a">
                        <div class="form-group row">
                            <label for="type0"
                                   class="col-sm-3 form-control-label">{!!  __('backend.eventType') !!}</label>
                            <div class="col-sm-9">
                                <div class="radio">
                                    <label class="md-check" id="type0l">
                                        <input type="radio" name="type" value="0" class="has-value" checked id="type0">
                                        <i class="primary"></i>
                                        {{ __('backend.eventNote') }}
                                    </label>
                                    &nbsp;
                                    <label class="md-check" id="type1l">
                                        <input type="radio" name="type" value="1" class="has-value" id="type1">
                                        <i class="green"></i>
                                        {{ __('backend.eventMeeting') }}
                                    </label>
                                    &nbsp;
                                    <label class="md-check" id="type2l">
                                        <input type="radio" name="type" value="2" class="has-value" id="type2">
                                        <i class="danger"></i>
                                        {{ __('backend.eventEvent') }}
                                    </label>
                                    &nbsp;
                                    <label class="md-check" id="type3l">
                                        <input type="radio" name="type" value="3" class="has-value" id="type3">
                                        <i class="info"></i>
                                        {{ __('backend.eventTask') }}
                                    </label>
                                </div>
                            </div>
                        </div>


                        <div id="date" class="form-group row">
                            <label for="date"
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
                                        <input type="text" name="date" id="date" value="{{ Helper::formatDate(date("Y-m-d")) }}" class="form-control">
                                        <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="date_at" class="form-group row displayNone">
                            <label for="date_at"
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
                                        <input type="text" name="date_at" id="date_at" value="{{ Helper::formatDate(date("Y-m-d"))." ".date("h:i A") }}" class="form-control">
                                        <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="from_to_time" class="displayNone">

                            <div class="form-group row">
                                <label for="time_start"
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
                                            <input type="text" name="time_start" id="time_start" value="{{ Helper::formatDate(date("Y-m-d"))." ".date("h:i A") }}" class="form-control">
                                            <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="time_end"
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
                                            <input type="text" name="time_end" id="time_end" value="{{ Helper::formatDate(date("Y-m-d"))." ".date("h:i A") }}" class="form-control">
                                            <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="from_to_date" class="displayNone">

                            <div class="form-group row">
                                <label for="date_start"
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
                                            <input type="text" name="date_start" id="date_start" value="{{ Helper::formatDate(date("Y-m-d")) }}" class="form-control">
                                            <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="date_end"
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
                                            <input type="text" name="date_end" id="date_end" value="{{ Helper::formatDate(date("Y-m-d")) }}" class="form-control">
                                            <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
              </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="title"
                                   class="col-sm-3 form-control-label">{!!  __('backend.eventTitle') !!}
                            </label>
                            <div class="col-sm-9">
                                <input type="text" name="title" id="title" value="" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="details"
                                   class="col-sm-3 form-control-label">{!!  __('backend.eventDetails') !!}
                            </label>
                            <div class="col-sm-9">
                                <textarea name="details" id="details" rows="3" class="form-control"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn dark-white p-x-md"
                            data-dismiss="modal">{{ __('backend.cancel') }}</button>
                    <button type="submit"
                            class="btn btn-primary p-x-md">{!! __('backend.add') !!}</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div>
</div>
