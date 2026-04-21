<?php
$style_color1 = Helper::GeneralSiteSettings("style_color1");
$style_color2 = Helper::GeneralSiteSettings("style_color2");
$style_color3 = Helper::GeneralSiteSettings("style_color3");
$style_color4 = Helper::GeneralSiteSettings("style_color4");

?>
<style>
    a:hover, .site-top a, #topbar .contact-info i, #topbar .contact-info a:hover, #topbar .social-links a:hover, .navbar a:hover, .navbar .active, .navbar .active:focus, .navbar li:hover > a, .navbar .dropdown ul a:hover, .navbar .dropdown ul .active:hover, .navbar .dropdown ul li:hover > a, #footer .footer-bottom a, .sidebar-list .list-group .active, .sidebar-list .list-group a:hover, .contact .info i,.owl-stage-outer .item p a,.staff .member .member-info p a {
        color: {{ Helper::GeneralSiteSettings("style_color1") }};
    }
    /* .navbar .active, .navbar .active:focus {
        color: {{ Helper::GeneralSiteSettings("style_color1") }} !important;
    } */

    .contact .info i{
        color: #fff;
    }

    #hero .carousel-item::before {
        background-color: {{ Helper::colorHexToRGB(Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),2.4),0.6) }};
    }

    a, #footer .footer-bottom a:hover, .section-title h2, .staff .member h3, .testimonials .testimonial-item h3 {
        color: {{ Helper::GeneralSiteSettings("style_color2") }};
    }

    .read-more-link:hover, .card-title:hover {
        color: {{ Helper::GeneralSiteSettings("style_color1") }} !important;
    }

    .btn-theme, #footer .footer-newsletter form button:hover, .back-to-top, .section-title h2::before, .section-title h2::after, .contact .info .email:hover i, .contact .info .address:hover i, .contact .info .phone:hover i {
        background: {{ Helper::GeneralSiteSettings("style_color1") }};
    }

    .btn-primary {
        background: {{ Helper::GeneralSiteSettings("style_color1") }};
        border-color: {{ Helper::GeneralSiteSettings("style_color1") }};
    }

    .btn-primary:hover, .btn-primary:active, .btn-primary:focus {
        background: {{ Helper::GeneralSiteSettings("style_color2") }} !important;
        border-color: {{ Helper::GeneralSiteSettings("style_color2") }} !important;
    }

    .btn-theme:hover, #footer .footer-newsletter form button, .back-to-top:hover {
        background: {{ Helper::GeneralSiteSettings("style_color2") }};
    }

    .section-bg {
        background-color: {{ Helper::GeneralSiteSettings("style_color3") }};
    }

    #footer .footer-bottom, .breadcrumbs, pre, .fixed-area-menu::-webkit-scrollbar-track {
        background-color: {{ Helper::GeneralSiteSettings("style_color3") }};
    }

    .fixed-area-menu::-webkit-scrollbar-thumb {
        background-color: {{ Helper::GeneralSiteSettings("style_color1") }};
    }

    .testimonials .testimonial-item .quote-icon-left, .testimonials .testimonial-item .quote-icon-right {
        color: {{ Helper::GeneralSiteSettings("style_color4") }};
    }

    .testimonials .swiper-pagination .swiper-pagination-bullet {
        border-color: {{ Helper::GeneralSiteSettings("style_color1") }};
    }

    .testimonials .swiper-pagination .swiper-pagination-bullet-active {
        background-color: {{ Helper::GeneralSiteSettings("style_color1") }};
    }

    .btn-secondary, .bg-secondary {
        background-color: {{ Helper::GeneralSiteSettings("style_color1") }}  !important;
        border-color: {{ Helper::GeneralSiteSettings("style_color1") }}  !important;
    }

    .btn-secondary:hover {
        background-color: {{ Helper::GeneralSiteSettings("style_color2") }}  !important;
        border-color: {{ Helper::GeneralSiteSettings("style_color2") }}  !important;
    }

    .tooltip-inner {
        background-color: {{ Helper::GeneralSiteSettings("style_color1") }};
    }

    .bs-tooltip-auto[data-popper-placement^=top] .tooltip-arrow::before, .bs-tooltip-top .tooltip-arrow::before {
        border-top-color: {{ Helper::GeneralSiteSettings("style_color1") }};
    }

    .bs-tooltip-auto[data-popper-placement^=bottom] .tooltip-arrow::before, .bs-tooltip-top .tooltip-arrow::before {
        border-bottom-color: {{ Helper::GeneralSiteSettings("style_color1") }};
    }

    .services .icon-box:hover {
        background: {{ Helper::GeneralSiteSettings("style_color2") }} !important;
        border-color: {{ Helper::GeneralSiteSettings("style_color2") }} !important;
    }

    .services .icon-box {
        border-color: {{ Helper::GeneralSiteSettings("style_color1") }}  !important;
        background: {{ Helper::GeneralSiteSettings("style_color1") }}  !important;
    }

    .services .icon-box .icon i, .services .icon-box:hover .icon i {
        color: {{ Helper::GeneralSiteSettings("style_color1") }};
    }

    .bottom-article, .widget-title, .contact .info i {
        background: {{ Helper::GeneralSiteSettings("style_color3") }};
        border-color: {{ Helper::GeneralSiteSettings("style_color4") }} !important;
    }

    .text-primary {
        color: {{ Helper::GeneralSiteSettings("style_color1") }}  !important;
    }

    .bg-primary {
        background-color: {{ Helper::GeneralSiteSettings("style_color1") }} !important;
        border-color: {{ Helper::GeneralSiteSettings("style_color1") }} !important;
    }

    .list-group-item, .card, .form-control, .card {
        border-color: {{ Helper::GeneralSiteSettings("style_color4") }};
    }

    .page-link {
        color: {{ Helper::GeneralSiteSettings("style_color1") }} !important;
    }

    .active > .page-link, .page-link.active {
        background-color: {{ Helper::GeneralSiteSettings("style_color1") }} !important;
        border-color: {{ Helper::GeneralSiteSettings("style_color1") }} !important;
    }

    #preloader:before {
        border-color: {{ Helper::GeneralSiteSettings("style_color1") }};
        border-top-color: {{ Helper::GeneralSiteSettings("style_color4") }};
    }

    #footer .footer-top .footer-links ul a:hover, #footer a:hover, #footer a:active {
        color: {{ Helper::GeneralSiteSettings("style_color1") }};
    }

    .form-control:focus {
        border-color: {{ Helper::GeneralSiteSettings("style_color4") }};
        box-shadow: 0 0 0 0.25rem{{ Helper::GeneralSiteSettings("style_color3") }};
    }

    .dropdown-item:focus, .dropdown-item:hover {
        color: {{ Helper::GeneralSiteSettings("style_color1") }} !important;
    }

    .select2-container--default .select2-results__option--selected {
        background-color: {{ Helper::GeneralSiteSettings("style_color3") }};
    }

    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: {{ Helper::GeneralSiteSettings("style_color1") }};
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: {{ Helper::GeneralSiteSettings("style_color3") }} !important;
    }

    .bg-light {
        background-color: {{ Helper::GeneralSiteSettings("style_color2") }} !important;
    }

    .text-light {
        color: {{ Helper::GeneralSiteSettings("style_color3") }} !important;
    }

    .text-light::placeholder {
        color: {{ Helper::GeneralSiteSettings("style_color4") }} !important;
        opacity: 0.7;
    }

    .text-light::-ms-input-placeholder {
        color: {{ Helper::GeneralSiteSettings("style_color4") }} !important;
        opacity: 0.7;
    }

    .accordion-item {
        border-color: {{ Helper::GeneralSiteSettings("style_color4") }} !important;
    }

    .accordion-item:last-of-type .accordion-button.collapsed {
        background: {{ Helper::GeneralSiteSettings("style_color3") }} !important;
    }

    .accordion-button:not(.collapsed) {
        color: #fff !important;
        background-color: {{ Helper::GeneralSiteSettings("style_color1") }} !important;
        box-shadow: none;
    }

    .no-data {
        color: {{ Helper::GeneralSiteSettings("style_color2") }} !important;
    }

    .btn.disabled, .btn:disabled, fieldset:disabled .btn {
        color: {{ Helper::GeneralSiteSettings("style_color4") }} !important;
    }

    .header-form-search .form-control {
        background-color: {{ Helper::GeneralSiteSettings("style_color3") }} !important;
    }

    .home-page .testimonials, .home-page .gallery , .home-page .faq,.divider {
        border-top: 1px solid {{ Helper::GeneralSiteSettings("style_color3") }} !important;
    }

    .staff .member span::after {
        background: {{ Helper::GeneralSiteSettings("style_color1") }} !important;
    }

    .cookies-accept-box {
        background-color: {{ Helper::colorHexToRGB(Helper::GeneralSiteSettings("style_color2"),0.9) }};
    }

    .post-gallery {
        background: {{ Helper::GeneralSiteSettings("style_color3") }} !important;
    }

    .line-frame {
        border: 1px solid {{ Helper::GeneralSiteSettings("style_color4") }} !important;
    }

    .gallery .gallery-item {
        border: 3px solid #fff;
    }

    .staff .member {
        background: {{ Helper::GeneralSiteSettings("style_color4") }} !important;
        box-shadow: none;
    }

    .staff .member .member-info .custom-field-value {
        background: transparent !important;
    }

    .btn-outline-theme {
        border: 1px solid {{ Helper::GeneralSiteSettings("style_color4") }};
        color: {{ Helper::GeneralSiteSettings("style_color1") }};
    }

    .btn-outline-theme:hover, .btn-outline-theme:active {
        background: {{ Helper::GeneralSiteSettings("style_color4") }};
        border: 1px solid {{ Helper::GeneralSiteSettings("style_color4") }};
    }

    #header-search-box {
        background: {{ Helper::GeneralSiteSettings("style_color2") }};
    }

    #header-search-box input[type="search"]::placeholder {
        color: {{ Helper::GeneralSiteSettings("style_color4") }};
    }

    #header-search-box input[type="search"]::-ms-input-placeholder {
        color: {{ Helper::GeneralSiteSettings("style_color4") }};
    }

    #header-search-box .close {
        background: {{ Helper::GeneralSiteSettings("style_color4") }};
        color: {{ Helper::GeneralSiteSettings("style_color1") }};
    }

    .appearance-toggle .checkbox-label .ball {
        background-color: {{ Helper::GeneralSiteSettings("style_color4") }};
    }

    .navbar a, .navbar a:focus {
        color: {{ Helper::GeneralSiteSettings("style_color2") }};
    }

    #footer {
        background-color: {{ Helper::GeneralSiteSettings("style_color2") }};
    }
    .modal-backdrop{
        background-color: {{ Helper::GeneralSiteSettings("style_color2") }};
    }

    /* Dark Mode*/
    .dark, .dark .header-bg, .dark .services .icon-box:hover .icon, .dark .staff, .dark #preloader, .dark .contact .info, .dark .contact .php-email-form {
        background: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.6) }};
        color: #fff;
    }
    .dark #preloader:before {
        border-top-color: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.6) }};
    }
    .dark #header.header-scrolled {
        background: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),2.2) }};
        box-shadow: 0 2px 10px rgba(255, 255, 255, .1) !important;
    }

    .dark #topbar.topbar-scrolled {
        background: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.6) }};
    }

    .dark .section-bg, .dark .testimonials .testimonial-item {
        background-color: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.2) }};
    }

    .dark .testimonials .testimonial-item .testimonial-img {
        border: 6px solid {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.6) }};
    }

    .dark .appearance-toggle .checkbox-label .ball {
        background-color: {{ Helper::GeneralSiteSettings("style_color2") }};
    }

    .dark .header-scrolled .header-dropdown .btn, .dark .header-scrolled .navbar a {
        color: {{ Helper::GeneralSiteSettings("style_color3") }};
    }

    .dark a, .dark #footer .footer-bottom a:hover, .dark .section-title h2, .dark .staff .member h3, .dark .testimonials .testimonial-item h3 {
        color: {{ Helper::GeneralSiteSettings("style_color3") }};
    }

    .dark .btn-theme, .dark #footer .footer-newsletter form button {
        background: {{ Helper::GeneralSiteSettings("style_color1") }};
        color: #fff;
        border: 1px solid {{ Helper::GeneralSiteSettings("style_color1") }} !important;
    }

    .dark #footer .footer-newsletter form button:hover {
        background: {{ Helper::GeneralSiteSettings("style_color2") }};
        color: #fff;
    }

    .dark .btn-theme:hover, .dark .back-to-top:hover {
        background: {{ Helper::GeneralSiteSettings("style_color2") }};
        color: {{ Helper::GeneralSiteSettings("style_color1") }};
        border: 1px solid {{ Helper::GeneralSiteSettings("style_color1") }} !important;
    }

    .dark .contact .info h4 {
        color: {{ Helper::GeneralSiteSettings("style_color3") }};
    }

    .dark .contact .info p {
        color: {{ Helper::GeneralSiteSettings("style_color3") }};
    }

    .dark .navbar a, .dark .navbar a:focus, .dark .header-dropdown .btn {
        color: {{ Helper::GeneralSiteSettings("style_color3") }};
    }

    .dark .breadcrumbs, .dark pre, .dark .fixed-area-menu::-webkit-scrollbar-track {
        background-color: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),2.4) }};
    }

    .dark #footer .footer-bottom {
        background: {{ Helper::GeneralSiteSettings("style_color2") }};
    }

    .dark #footer .footer-bottom, .dark .section-border-top {
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .dark .widget-title, .dark .contact .info i {
        background: {{ Helper::GeneralSiteSettings("style_color2") }};
        border-color: {{ Helper::GeneralSiteSettings("style_color2") }} !important;
    }
    .dark .bottom-article{
        background: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.6) }};
        border-color: {{ Helper::GeneralSiteSettings("style_color2") }} !important;
    }
    .dark #footer .copyright, .dark .mobile-nav-toggle.bi-x, .dark #footer .credits {
        color: {{ Helper::GeneralSiteSettings("style_color4") }};
    }

    .dark #footer {
        background-color: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),2.4) }};
    }

    .dark .form-control,.dark .select2-container--default .select2-selection--multiple,.dark .select2-container--default .select2-selection--single,.dark .select2-dropdown,.dark .select2-search--dropdown .select2-search__field,.dark .iti--inline-dropdown .iti__dropdown-content,.dark .iti__search-input {
        background: {{ Helper::GeneralSiteSettings("style_color2") }} !important;
        border-color: {{ Helper::GeneralSiteSettings("style_color2") }} !important;
        color: #fff !important;
    }
    .dark .select2-container--default .select2-selection--single .select2-selection__rendered,.dark .select2-container--default .select2-search--inline .select2-search__field{
        color: #fff !important;
    }

    .dark .form-control:focus {
        border-color: 0 !important;
        box-shadow: none;
    }

    .dark .select2-container--default .select2-selection--multiple .select2-selection__choice,.dark .select2-container--default .select2-results__option--selected {
        background-color: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.6) }} !important;
    }

    .dark #footer .footer-newsletter form {
        background: {{ Helper::GeneralSiteSettings("style_color2") }};
        border: 5px solid {{ Helper::GeneralSiteSettings("style_color2") }};
    }

    .dark #topbar {
        box-shadow: 0 0 1px rgba(255, 255, 255, .4);
    }

    .dark .navbar .dropdown ul {
        background-color: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.8) }};
    }

    .dark .navbar .dropdown ul a {
        color: #fff;
    }

    .dark .dropdown-menu.show {
        background-color: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.8) }};
        border-color: rgba(255, 255, 255, .15);
    }

    .dark .accordion-item {
        border-color: {{ Helper::GeneralSiteSettings("style_color2") }} !important;
        background: {{ Helper::GeneralSiteSettings("style_color2") }} !important;
        color: #fff;
    }

    .dark .accordion-item:last-of-type .accordion-button.collapsed {
        background: {{ Helper::GeneralSiteSettings("style_color2") }} !important;
        color: #fff;
    }

    .dark .accordion-button:not(.collapsed) {
        color: #fff !important;
        background-color: {{ Helper::GeneralSiteSettings("style_color1") }} !important;
        box-shadow: none;
    }

    .dark .accordion-button::after {
        filter: brightness(0) invert(1);
    }
    .dark .card,.dark .form-control,.dark .card {
        border-color: {{ Helper::GeneralSiteSettings("style_color2") }};
    }
    .dark .card{
        background: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.6) }} !important;
        color: {{ Helper::GeneralSiteSettings("style_color3") }};
    }

    .ads-top-home .card{
        background-color: #F1C40FC7 !important;
    }
    .dark .list-group-item{
        background: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.6) }} !important;
        border-color: {{ Helper::GeneralSiteSettings("style_color2") }};
    }
    .dark .text-muted{
        color: {{ Helper::GeneralSiteSettings("style_color4") }} !important;
    }
    .dark .home-page .testimonials, .dark .home-page .gallery, .dark .home-page .faq,.dark .divider {
        border-top: 1px solid {{ Helper::GeneralSiteSettings("style_color2") }} !important;
    }
    .dark .staff .member{
        background-color: {{ Helper::GeneralSiteSettings("style_color2") }}  !important;
    }
    .dark .gallery .gallery-item {
        border-color: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.6) }};
    }
    .dark .services .icon-box:hover{
        background: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),2.4) }} !important;
        border-color: {{ Helper::GeneralSiteSettings("style_color1") }} !important;
    }
    .dark .mobile-nav-toggle{
        color: #fff;
    }
    .dark .navbar-mobile ul{
        background: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.6) }};
    }
    .dark .navbar-mobile > ul > li{
        border-color: {{ Helper::GeneralSiteSettings("style_color2") }} !important;
    }
    .dark .navbar-mobile .mobile-nav-toggle{
        background: {{ Helper::GeneralSiteSettings("style_color2") }};
        border: 1px solid {{ Helper::GeneralSiteSettings("style_color2") }};
    }
    .dark .page-popup .btn-close,.dark .iti__arrow{
        filter: invert(1);
    }

    @media (max-width: 768px) {
        .dark .navbar-mobile-bg {
            background: {{ Helper::colorHexToDarken(Helper::GeneralSiteSettings("style_color2"),1.6) }};
        }
    }
</style>
