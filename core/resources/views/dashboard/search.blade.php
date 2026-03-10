@extends('dashboard.layouts.master')
@section('title', Helper::GeneralSiteSettings("site_title_".@Helper::currentLanguage()->code))
@section('content')
    <div class="padding">
        <form method="GET" action="{{ route('adminSearch') }}" class="dashboard-form m-b-md">
            <div class="input-group input-group-lg">
                <input type="text" name="q" value="{{ $search_word }}" class="form-control"
                       placeholder="{{ __('backend.search') }}..." required>
                <span class="input-group-btn">
        <button class="btn b-a no-shadow white" type="submit">{{ __('backend.search') }}</button>
      </span>
            </div>
        </form>
        <p class="m-b-md">
            <strong>{{ $totalcount = count($Topics) + count($Sections) + count($Contacts) + count($Events) + count($Webmails) }}</strong> {{ __('backend.resultsFoundFor') }}
            : <strong>{{ $search_word }}</strong></p>

        <ul class="nav nav-sm nav-pills nav-active-primary clearfix">
            @if(count($Topics)>0)
                <li class="nav-item">
                    <a class="nav-link {{ ($active_tab==1) ? "active":"" }}" data-toggle="tab"
                       data-target="#tab_1"> {{ __('backend.pages') }}
                        <span
                            class="label label-xs primary m-l-xs">{{count($Topics)}}</span></a>
                </li>
            @endif
            @if(count($Sections)>0)
                <li class="nav-item">
                    <a class="nav-link {{ ($active_tab==2) ? "active":"" }}" data-toggle="tab"
                       data-target="#tab_2"> {{ __('backend.categories') }}
                        <span
                            class="label label-xs primary m-l-xs">{{count($Sections)}}</span></a>
                </li>
            @endif
            @if(count($Contacts)>0)
                <li class="nav-item">
                    <a class="nav-link {{ ($active_tab==3) ? "active":"" }}" data-toggle="tab"
                       data-target="#tab_3"> {{ __('backend.newsletter') }}
                        <span
                            class="label label-xs primary m-l-xs">{{count($Contacts)}}</span></a>
                </li>
            @endif
            @if(count($Events)>0)
                <li class="nav-item">
                    <a class="nav-link {{ ($active_tab==4) ? "active":"" }}" data-toggle="tab"
                       data-target="#tab_4"> {{ __('backend.notesEvents') }}
                        <span
                            class="label label-xs primary m-l-xs">{{count($Events)}}</span></a>
                </li>
            @endif
            @if(count($Webmails)>0)
                <li class="nav-item">
                    <a class="nav-link {{ ($active_tab==5) ? "active":"" }}" data-toggle="tab"
                       data-target="#tab_5"> {{ __('backend.inbox') }} <span
                            class="label label-xs primary m-l-xs">{{count($Webmails)}}</span></a>
                </li>
            @endif
        </ul>

        <div class="tab-content">
            @if(count($Topics)>0)
                <div class="tab-pane p-v-sm  {{ ($active_tab==1) ? "active":"" }}" id="tab_1">

                        <?php
                        $title_var = "title_".@Helper::currentLanguage()->code;
                        $title_var2 = "title_".config('smartend.default_language');
                        $details_var = "details_".@Helper::currentLanguage()->code;
                        ?>
                    @foreach($Topics as $Topic)
                            <?php
                            if ($Topic->$title_var != "") {
                                $title = $Topic->$title_var;
                            } else {
                                $title = $Topic->$title_var2;
                            }
                            $section = "";
                            try {
                                if ($Topic->webmasterSection->$title_var != "") {
                                    $section = $Topic->webmasterSection->$title_var;
                                } else {
                                    $section = $Topic->webmasterSection->$title_var2;
                                }
                            } catch (Exception $e) {
                                $section = "";
                            }
                            if (strlen(stripcslashes(strip_tags($Topic->$details_var))) > 300) {
                                $details = mb_substr(stripcslashes(strip_tags($Topic->$details_var)), 0, 300,
                                        'UTF-8')."...";
                            } else {
                                $details = stripcslashes(strip_tags($Topic->$details_var));
                            }
                            ?>
                        <div class="box m-t p-a-sm">
                            <ul class="list m-b-0">
                                <li class="list-item">
                                    @if($Topic->photo_file !="")
                                        <div class="pull-right pull-none-xs m-l w p-a-xs b-a">
                                            <img src="{{ route("fileView",["path" =>'topics/'.$Topic->photo_file]) }}"
                                                 style="max-height: 100px" alt="{{ $title }}" class="w-full">
                                        </div>
                                    @endif
                                    <div class="clear">
                                        <h5 class="m-a-0 m-b-sm text-primary"><a
                                                href="{{ route("topicsEdit",["webmasterId"=>$Topic->webmaster_id,"id"=>$Topic->id]) }}">{{ $title }}</a>
                                        </h5>
                                        <p class="text-muted m-b-0">{!! $details !!}.. <a
                                                href="{{ route("topicsEdit",["webmasterId"=>$Topic->webmaster_id,"id"=>$Topic->id]) }}"><strong>[ {{ __("backend.viewDetails") }}
                                                    ]</strong></a></p>
                                        <a href="{{ route('topics',$Topic->webmaster_id) }}"
                                           class="btn btn-xs light dk b-a m-t-sm">{!! $section  !!}</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    @endforeach

                    <div>
                        <div class="row">
                            <div class="col-sm-6">
                                <small
                                    class="text-muted inline m-t-sm m-b-sm">{{ __('backend.showing') }} {{ $Topics->firstItem() }}
                                    -{{ $Topics->lastItem() }} {{ __('backend.of') }}
                                    <strong>{{ $Topics->total()  }}</strong> {{ __('backend.records') }}
                                </small>
                            </div>
                            <div class="col-sm-6 text-right text-center-xs">
                                {!! $Topics->withQueryString()->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(count($Sections)>0)
                <div class="tab-pane p-v-sm  {{ ($active_tab==2) ? "active":"" }}" id="tab_2">
                    <div class="box m-t p-a-sm">
                        <ul class="list m-b-0">
                                <?php
                                $title_var = "title_".@Helper::currentLanguage()->code;
                                $title_var2 = "title_".config('smartend.default_language');
                                ?>
                            @foreach($Sections as $Section)
                                    <?php
                                    if ($Section->$title_var != "") {
                                        $title = $Section->$title_var;
                                    } else {
                                        $title = $Section->$title_var2;
                                    }
                                    ?>
                                <li class="list-item">
                                    <div class="clear">
                                        <h5 class="m-a-0 m-b-sm text-primary"><a
                                                href="{{ route("categoriesEdit",["webmasterId"=>$Section->webmaster_id,"id"=>$Section->id]) }}">{{ $title }}</a>
                                        </h5>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-sm-6">
                                <small
                                    class="text-muted inline m-t-sm m-b-sm">{{ __('backend.showing') }} {{ $Sections->firstItem() }}
                                    -{{ $Sections->lastItem() }} {{ __('backend.of') }}
                                    <strong>{{ $Sections->total()  }}</strong> {{ __('backend.records') }}
                                </small>
                            </div>
                            <div class="col-sm-6 text-right text-center-xs">
                                {!! $Sections->withQueryString()->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(count($Contacts)>0)
                <div class="tab-pane p-v-sm  {{ ($active_tab==3) ? "active":"" }}" id="tab_3">
                    <div class="m-t">
                        <div class="row row-sm">
                            @foreach($Contacts as $Contact)
                                <div class="col-xs-6 col-lg-4">
                                    <div class="list-item box r m-b">

                                        <a href="{{ route("contactsEdit",["id"=>$Contact->id]) }}" class="list-left">
                                            <span class="w-40 avatar">
                                            @if($Contact->photo!="")
                                                    <img src="{{ route("fileView",["path" =>'contacts/'.$Contact->photo]) }}"
                                                         class="on b-white bottom">
                                                @else
                                                    <img src="{{ route("fileView",["path" =>'contacts/profile.jpg']) }}"
                                                         class="on b-white bottom"
                                                         style="opacity: 0.5">
                                                @endif
                                            </span>
                                        </a>

                                        <div class="list-body">
                                            <div class="text-ellipsis"><a
                                                    href="{{ route("contactsEdit",["id"=>$Contact->id]) }}">{{ $Contact->first_name }} {{ $Contact->last_name }}</a>
                                            </div>
                                            <small class="text-muted text-ellipsis">
                                                <span dir="ltr">{{ $Contact->phone }}</span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <small
                                        class="text-muted inline m-t-sm m-b-sm">{{ __('backend.showing') }} {{ $Contacts->firstItem() }}
                                        -{{ $Contacts->lastItem() }} {{ __('backend.of') }}
                                        <strong>{{ $Contacts->total()  }}</strong> {{ __('backend.records') }}
                                    </small>
                                </div>
                                <div class="col-sm-6 text-right text-center-xs">
                                    {!! $Contacts->withQueryString()->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(count($Events)>0)
                <div class="tab-pane p-v-sm  {{ ($active_tab==4) ? "active":"" }}" id="tab_4">

                    @foreach($Events as $Event)
                        <div class="box m-t p-a-sm">
                            <ul class="list m-b-0">

                                <li class="list-item">
                                    <div class="clear">

                                        <h5 class="m-a-0 m-b-sm text-primary">
                                            <span class="label m-r" style="margin-bottom: 5px;">
                                                @if($Event->type ==3 || $Event->type ==2)
                                                    {{ date('d M Y  h:i A', strtotime($Event->start_date)) }}
                                                @else
                                                    {{ date('d M Y', strtotime($Event->start_date)) }}
                                                @endif
                                            </span><br>
                                            <a
                                                href="{{ route("calendarEdit",["id"=>$Event->id]) }}">{{ $Event->title }}</a>

                                        </h5>
                                        <p class="text-muted  m-b-0">{!! nl2br($Event->details) !!}</p>

                                    </div>
                                </li>
                            </ul>
                        </div>
                    @endforeach

                    <div>
                        <div class="row">
                            <div class="col-sm-6">
                                <small
                                    class="text-muted inline m-t-sm m-b-sm">{{ __('backend.showing') }} {{ $Events->firstItem() }}
                                    -{{ $Events->lastItem() }} {{ __('backend.of') }}
                                    <strong>{{ $Events->total()  }}</strong> {{ __('backend.records') }}
                                </small>
                            </div>
                            <div class="col-sm-6 text-right text-center-xs">
                                {!! $Events->withQueryString()->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(count($Webmails)>0)
                <div class="tab-pane p-v-sm  {{ ($active_tab==5) ? "active":"" }}" id="tab_5">

                    @foreach($Webmails as $Webmail)
                            <?php
                            if (strlen(stripcslashes(strip_tags($Webmail->details))) > 300) {
                                $details = mb_substr(stripcslashes(strip_tags($Webmail->details)), 0, 300,
                                        'UTF-8')."...";
                            } else {
                                $details = stripcslashes(strip_tags($Webmail->details));
                            }
                            ?>
                        <div class="box m-t p-a-sm">
                            <ul class="list m-b-0">
                                <li class="list-item">
                                    <div class="clear"><span class="label m-r">
                                                    {{ date('d M Y', strtotime($Webmail->date)) }}
                                            </span>

                                        <h5 class="m-a-0 m-b-sm text-primary"><a
                                                href="{{ route("webmailsEdit",["id"=>$Webmail->id]) }}">{{ $Webmail->title }}</a>
                                        </h5>
                                        <p class="text-muted m-b-0">{!! $Webmail->details !!}</p>

                                    </div>
                                </li>
                            </ul>
                        </div>
                    @endforeach

                </div>
            @endif


        </div>
    </div>
@endsection
