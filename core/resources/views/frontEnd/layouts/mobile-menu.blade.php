@php($mobileMenuLinks = \App\Helpers\SiteMenu::List(64))
<section class="mobile-menu p-0 d-none d-md-none">
    <div class="mobile-menu-body">
        <ul class="mobile-menu-list d-flex align-items-center justify-content-center p-0 m-0">
            @foreach ($mobileMenuLinks as $item)
                <li class="mobile-menu-item mx-3">
                    <a href="{{ $item->url }}" class="mobile-menu-link d-block text-center {{ \App\Helpers\SiteMenu::ActiveLink(url()->current(),@$item, '') }}" target="{{ $item->target }}">
                        <img src="{{URL::to("uploads/banners/".$item->id. ".svg")}}" alt="">
                        <span>{{ $item->title }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
