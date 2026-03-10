<div id="switcher">
    <div class="switcher box-color dark-white text-color" id="sw-theme">
        <a ui-toggle-class="active" target="#sw-theme" class="box-color dark-white text-color sw-btn">
            <i class="fa fa-gear"></i>
        </a>
        <div class="box-header">
            <h2>{{ __('backend.themeSwitcher') }}</h2>
        </div>
        <div class="box-body p-t-xs ">
            <p class="m-b-sm">{{ __('backend.themes') }}</p>
            <div data-target="bg" class="text-u-c text-center _600 clearfix">
                <label class="p-a col-xs-6 light pointer m-a-0">
                    <input type="radio" name="theme" value="" hidden>
                    {{ __('backend.themes1') }}
                </label>
                <label class="p-a col-xs-6 grey pointer m-a-0">
                    <input type="radio" name="theme" value="grey" hidden>
                    {{ __('backend.themes2') }}
                </label>
                <label class="p-a col-xs-6 dark pointer m-a-0">
                    <input type="radio" name="theme" value="dark" hidden>
                    {{ __('backend.themes3') }}
                </label>
                <label class="p-a col-xs-6 black pointer m-a-0">
                    <input type="radio" name="theme" value="black" hidden>
                    {{ __('backend.themes4') }}
                </label>
            </div>
            <br>
            @if(count(Helper::languagesList()) >0)
                <p class="m-b-sm">{{ __('backend.languages') }}</p>
                <div style="max-height: 200px;overflow-y: scroll">
                @foreach(Helper::languagesList() as $ActiveLanguage)
                    <div>
                        <a href="{{ route("localeChange",$ActiveLanguage->code) }}"
                           class="btn light btn-block m-b-xs text-left p-x-1">
                            @if($ActiveLanguage->icon !="")
                                <img
                                    src="{{ asset('assets/dashboard/images/flags/'.$ActiveLanguage->icon.".svg") }}"
                                    alt="" class="w-20">
                            @endif
                            {{ $ActiveLanguage->title }}
                        </a>
                    </div>
                @endforeach
                </div>
            @endif
            <div class="m-t-2">
                <a href="{{ route('cacheClear') }}" class="btn dark btn-block"
                   onclick="return confirm('{{ __('backend.cashClearMsg') }}')"><small class="text-sm">{!!  __('backend.cashClear') !!}</small></a>

            </div>
        </div>
    </div>

</div>
