<div class="col-lg-3 col-md-5 col-sm-12 col-xs-12">
    <aside class="right-sidebar">
        {{-- <div class="mb-4">
            <form method="GET" action="{{ Helper::sectionURL($WebmasterSection->id,"",$WebmasterSection) }}" class="form-search" id="side-search-form">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" autocomplete="off" name="search_word" id="search_word" value="{{ @$search_word }}" required maxlength="50"
                           placeholder="{{ __('frontend.search') }}" class="form-control mb-0"/>
                    <button class="btn btn-lg btn-secondary" type="submit" id="button-addon2">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </div> --}}
        @if(count($Categories)>0)
                <?php
                $title_var = "title_".@Helper::currentLanguage()->code;
                $title_var2 = "title_".config('smartend.default_language');
                $slug_var = "seo_url_slug_".@Helper::currentLanguage()->code;
                $slug_var2 = "seo_url_slug_".config('smartend.default_language');
                ?>
            <div class="widget categories-widget sidebar-list mb-4">
                <div class="widget-title categories-title">
                    <span class="float-end d-block d-sm-none"><i class="fa-solid fa-bars"></i></span>
                    <h5 class="m-0">{{ __('frontend.categories') }}</h5>
                </div>
                <div class="categories-list">
                    <ul class="list-group">
                        @foreach($Categories as $Category)
                                <?php $active_cat = ""; ?>
                            @if($CurrentCategory!="none")
                                @if(!empty($CurrentCategory))
                                    @if($Category->id == $CurrentCategory->id)
                                            <?php $active_cat = "active"; ?>
                                    @endif
                                @endif
                            @endif
                                <?php
                                if ($Category->$title_var != "") {
                                    $Category_title = $Category->$title_var;
                                } else {
                                    $Category_title = $Category->$title_var2;
                                }
                                ?>
                            <li class="list-group-item">
                                <a href="{{ Helper::categoryURL($Category->id,"",$Category) }}" class="nav-link {{ $active_cat }}">
                                            <span>
                                                 @if($Category->icon !="")
                                                    <i class="{{$Category->icon}}"></i> &nbsp;
                                                @endif
                                                {{ $Category_title }}
                                            </span>
                                </a>
                                @if(count($Category->fatherSections))
                                    <ul class="list-group">
                                        @foreach($Category->fatherSections as $MnuCategory)
                                                <?php $active_cat = ""; ?>
                                            @if($CurrentCategory!="none")
                                                @if(!empty($CurrentCategory))
                                                    @if($MnuCategory->id == $CurrentCategory->id)
                                                            <?php $active_cat = "active"; ?>
                                                    @endif
                                                @endif
                                            @endif
                                                <?php
                                                if ($MnuCategory->$title_var != "") {
                                                    $MnuCategory_title = $MnuCategory->$title_var;
                                                } else {
                                                    $MnuCategory_title = $MnuCategory->$title_var2;
                                                }
                                                ?>
                                            <li class="list-group-item"><a
                                                    href="{{ Helper::categoryURL($MnuCategory->id,"",$MnuCategory) }}"
                                                    class="nav-link {{ $active_cat }}">
                                            <span>
                                                 @if($MnuCategory->icon !="")
                                                    <i class="{{$MnuCategory->icon}}"></i> &nbsp;
                                                @endif
                                                {{ $MnuCategory_title }}
                                            </span>
                                                </a>
                                                @if(count($MnuCategory->fatherSections))
                                                    <ul class="list-group">
                                                        @foreach($MnuCategory->fatherSections as $MnuCategory2)
                                                                <?php $active_cat = ""; ?>
                                                            @if($CurrentCategory!="none")
                                                                @if(!empty($CurrentCategory))
                                                                    @if($MnuCategory2->id == $CurrentCategory->id)
                                                                            <?php $active_cat = "active"; ?>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                                <?php
                                                                if ($MnuCategory2->$title_var != "") {
                                                                    $MnuCategory2_title = $MnuCategory2->$title_var;
                                                                } else {
                                                                    $MnuCategory2_title = $MnuCategory2->$title_var2;
                                                                }
                                                                ?>
                                                            <li class="list-group-item"><a
                                                                    href="{{ Helper::categoryURL($MnuCategory2->id,"",$MnuCategory2) }}"
                                                                    class="nav-link {{ $active_cat }}">
                                            <span>
                                                 @if($MnuCategory2->icon !="")
                                                    <i class="{{$MnuCategory2->icon}}"></i> &nbsp;
                                                @endif
                                                {{ $MnuCategory2_title }}
                                            </span>
                                                                    @if(@$TopicsCountPerCat[$MnuCategory2->id] >0)
                                                                        <span
                                                                            class="badge bg-primary">{{ @$TopicsCountPerCat[$MnuCategory2->id] }}</span>
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        @endif
        {{-- @if(@count(@$MostViewedTopics) >0)
            <div class="widget mb-4 d-none d-md-block">
                <h5 class="widget-title">{{ __('frontend.mostViewed') }}</h5>

                @foreach($MostViewedTopics as $TopicMostViewed)
                    @include("frontEnd.topic.card2",["Post"=>$TopicMostViewed])
                @endforeach

            </div>
        @endif --}}

        @include('frontEnd.layouts.banners',["BannersSettingsId"=>Helper::GeneralWebmasterSettings("side_banners_section_id")])

    </aside>
</div>
