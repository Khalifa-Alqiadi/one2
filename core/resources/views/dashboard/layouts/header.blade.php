<div class="app-header white box-shadow navbar-md">
    <div class="navbar">
        <!-- Open side - Naviation on mobile -->
        <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up">
            <i class="material-icons  md-30 opacity-8">&#xe5d2;</i>
        </a>

        <!-- Page title - Bind to $state's title -->
        <div class="navbar-item pull-left h5" ng-bind="$state.current.data.title" id="pageTitle"></div>

        <!-- navbar right -->
        <ul class="nav navbar-nav pull-right">
            <li class="nav-item pa-13">
                <a class="btn btn info" href="{{ route("frontendRoute") }}" target="_blank">
                    <i class="material-icons">&#xe895;</i> <small>{{ __('backend.sitePreview') }}</small>
                </a>
            </li>
            <?php
            $webmailsAlerts = Helper::webmailsAlerts();
            $webmailsNewCount = Helper::webmailsNewCount();
            $eventsAlerts = Helper::eventsAlerts();
            $alerts = $webmailsNewCount + count($eventsAlerts);
            ?>
            @if($alerts >0)
                <li class="nav-item dropdown pos-stc-xs">
                    <a class="nav-link" data-toggle="dropdown">
                        <i class="material-icons">&#xe7f5;</i>
                        @if($alerts >0)
                            <span class="label label-sm up warn">{{ $alerts }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu pull-right w-xl animated fadeInUp no-bg no-border no-shadow">
                        <div>
                            <div class="scrollable maxHeight400">
                                <ul class="list-group list-group-gap m-a-0">
                                    @foreach($eventsAlerts as $eventsAlert)
                                        <li class="list-group-item lt white dk box-shadow b">
                                    <span class="clear block">
                                        <a href="{{ route("calendarEdit",["id"=>$eventsAlert->id]) }}"
                                           class="text-primary">{{ $eventsAlert->title }}</a>
                                        <br>
                                        <small class="text-dark">
                                            @if($eventsAlert->type ==3 || $eventsAlert->type ==2)
                                                {{ date('d M Y  h:i A', strtotime($eventsAlert->start_date)) }}
                                            @else
                                                {{ date('d M Y', strtotime($eventsAlert->start_date)) }}
                                            @endif
                                        </small>
                                    </span></li>
                                    @endforeach
                                    @foreach($webmailsAlerts as $webmailsAlert)
                                        <li class="list-group-item lt white dk box-shadow b">
                                    <span class="clear block">
                                        <small  class="text-dark">{{ $webmailsAlert->from_name }}</small><br>
                                        <a href="{{ route("webmailsEdit",["id"=>$webmailsAlert->id]) }}"
                                           class="text-primary">{{ $webmailsAlert->title }}</a>
                                        <br>
                                        <small class="text-dark">
                                            {{ date('d M Y  h:i A', strtotime($webmailsAlert->date)) }}
                                        </small>
                                    </span></li>
                                    @endforeach
                                    @if($webmailsNewCount > count($webmailsAlerts))
                                        <li class="list-group-item lt white dk box-shadow b text-center">
                                    <span class="clear block">
                                            <a href="{{ route("webmails") }}"><strong class="text-dark">{{ __("backend.viewMore") }}</strong></a>
                                    </span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            @endif
            <li class="nav-item dropdown">
                <a class="nav-link clear" data-toggle="dropdown">
                  <span class="avatar w-32">
                      @if(Auth::user()->photo !="")
                          <img src="{{ route("fileView",["path" =>'users/'.Auth::user()->photo]) }}" alt="{{ Auth::user()->name }}"
                               title="{{ Auth::user()->name }}">
                      @else
                          <img src="{{ route("fileView",["path" =>'contacts/profile.jpg']) }}" alt="{{ Auth::user()->name }}"
                               title="{{ Auth::user()->name }}">
                      @endif
                      <i class="on b-white bottom"></i>
                  </span>
                </a>
                <div class="dropdown-menu pull-right dropdown-menu-scale ">
                    @if(Helper::GeneralWebmasterSettings("inbox_status"))
                        @if(@Auth::user()->permissionsGroup->inbox_status)
                            <a class="dropdown-item"
                               href="{{ route('webmails') }}"><span>{{ __('backend.siteInbox') }}</span>
                                @if( @$webmailsNewCount >0)
                                    <span class="label warn m-l-xs">{{ @$webmailsNewCount }}</span>
                                @endif
                            </a>
                        @endif
                    @endif
                    @if(Auth::user()->permissions ==0 || Auth::user()->permissions ==1)
                        <a class="dropdown-item"
                           href="{{ route('usersEdit',Auth::user()->id) }}"><span>{{ __('backend.profile') }}</span></a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('adminLogout') }}">{{ __('backend.logout') }}</a>
                </div>
            </li>

            <li class="nav-item hidden-md-up">
                <a class="nav-link" data-toggle="collapse" data-target="#collapse">
                    <i class="material-icons">&#xe5d4;</i>
                </a>
            </li>
        </ul>

        <!-- navbar collapse -->
        <div class="collapse navbar-toggleable-sm" id="collapse">
            @if(Route::currentRouteName() !="adminSearch")
                <form method="GET" action="{{ route("adminSearch") }}" class="navbar-form form-inline pull-right pull-none-sm navbar-item v-m" role="search">
                    @csrf
                    <div class="form-group l-h m-a-0">
                        <div class="input-group"><input type="text" name="q" class="form-control p-x" autocomplete="off"
                                                        placeholder="{{ __('backend.search') }}...">
                            <span
                                class="input-group-btn"><button type="submit" class="btn white b-a no-shadow"><i
                                        class="fa fa-search"></i></button></span></div>
                    </div>
                </form>
            @endif

            @if(Helper::GeneralWebmasterSettings("license") && Helper::GeneralWebmasterSettings("purchase_code")!="")
                @if(@Auth::user()->permissionsGroup->add_status)
                    <ul class="nav navbar-nav">
                        <li class="nav-item dropdown pa-13">
                            <a class="btn light" data-toggle="dropdown">
                                <i class="material-icons">&#xe145;</i>
                                <span>{{ __('backend.new') }} </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-scale">
                                    <?php
                                    $data_sections_arr = explode(",", Auth::user()->permissionsGroup->data_sections);
                                    $clr_ary = array("info", "danger", "success", "accent",);
                                    $ik = 0;
                                    $mnu_title_var = "title_".@Helper::currentLanguage()->code;
                                    $mnu_title_var2 = "title_".config('smartend.default_language');
                                    ?>
                                @if(@Auth::user()->permissionsGroup->add_status)
                                    @foreach($GeneralWebmasterSections as $headerWebmasterSection)
                                        @if(in_array($headerWebmasterSection->id,$data_sections_arr))
                                                <?php
                                                if ($headerWebmasterSection->$mnu_title_var != "") {
                                                    $GeneralWebmasterSectionTitle = $headerWebmasterSection->$mnu_title_var;
                                                } else {
                                                    $GeneralWebmasterSectionTitle = $headerWebmasterSection->$mnu_title_var2;
                                                }
                                                $LiIcon = "&#xe2c8;";
                                                if ($headerWebmasterSection->type == 3) {
                                                    $LiIcon = "&#xe050;";
                                                }
                                                if ($headerWebmasterSection->type == 2) {
                                                    $LiIcon = "&#xe63a;";
                                                }
                                                if ($headerWebmasterSection->type == 1) {
                                                    $LiIcon = "&#xe251;";
                                                }
                                                if ($headerWebmasterSection->type == 0) {
                                                    $LiIcon = "&#xe2c8;";
                                                }
                                                if ($headerWebmasterSection->id == 1) {
                                                    $LiIcon = "&#xe3e8;";
                                                }
                                                if ($headerWebmasterSection->id == 7) {
                                                    $LiIcon = "&#xe02f;";
                                                }
                                                if ($headerWebmasterSection->id == 2) {
                                                    $LiIcon = "&#xe540;";
                                                }
                                                if ($headerWebmasterSection->id == 3) {
                                                    $LiIcon = "&#xe307;";
                                                }
                                                if ($headerWebmasterSection->id == 8) {
                                                    $LiIcon = "&#xe8f6;";
                                                }
                                                if ($headerWebmasterSection->type == 10) {
                                                    $LiIcon = "&#xe051;";
                                                }

                                                ?>
                                            <a class="dropdown-item"
                                               href="{{route("topicsCreate",$headerWebmasterSection->id)}}"><span><i
                                                        class="material-icons">{!! $LiIcon !!}</i> &nbsp;{!! $GeneralWebmasterSectionTitle !!}</span></a>
                                        @endif
                                    @endforeach

                                    @if(@Auth::user()->permissionsGroup->banners_status)
                                        <a class="dropdown-item" href="{{route("Banners")}}"><i class="material-icons">
                                                &#xe433;</i>
                                            &nbsp;{{ __('backend.adsBanners') }}</a>
                                    @endif
                                    <div class="dropdown-divider"></div>

                                    @if(Helper::GeneralWebmasterSettings("newsletter_status"))
                                        @if(@Auth::user()->permissionsGroup->newsletter_status)
                                            <a class="dropdown-item" href="{{route("contacts")}}"><i
                                                    class="material-icons">
                                                    &#xe7ef;</i>
                                                &nbsp;{{ __('backend.newContacts') }}</a>
                                        @endif
                                    @endif
                                @endif
                                @if(Helper::GeneralWebmasterSettings("inbox_status"))
                                    @if(@Auth::user()->permissionsGroup->inbox_status)
                                        <a class="dropdown-item"
                                           href="{{ route("webmails",["group_id"=>"create"]) }}"><i
                                                class="material-icons">&#xe0be;</i> &nbsp;{{ __('backend.compose') }}
                                        </a>
                                    @endif
                                @endif

                            </div>
                        </li>
                    </ul>
                @endif
            @else
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <div class="pa-13">
                            <strong class="inline-block text-danger">{{ __('backend.unlicensed') }}</strong> &nbsp;
                            <a href="{{ route("webmasterSettings") }}?tab=license"
                               class="btn btn danger">{{ __('backend.licenseNow') }}</a>
                        </div>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</div>
