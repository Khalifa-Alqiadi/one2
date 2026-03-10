<div
    class="tab-pane {{ ( Session::get('active_tab') == 'frontSettingsTab' || Session::get('active_tab') =="") ? 'active' : '' }}"
    id="tab-5">
    <div class="p-a-md"><h5>{!!  __('backend.homepageSettings') !!}</h5></div>

    <div class="p-a-md col-md-12">
        <div class="m-b-sm">
            <div class="row">
                <div class="col-md-12">
                    <div class="radio">
                        <div>
                            <label class="md-check">
                                <input type="radio" name="homepage_type" value="0" class="has-value" {{ ($WebmasterSetting->homepage_type)?"":"checked" }}  id="homepage_type1">
                                <i class="primary"></i>
                                {!!  __('backend.defaultHomepage') !!}
                            </label>
                        </div>
                        <div style="margin-top: 5px;">
                            <label class="md-check">
                                <input type="radio" name="homepage_type" value="1" class="has-value" {{ ($WebmasterSetting->homepage_type)?"checked":"" }} id="homepage_type2">
                                <i class="info"></i>
                                {!!  __('backend.customHomepage') !!}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="homepage_type0_settings" class="p-a-2 b-a m-b-2 {{ ($WebmasterSetting->homepage_type)?"displayNone":"" }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.homeRow1') }}</label>
                        <select name="home_content4_section_id"
                                class="form-control c-select">
                            <option value="0">- - {!!  __('backend.none') !!} - -</option>
                            @foreach ($SitePages as $SitePage)
                                    <?php
                                    if ($SitePage->$title_var != "") {
                                        $title = $SitePage->$title_var;
                                    } else {
                                        $title = $SitePage->$title_var2;
                                    }
                                    ?>
                                <option
                                    value="{{ $SitePage->id  }}" {{ ($SitePage->id == $WebmasterSetting->home_content4_section_id) ? "selected='selected'":""  }}>{{ $SitePage->id }}
                                    - {{ $title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.homeRow2') }}</label>
                        <select name="home_content1_section_id" id="home_content1_section_id"
                                class="form-control c-select">
                            <option value="0">- - {!!  __('backend.none') !!} - -</option>
                            @foreach ($GeneralWebmasterSections as $Webmaster_Section)
                                @if($Webmaster_Section->type !=4)
                                        <?php
                                        if ($Webmaster_Section->$title_var != "") {
                                            $WSectionTitle = $Webmaster_Section->$title_var;
                                        } else {
                                            $WSectionTitle = $Webmaster_Section->$title_var2;
                                        }
                                        ?>
                                    <option
                                        value="{{ $Webmaster_Section->id  }}" {{ ($Webmaster_Section->id == $WebmasterSetting->home_content1_section_id) ? "selected='selected'":""  }}>{{ $Webmaster_Section->id }}
                                        - {!! $WSectionTitle !!}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.homeRow_3') }}</label>
                        <select name="home_content5_section_id" id="home_content5_section_id"
                                class="form-control c-select">
                            <option value="0">- - {!!  __('backend.none') !!} - -</option>
                            @foreach ($GeneralWebmasterSections as $Webmaster_Section)
                                @if($Webmaster_Section->type !=4)
                                        <?php
                                        if ($Webmaster_Section->$title_var != "") {
                                            $WSectionTitle = $Webmaster_Section->$title_var;
                                        } else {
                                            $WSectionTitle = $Webmaster_Section->$title_var2;
                                        }
                                        ?>
                                    <option
                                        value="{{ $Webmaster_Section->id  }}" {{ ($Webmaster_Section->id == $WebmasterSetting->home_content5_section_id) ? "selected='selected'":""  }}>{{ $Webmaster_Section->id }}
                                        - {!! $WSectionTitle !!}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.homeRow_4') }}</label>
                        <select name="home_content2_section_id" id="home_content2_section_id"
                                class="form-control c-select">
                            <option value="0">- - {!!  __('backend.none') !!} - -</option>
                            @foreach ($GeneralWebmasterSections as $Webmaster_Section)
                                @if($Webmaster_Section->type !=4)
                                        <?php
                                        if ($Webmaster_Section->$title_var != "") {
                                            $WSectionTitle = $Webmaster_Section->$title_var;
                                        } else {
                                            $WSectionTitle = $Webmaster_Section->$title_var2;
                                        }
                                        ?>
                                    <option
                                        value="{{ $Webmaster_Section->id  }}" {{ ($Webmaster_Section->id == $WebmasterSetting->home_content2_section_id) ? "selected='selected'":""  }}>{{ $Webmaster_Section->id }}
                                        - {!! $WSectionTitle !!}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.homeRow_5') }}</label>
                        <select name="home_content7_section_id" id="home_content7_section_id"
                                class="form-control c-select">
                            <option value="0">- - {!!  __('backend.none') !!} - -</option>
                            @foreach ($GeneralWebmasterSections as $Webmaster_Section)
                                @if($Webmaster_Section->type !=4)
                                        <?php
                                        if ($Webmaster_Section->$title_var != "") {
                                            $WSectionTitle = $Webmaster_Section->$title_var;
                                        } else {
                                            $WSectionTitle = $Webmaster_Section->$title_var2;
                                        }
                                        ?>
                                    <option
                                        value="{{ $Webmaster_Section->id  }}" {{ ($Webmaster_Section->id == $WebmasterSetting->home_content7_section_id) ? "selected='selected'":""  }}>{{ $Webmaster_Section->id }}
                                        - {!! $WSectionTitle !!}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.homeRow_6') }}</label>
                        <select name="home_content6_section_id" id="home_content6_section_id"
                                class="form-control c-select">
                            <option value="0">- - {!!  __('backend.none') !!} - -</option>
                            @foreach ($GeneralWebmasterSections as $Webmaster_Section)
                                @if($Webmaster_Section->type !=4)
                                        <?php
                                        if ($Webmaster_Section->$title_var != "") {
                                            $WSectionTitle = $Webmaster_Section->$title_var;
                                        } else {
                                            $WSectionTitle = $Webmaster_Section->$title_var2;
                                        }
                                        ?>
                                    <option
                                        value="{{ $Webmaster_Section->id  }}" {{ ($Webmaster_Section->id == $WebmasterSetting->home_content6_section_id) ? "selected='selected'":""  }}>{{ $Webmaster_Section->id }}
                                        - {!! $WSectionTitle !!}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.homeRow_7') }}</label>
                        <select name="home_content3_section_id" id="home_content3_section_id"
                                class="form-control c-select">
                            <option value="0">- - {!!  __('backend.none') !!} - -</option>
                            @foreach ($GeneralWebmasterSections as $Webmaster_Section)
                                @if($Webmaster_Section->type !=4)
                                        <?php
                                        if ($Webmaster_Section->$title_var != "") {
                                            $WSectionTitle = $Webmaster_Section->$title_var;
                                        } else {
                                            $WSectionTitle = $Webmaster_Section->$title_var2;
                                        }
                                        ?>
                                    <option
                                        value="{{ $Webmaster_Section->id  }}" {{ ($Webmaster_Section->id == $WebmasterSetting->home_content3_section_id) ? "selected='selected'":""  }}>{{ $Webmaster_Section->id }}
                                        - {!! $WSectionTitle !!}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.homeSlideBanners') }}</label>
                        <select name="home_banners_section_id" id="home_banners_section_id"
                                class="form-control c-select">
                            <option value="0">- - {!!  __('backend.none') !!} - -</option>
                            @foreach ($WebmasterBanners as $WebmasterBanner)
                                    <?php
                                    if ($WebmasterBanner->$title_var != "") {
                                        $WBTitle = $WebmasterBanner->$title_var;
                                    } else {
                                        $WBTitle = $WebmasterBanner->$title_var2;
                                    }
                                    ?>
                                <option
                                    value="{{ $WebmasterBanner->id  }}" {{ ($WebmasterBanner->id == $WebmasterSetting->home_banners_section_id) ? "selected='selected'":""  }}>{!! $WBTitle !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.homeTextBanners') }}</label>
                        <select name="home_text_banners_section_id" id="home_text_banners_section_id"
                                class="form-control c-select">
                            <option value="0">- - {!!  __('backend.none') !!} - -</option>
                            @foreach ($WebmasterBanners as $WebmasterBanner)
                                    <?php
                                    if ($WebmasterBanner->$title_var != "") {
                                        $WBTitle = $WebmasterBanner->$title_var;
                                    } else {
                                        $WBTitle = $WebmasterBanner->$title_var2;
                                    }
                                    ?>
                                <option
                                    value="{{ $WebmasterBanner->id  }}" {{ ($WebmasterBanner->id == $WebmasterSetting->home_text_banners_section_id) ? "selected='selected'":""  }}>{!! $WBTitle  !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div id="homepage_type1_settings" class="{{ ($WebmasterSetting->homepage_type)?"":"displayNone" }}">
            <select name="landing_page_id" id="landing_page_id" class="form-control select2" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                <option value="">- - {!!  __('backend.select') !!} - -</option>
                <?php
                $title_var = "title_".@Helper::currentLanguage()->code;
                $title_var2 = "title_".config('smartend.default_language');
                ?>
                @foreach ($LandingPages as $LandingPage)
                        <?php
                        if ($LandingPage->$title_var != "") {
                            $title = $LandingPage->$title_var;
                        } else {
                            $title = $LandingPage->$title_var2;
                        }
                        ?>
                    <option
                        value="{{ $LandingPage->id  }}" {{ ($LandingPage->id == $WebmasterSetting->landing_page_id) ? "selected='selected'":""  }}>{{ $LandingPage->id }}
                        - {{ $title }}</option>
                @endforeach
            </select>
        </div>

        <div class="p-t-md"><h5>{!!  __('backend.frontSettings') !!}</h5></div>

        <div class="p-a-2 b-a m-b-2">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.contactPageId') }}</label>
                        <select name="contact_page_id" id="contact_page_id" class="form-control c-select">
                            <option value="0">- - {!!  __('backend.none') !!} - -</option>
                            <?php
                            $title_var = "title_".@Helper::currentLanguage()->code;
                            $title_var2 = "title_".config('smartend.default_language');
                            ?>
                            @foreach ($SitePages as $SitePage)
                                    <?php
                                    if ($SitePage->$title_var != "") {
                                        $title = $SitePage->$title_var;
                                    } else {
                                        $title = $SitePage->$title_var2;
                                    }
                                    ?>
                                <option
                                    value="{{ $SitePage->id  }}" {{ ($SitePage->id == $WebmasterSetting->contact_page_id) ? "selected='selected'":""  }}>{{ $SitePage->id }}
                                    - {{ $title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.newsletterGroup') }}</label>
                        <select name="newsletter_contacts_group" id="newsletter_contacts_group"
                                class="form-control c-select">
                            <option value="0">- - {!!  __('backend.none') !!} - -</option>
                            @foreach ($ContactsGroups as $ContactsGroup)
                                    <?php
                                    ?>
                                <option
                                    value="{{ $ContactsGroup->id  }}" {{ ($ContactsGroup->id == $WebmasterSetting->newsletter_contacts_group) ? "selected='selected'":""  }}>{!! $ContactsGroup->name   !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('backend.headerMenu') }}</label>
                        <select name="header_menu_id" id="header_menu_id" class="form-control c-select">
                            <option value="0">- - {!!  __('backend.none') !!} - -</option>
                            <?php
                            $title_var = "title_".@Helper::currentLanguage()->code;
                            $title_var2 = "title_".config('smartend.default_language');
                            ?>
                            @foreach ($ParentMenus as $ParentMenu)
                                    <?php
                                    if ($ParentMenu->$title_var != "") {
                                        $title = $ParentMenu->$title_var;
                                    } else {
                                        $title = $ParentMenu->$title_var2;
                                    }
                                    ?>
                                <option
                                    value="{{ $ParentMenu->id  }}" {{ ($ParentMenu->id == $WebmasterSetting->header_menu_id) ? "selected='selected'":""  }}>{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('backend.footerMenu') }}</label>
                        <select name="footer_menu_id" id="footer_menu_id" class="form-control c-select">
                            <option value="0">- - {!!  __('backend.none') !!} - -</option>
                            <?php
                            $title_var = "title_".@Helper::currentLanguage()->code;
                            $title_var2 = "title_".config('smartend.default_language');
                            ?>
                            @foreach ($ParentMenus as $ParentMenu)
                                    <?php
                                    if ($ParentMenu->$title_var != "") {
                                        $title = $ParentMenu->$title_var;
                                    } else {
                                        $title = $ParentMenu->$title_var2;
                                    }
                                    ?>
                                <option
                                    value="{{ $ParentMenu->id  }}" {{ ($ParentMenu->id == $WebmasterSetting->footer_menu_id) ? "selected='selected'":""  }}>{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('backend.sideBanners') }}</label>
                        <select name="side_banners_section_id" id="side_banners_section_id"
                                class="form-control c-select">
                            <option value="0">- - {!!  __('backend.none') !!} - -</option>
                            @foreach ($WebmasterBanners as $WebmasterBanner)
                                    <?php
                                    if ($WebmasterBanner->$title_var != "") {
                                        $WBTitle = $WebmasterBanner->$title_var;
                                    } else {
                                        $WBTitle = $WebmasterBanner->$title_var2;
                                    }
                                    ?>
                                <option
                                    value="{{ $WebmasterBanner->id  }}" {{ ($WebmasterBanner->id == $WebmasterSetting->side_banners_section_id) ? "selected='selected'":""  }}>{!! $WBTitle !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-t-md"><h5>{!!  __('backend.paginationSettings') !!}</h5></div>
        <div class="p-a-2 b-a m-b-2">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="home_contents_per_page">{{ __('backend.topicsPerPage') }}</label>
                        <input type="number" name="home_contents_per_page" id="home_contents_per_page" value="{{ $WebmasterSetting->home_contents_per_page }}" placeholder="" class="form-control"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.topicsOrderInFront') }}</label>
                        <select name="front_topics_order" id="front_topics_order"
                                class="form-control c-select">
                            <option
                                value="asc" {{ (config('smartend.frontend_topics_order') == "asc") ? "selected='selected'":""  }}>{!!  __('backend.topicsOrderInFrontAsc') !!}</option>
                            <option
                                value="desc" {{ (config('smartend.frontend_topics_order') == "desc") ? "selected='selected'":""  }}>{!!  __('backend.topicsOrderInFrontDesc') !!}</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <div class="p-t-md"><h5>{!!  __('backend.otherSettings') !!}</h5></div>
        <div class="p-a-2 b-a">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.commentsStatus') }}</label>
                        <select name="new_comments_status" id="new_comments_status"
                                class="form-control c-select">
                            <option
                                value="1" {{ ($WebmasterSetting->new_comments_status==1)?"selected":"" }}>{!!  __('backend.automaticPublish') !!}</option>
                            <option
                                value="0" {{ ($WebmasterSetting->new_comments_status==0)?"selected":"" }}>{!!  __('backend.manualByAdmin') !!}</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.cookieAcceptMessage') }}</label>
                        <select name="cookie_policy_status" id="cookie_policy_status"
                                class="form-control c-select">
                            <option
                                value="1" {{ ($WebmasterSetting->cookie_policy_status==1)?"selected":"" }}>{!!  __('backend.active') !!}</option>
                            <option
                                value="0" {{ ($WebmasterSetting->cookie_policy_status==0)?"selected":"" }}>{!!  __('backend.notActive') !!}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.dashboardLink') }}</label>
                        <select name="dashboard_link_status" id="dashboard_link_status"
                                class="form-control c-select">
                            <option
                                value="1" {{ ($WebmasterSetting->dashboard_link_status==1)?"selected":"" }}>{!!  __('backend.active') !!}</option>
                            <option
                                value="0" {{ ($WebmasterSetting->dashboard_link_status==0)?"selected":"" }}>{!!  __('backend.notActive') !!}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('backend.headerSearch') }}</label>
                        <select name="header_search_status" id="header_search_status"
                                class="form-control c-select">
                            <option
                                value="1" {{ ($WebmasterSetting->header_search_status==1)?"selected":"" }}>{!!  __('backend.active') !!}</option>
                            <option
                                value="0" {{ ($WebmasterSetting->header_search_status==0)?"selected":"" }}>{!!  __('backend.notActive') !!}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
