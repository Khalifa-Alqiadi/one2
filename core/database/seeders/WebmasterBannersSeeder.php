<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WebmasterBanner;

class WebmasterBannersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  Home Banners Settings
        $settings = new WebmasterBanner();
        $settings->row_no = 1;
        $settings->title_ar = "بنرات الرئيسية";
        $settings->title_en = "Home Banners";
        $settings->title_ch = "Home Banners";
        $settings->title_hi = "Home Banners";
        $settings->title_es = "Home Banners";
        $settings->title_ru = "Home Banners";
        $settings->title_pt = "Home Banners";
        $settings->title_fr = "Home Banners";
        $settings->title_de = "Home Banners";
        $settings->title_th = "Home Banners";
        $settings->title_br = "Home Banners";
        $settings->width = 1600;
        $settings->height = 600;
        $settings->desc_status = 1;
        $settings->link_status = 1;
        $settings->icon_status = 0;
        $settings->type = 1;
        $settings->status = 1;
        $settings->created_by = 1;
        $settings->save();


        //  Text Banners Settings
        $settings = new WebmasterBanner();
        $settings->row_no = 2;
        $settings->title_ar = "بنرات نصية";
        $settings->title_en = "Text Banners";
        $settings->title_ch = "Text Banners";
        $settings->title_hi = "Text Banners";
        $settings->title_es = "Text Banners";
        $settings->title_ru = "Text Banners";
        $settings->title_pt = "Text Banners";
        $settings->title_fr = "Text Banners";
        $settings->title_de = "Text Banners";
        $settings->title_th = "Text Banners";
        $settings->title_br = "Text Banners";
        $settings->width = 400;
        $settings->height = 400;
        $settings->desc_status = 1;
        $settings->link_status = 1;
        $settings->icon_status = 1;
        $settings->type = 0;
        $settings->status = 1;
        $settings->created_by = 1;
        $settings->save();

        //  Side Banners Settings
        $settings = new WebmasterBanner();
        $settings->row_no = 3;
        $settings->title_ar = "بنرات جانبية";
        $settings->title_en = "Side Banners";
        $settings->title_ch = "Side Banners";
        $settings->title_hi = "Side Banners";
        $settings->title_es = "Side Banners";
        $settings->title_ru = "Side Banners";
        $settings->title_pt = "Side Banners";
        $settings->title_fr = "Side Banners";
        $settings->title_de = "Side Banners";
        $settings->title_th = "Side Banners";
        $settings->title_br = "Side Banners";
        $settings->width = 400;
        $settings->height = 400;
        $settings->desc_status = 0;
        $settings->link_status = 1;
        $settings->icon_status = 0;
        $settings->type = 1;
        $settings->status = 1;
        $settings->created_by = 1;
        $settings->save();

    }
}
