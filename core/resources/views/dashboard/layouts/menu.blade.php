<?php
// Current Full URL
$fullPagePath = Request::url();
// Char Count of Backend folder Plus 1
$envAdminCharCount = strlen(config('smartend.backend_path')) + 2;
// URL after Root Path EX: admin/home
$urlAfterRoot = substr($fullPagePath, strpos($fullPagePath, '/' . config('smartend.backend_path')) + $envAdminCharCount);
$mnu_title_var = 'title_' . @Helper::currentLanguage()->code;
$mnu_title_var2 = 'title_' . config('smartend.default_language');
?>

<div id="aside" class="app-aside modal fade folded md nav-expand">
    <div class="left navside dark dk" layout="column">

        <div class="navbar navbar-md no-radius">
            <a class="hidden-folded inline folded-toggle m-t p-t-xs pull-right">
                <i class="material-icons md-24 opacity">&#xe5d2;</i>
            </a>
            <!-- brand -->
            <a class="navbar-brand" href="{{ route('adminHome') }}">
                <img src="{{ asset('assets/dashboard/images/logo.png') }}" alt="Control">
                <span class="hidden-folded inline">{{ __('backend.control') }}</span>
            </a>
            <!-- / brand -->
        </div>
        <div flex class="hide-scroll">
            <nav class="scroll nav-active-primary">

                <ul class="nav" ui-nav>
                    <li class="nav-header hidden-folded">
                        <small class="text-muted">{{ __('backend.main') }}</small>
                    </li>
                    <li {{ Route::currentRouteName() == 'adminHome' ? 'class=active' : '' }}>
                        <a href="{{ route('adminHome') }}">
                            <span class="nav-icon">
                                <i class="material-icons">&#xe3fc;</i>
                            </span>
                            <span class="nav-text">{{ __('backend.dashboard') }}</span>
                        </a>
                    </li>


                    @if (config('smartend.geoip_status'))
                        @if (Helper::GeneralWebmasterSettings('analytics_status'))
                            @if (@Auth::user()->permissionsGroup->analytics_status)
                                <?php
                                $currentFolder = 'analytics'; // Put folder name here
                                $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));

                                $currentFolder2 = 'ip'; // Put folder name here
                                $PathCurrentFolder2 = substr($urlAfterRoot, 0, strlen($currentFolder2));

                                $currentFolder3 = 'visitors'; // Put folder name here
                                $PathCurrentFolder3 = substr($urlAfterRoot, 0, strlen($currentFolder3));
                                ?>
                                <li
                                    {{ $PathCurrentFolder == $currentFolder || $PathCurrentFolder2 == $currentFolder2 || $PathCurrentFolder3 == $currentFolder3 ? 'class=active' : '' }}>
                                    <a>
                                        <span class="nav-caret">
                                            <i class="fa fa-caret-down"></i>
                                        </span>
                                        <span class="nav-icon">
                                            <i class="material-icons">&#xe1b8;</i>
                                        </span>
                                        <span class="nav-text">{{ __('backend.visitorsAnalytics') }}</span>
                                    </a>
                                    <ul class="nav-sub">
                                        <li>
                                            <a href="{{ route('analytics', 'date') }}">
                                                <span
                                                    class="nav-text">{{ __('backend.visitorsAnalyticsBydate') }}</span>
                                            </a>
                                        </li>

                                        <?php
                                        $currentFolder = 'analytics/country'; // Put folder name here
                                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                                        ?>
                                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                            <a href="{{ route('analytics', 'country') }}">
                                                <span
                                                    class="nav-text">{{ __('backend.visitorsAnalyticsByCountry') }}</span>
                                            </a>
                                        </li>

                                        <?php
                                        $currentFolder = 'analytics/city'; // Put folder name here
                                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                                        ?>
                                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                            <a href="{{ route('analytics', 'city') }}">
                                                <span
                                                    class="nav-text">{{ __('backend.visitorsAnalyticsByCity') }}</span>
                                            </a>
                                        </li>

                                        <?php
                                        $currentFolder = 'analytics/os'; // Put folder name here
                                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                                        ?>
                                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                            <a href="{{ route('analytics', 'os') }}">
                                                <span
                                                    class="nav-text">{{ __('backend.visitorsAnalyticsByOperatingSystem') }}</span>
                                            </a>
                                        </li>

                                        <?php
                                        $currentFolder = 'analytics/browser'; // Put folder name here
                                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                                        ?>
                                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                            <a href="{{ route('analytics', 'browser') }}">
                                                <span
                                                    class="nav-text">{{ __('backend.visitorsAnalyticsByBrowser') }}</span>
                                            </a>
                                        </li>

                                        <?php
                                        $currentFolder = 'analytics/referrer'; // Put folder name here
                                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                                        ?>
                                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                            <a href="{{ route('analytics', 'referrer') }}">
                                                <span
                                                    class="nav-text">{{ __('backend.visitorsAnalyticsByReachWay') }}</span>
                                            </a>
                                        </li>
                                        <?php
                                        $currentFolder = 'analytics/hostname'; // Put folder name here
                                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                                        ?>
                                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                            <a href="{{ route('analytics', 'hostname') }}">
                                                <span
                                                    class="nav-text">{{ __('backend.visitorsAnalyticsByHostName') }}</span>
                                            </a>
                                        </li>
                                        <?php
                                        $currentFolder = 'analytics/org'; // Put folder name here
                                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                                        ?>
                                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                            <a href="{{ route('analytics', 'org') }}">
                                                <span
                                                    class="nav-text">{{ __('backend.visitorsAnalyticsByOrganization') }}</span>
                                            </a>
                                        </li>
                                        <?php
                                        $currentFolder = 'visitors'; // Put folder name here
                                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                                        ?>
                                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                            <a href="{{ route('visitors') }}">
                                                <span
                                                    class="nav-text">{{ __('backend.visitorsAnalyticsVisitorsHistory') }}</span>
                                            </a>
                                        </li>
                                        <?php
                                        $currentFolder = 'ip'; // Put folder name here
                                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                                        ?>
                                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                            <a href="{{ route('visitorsIP') }}">
                                                <span
                                                    class="nav-text">{{ __('backend.visitorsAnalyticsIPInquiry') }}</span>
                                            </a>
                                        </li>


                                    </ul>
                                </li>
                            @endif
                        @endif
                    @endif
                    @if (Helper::GeneralWebmasterSettings('newsletter_status'))
                        @if (@Auth::user()->permissionsGroup->newsletter_status)
                            <?php
                            $currentFolder = 'contacts'; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                <a href="{{ route('contacts') }}">
                                    <span class="nav-icon">
                                        <i class="material-icons">&#xe7ef;</i>
                                    </span>
                                    <span class="nav-text">{{ __('backend.newsletter') }}</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    @if (Helper::GeneralWebmasterSettings('inbox_status'))
                        @if (@Auth::user()->permissionsGroup->inbox_status)
                            <?php
                            $currentFolder = 'webmails'; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                <a href="{{ route('webmails') }}">
                                    <span class="nav-icon">
                                        <i class="material-icons">&#xe156;</i>
                                    </span>
                                    <span class="nav-text">{{ __('backend.siteInbox') }}
                                        @if (Helper::webmailsNewCount() > 0)
                                            <badge class="label warn m-l-xs">{{ Helper::webmailsNewCount() }}</badge>
                                        @endif
                                    </span>

                                </a>
                            </li>
                        @endif
                    @endif

                    @if (Helper::GeneralWebmasterSettings('calendar_status'))
                        @if (@Auth::user()->permissionsGroup->calendar_status)
                            <?php
                            $currentFolder = 'calendar'; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                <a href="{{ route('calendar') }}">
                                    <span class="nav-icon">
                                        <i class="material-icons">&#xe5c3;</i>
                                    </span>
                                    <span class="nav-text">{{ __('backend.calendar') }}</span>
                                </a>
                            </li>
                        @endif
                    @endif
                    <li class="nav-header hidden-folded m-t-sm">
                        <small class="text-muted">{{ __('backend.siteData') }}</small>
                    </li>

                    <?php
                    $data_sections_arr = explode(',', Auth::user()->permissionsGroup->data_sections);
                    ?>
                    @foreach ($GeneralWebmasterSections as $GeneralWebmasterSection)
                        @if (in_array($GeneralWebmasterSection->id, $data_sections_arr))
                            <?php
                            if ($GeneralWebmasterSection->$mnu_title_var != '') {
                                $GeneralWebmasterSectionTitle = $GeneralWebmasterSection->$mnu_title_var;
                            } else {
                                $GeneralWebmasterSectionTitle = $GeneralWebmasterSection->$mnu_title_var2;
                            }

                            $LiIcon = '&#xe2c8;';
                            if ($GeneralWebmasterSection->type == 3) {
                                $LiIcon = '&#xe050;';
                            }
                            if ($GeneralWebmasterSection->type == 2) {
                                $LiIcon = '&#xe63a;';
                            }
                            if ($GeneralWebmasterSection->type == 1) {
                                $LiIcon = '&#xe251;';
                            }
                            if ($GeneralWebmasterSection->type == 0) {
                                $LiIcon = '&#xe2c8;';
                            }
                            if ($GeneralWebmasterSection->id == 1) {
                                $LiIcon = '&#xe3e8;';
                            }
                            if ($GeneralWebmasterSection->id == 7) {
                                $LiIcon = '&#xe02f;';
                            }
                            if ($GeneralWebmasterSection->id == 2) {
                                $LiIcon = '&#xe540;';
                            }
                            if ($GeneralWebmasterSection->id == 3) {
                                $LiIcon = '&#xe307;';
                            }
                            if ($GeneralWebmasterSection->id == 8) {
                                $LiIcon = '&#xe8f6;';
                            }
                            if ($GeneralWebmasterSection->type == 10) {
                                $LiIcon = '&#xe051;';
                            }

                            // get 9 char after root url to check if is "webmaster"
                            $is_webmaster = substr($urlAfterRoot, 0, 9);
                            ?>
                            @if ($GeneralWebmasterSection->sections_status > 0 && @Auth::user()->permissionsGroup->view_status == 0)
                                <li
                                    {{ $GeneralWebmasterSection->id == @$WebmasterSection->id && $is_webmaster != 'webmaster' ? 'class=active' : '' }}>
                                    <a>
                                        <span class="nav-caret">
                                            <i class="fa fa-caret-down"></i>
                                        </span>
                                        <span class="nav-icon">
                                            <i class="material-icons">{!! $LiIcon !!}</i>
                                        </span>
                                        <span class="nav-text">{!! $GeneralWebmasterSectionTitle !!}</span>
                                    </a>
                                    <ul class="nav-sub">
                                        @if ($GeneralWebmasterSection->sections_status > 0)
                                            <?php
                                            $currentFolder = 'categories'; // Put folder name here
                                            $PathCurrentFolder = substr($urlAfterRoot, strlen($GeneralWebmasterSection->id) + 1, strlen($currentFolder));
                                            ?>
                                            <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                                <a href="{{ route('categories', $GeneralWebmasterSection->id) }}">
                                                    <span class="nav-text">{{ __('backend.sectionsOf') }}
                                                        {{ $GeneralWebmasterSectionTitle }}</span>
                                                </a>
                                            </li>
                                        @endif

                                        <?php
                                        $currentFolder = 'topics'; // Put folder name here
                                        $PathCurrentFolder = substr($urlAfterRoot, strlen($GeneralWebmasterSection->id) + 1, strlen($currentFolder));
                                        ?>
                                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                            <a href="{{ route('topics', $GeneralWebmasterSection->id) }}">
                                                <span class="nav-text">{!! $GeneralWebmasterSectionTitle !!}</span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                            @else
                                <li
                                    {{ $GeneralWebmasterSection->id == @$WebmasterSection->id ? 'class=active' : '' }}>
                                    <a href="{{ route('topics', $GeneralWebmasterSection->id) }}">
                                        <span class="nav-icon">
                                            <i class="material-icons">{!! $LiIcon !!}</i>
                                        </span>
                                        <span class="nav-text">{!! $GeneralWebmasterSectionTitle !!}</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endforeach

                    <li class="nav-header hidden-folded m-t-sm">
                        <small class="text-muted">{{ __('backend.matches') }}</small>
                    </li>
                    <?php
                    $currentFolder = 'leagues'; // Put folder name here
                    $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                    ?>
                    <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                        <a href="{{ route('leagues') }}">
                            <span class="nav-icon">
                                <i class="material-icons">&#xe53b;</i>
                            </span>
                            <span class="nav-text">{{ __('backend.leagues') }}</span>
                        </a>
                    </li>
                    <?php
                    $currentFolder = 'seasons';
                    $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                    ?>
                    <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                        <a href="{{ route('seasons') }}">
                            <span class="nav-icon">
                                <i class="material-icons">&#xe53b;</i>
                            </span>
                            <span class="nav-text">{{ __('backend.seasons') }}</span>
                        </a>
                    </li>
                    <?php
                    $currentFolder = 'teams';
                    $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                    ?>
                    <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                        <a href="{{ route('teams') }}">
                            <span class="nav-icon">
                                <i class="material-icons">&#xe53b;</i>
                            </span>
                            <span class="nav-text">{{ __('backend.teams') }}</span>
                        </a>
                    </li>
                    <?php
                    $currentFolder = 'countries';
                    $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                    ?>
                    <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                        <a href="{{ route('countries') }}">
                            <span class="nav-icon">
                                <i class="material-icons">&#xe53b;</i>
                            </span>
                            <span class="nav-text">{{ __('backend.countries') }}</span>
                        </a>
                    </li>

                    @if (
                        (Helper::GeneralWebmasterSettings('banners_status') && @Auth::user()->permissionsGroup->banners_status) ||
                            (Helper::GeneralWebmasterSettings('tags_status') && @Auth::user()->permissionsGroup->tags_status) ||
                            (Helper::GeneralWebmasterSettings('popups_status') && @Auth::user()->permissionsGroup->popups_status) ||
                            (Helper::GeneralWebmasterSettings('menus_status') && @Auth::user()->permissionsGroup->menus_status) ||
                            (Helper::GeneralWebmasterSettings('file_manager_status') &&
                                @Auth::user()->permissionsGroup->file_manager_status))
                        <li class="nav-header hidden-folded m-t-sm">
                            <small class="text-muted">{{ __('backend.extra') }}</small>
                        </li>
                    @endif

                    @if (Helper::GeneralWebmasterSettings('banners_status'))
                        @if (@Auth::user()->permissionsGroup->banners_status)
                            <?php
                            $currentFolder = 'banners'; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                <a href="{{ route('Banners') }}">
                                    <span class="nav-icon">
                                        <i class="material-icons">&#xe433;</i>
                                    </span>
                                    <span class="nav-text">{{ __('backend.adsBanners') }}</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    @if (Helper::GeneralWebmasterSettings('popups_status'))
                        @if (@Auth::user()->permissionsGroup->popups_status)
                            <?php
                            $currentFolder = 'popups'; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                <a href="{{ route('popups') }}">
                                    <span class="nav-icon">
                                        <i class="material-icons">&#xe8d9;</i>
                                    </span>
                                    <span class="nav-text">{{ __('backend.popups') }}</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    @if (Helper::GeneralWebmasterSettings('tags_status'))
                        @if (@Auth::user()->permissionsGroup->tags_status)
                            <?php
                            $currentFolder = 'tags'; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                <a href="{{ route('tags') }}">
                                    <span class="nav-icon">
                                        <i class="material-icons">&#xe53b;</i>
                                    </span>
                                    <span class="nav-text">{{ __('backend.tags') }}</span>
                                </a>
                            </li>
                        @endif
                    @endif
                    @if (Helper::GeneralWebmasterSettings('menus_status'))
                        @if (@Auth::user()->permissionsGroup->menus_status)
                            <?php
                            $currentFolder = 'menus'; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                <a href="{{ route('menus') }}">
                                    <span class="nav-icon">
                                        <i class="material-icons">&#xe241;</i>
                                    </span>
                                    <span class="nav-text">{{ __('backend.siteMenus') }}</span>
                                </a>
                            </li>
                        @endif
                    @endif
                    @if (Helper::GeneralWebmasterSettings('file_manager_status'))
                        @if (@Auth::user()->permissionsGroup->file_manager_status)
                            <?php
                            $currentFolder = 'file-manager'; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                <a href="{{ route('FileManager') }}">
                                    <span class="nav-icon">
                                        <i class="material-icons">&#xe2c7;</i>
                                    </span>
                                    <span class="nav-text">{{ __('backend.fileManager') }}</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    @if (
                        @Auth::user()->permissionsGroup->roles_status ||
                            (Helper::GeneralWebmasterSettings('settings_status') && @Auth::user()->permissionsGroup->settings_status) ||
                            @Auth::user()->permissionsGroup->webmaster_status)
                        <li class="nav-header hidden-folded m-t-sm">
                            <small class="text-muted">{{ __('backend.settings') }}</small>
                        </li>
                    @endif

                    @if (@Auth::user()->permissionsGroup->roles_status)
                        <?php
                        $currentFolder = 'users'; // Put folder name here
                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                        ?>
                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                            <a href="{{ route('users') }}">
                                <span class="nav-icon">
                                    <i class="material-icons">&#xe7fb;</i>
                                </span>
                                <span class="nav-text">{{ __('backend.usersPermissions') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (Helper::GeneralWebmasterSettings('settings_status'))
                        @if (@Auth::user()->permissionsGroup->settings_status)
                            <?php
                            $currentFolder = 'settings'; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                <a href="{{ route('settings') }}">
                                    <span class="nav-icon">
                                        <i class="material-icons">&#xe8b8;</i>
                                    </span>
                                    <span class="nav-text">{{ __('backend.generalSiteSettings') }}</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    @if (@Auth::user()->permissionsGroup->webmaster_status || @Auth::user()->permissionsGroup->modules_status)
                        <li class="nav-header hidden-folded m-t-sm">
                            <small class="text-muted">{{ __('backend.webmasterTools') }}</small>
                        </li>
                    @endif

                    @if (@Auth::user()->permissionsGroup->modules_status)
                        <?php
                        $currentFolder = 'modules'; // Put folder name here
                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                        ?>
                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                            <a href="{{ route('WebmasterSections') }}">
                                <span class="nav-icon">
                                    <i class="material-icons">&#xe30d;</i>
                                </span>
                                <span class="nav-text">{{ __('backend.siteSectionsSettings') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (@Auth::user()->permissionsGroup->webmaster_status)
                        <?php
                        $currentFolder = 'webmaster'; // Put folder name here
                        $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                        ?>
                        <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                            <a href="{{ route('webmasterSettings') }}">
                                <span class="nav-icon">
                                    <i class="material-icons">&#xe8b8;</i>
                                </span>
                                <span class="nav-text">{{ __('backend.generalSettings') }}</span>
                            </a>
                        </li>
                    @endif

                </ul>
            </nav>
        </div>
        <br>
    </div>
</div>
