<?php

namespace Database\Seeders;

use App\Models\Photo;
use App\Models\TopicBlock;
use App\Models\TopicCategory;
use App\Models\TopicField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Models\Topic;

class TopicsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $details_ar = "
هذا نص تجريبي لاختبار شكل و حجم النصوص و طريقة عرضها في هذا المكان و حجم و لون الخط حيث يتم التحكم في هذا النص وامكانية تغييرة في اي وقت عن طريق ادارة الموقع . يتم اضافة هذا النص كنص تجريبي للمعاينة فقط وهو لا يعبر عن أي موضوع محدد انما لتحديد الشكل العام للقسم او الصفحة أو الموقع.<br>
هذا نص تجريبي لاختبار شكل و حجم النصوص و طريقة عرضها في هذا المكان و حجم و لون الخط حيث يتم التحكم في هذا النص وامكانية تغييرة في اي وقت عن طريق ادارة الموقع . يتم اضافة هذا النص كنص تجريبي للمعاينة فقط وهو لا يعبر عن أي موضوع محدد انما لتحديد الشكل العام للقسم او الصفحة أو الموقع. هذا نص تجريبي لاختبار شكل و حجم النصوص و طريقة عرضها في هذا المكان و حجم و لون الخط حيث يتم التحكم في هذا النص وامكانية تغييرة في اي وقت عن طريق ادارة الموقع . يتم اضافة هذا النص كنص تجريبي للمعاينة فقط وهو لا يعبر عن أي موضوع محدد انما لتحديد الشكل العام للقسم او الصفحة أو الموقع.<br>
هذا نص تجريبي لاختبار شكل و حجم النصوص و طريقة عرضها في هذا المكان و حجم و لون الخط حيث يتم التحكم في هذا النص وامكانية تغييرة في اي وقت عن طريق <br>
.يتم اضافة هذا النص كنص تجريبي للمعاينة فقط وهو لا يعبر عن أي موضوع محدد انما لتحديد الشكل العام للقسم او الصفحة أو الموقع.
هذا نص تجريبي لاختبار شكل و حجم النصوص و طريقة عرضها في هذا المكان و حجم و لون الخط حيث يتم التحكم في هذا النص وامكانية تغييرة في اي وقت عن طريق ادارة الموقع . يتم اضافة هذا النص كنص تجريبي للمعاينة فقط وهو لا يعبر عن أي موضوع محدد انما لتحديد الشكل العام للقسم او الصفحة أو الموقع. هذا نص تجريبي لاختبار شكل و حجم النصوص و طريقة عرضها في هذا المكان و حجم و لون الخط حيث يتم التحكم في هذا النص وامكانية تغييرة في اي وقت عن طريق ادارة الموقع . يتم اضافة هذا النص كنص تجريبي للمعاينة فقط وهو لا يعبر عن أي موضوع محدد انما لتحديد الشكل العام للقسم او الصفحة أو الموقع.";
        $details_en = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed at ante. Mauris eleifend, quam a vulputate dictum, massa quam dapibus leo, eget vulputate orci purus ut lorem. In fringilla mi in ligula. Pellentesque aliquam quam vel dolor. Nunc adipiscing. Sed quam odio, tempus ac, aliquam molestie, varius ac, tellus. Vestibulum ut nulla aliquam risus rutrum interdum. Pellentesque lorem. Curabitur sit amet erat quis risus feugiat viverra. Pellentesque augue justo, sagittis et, lacinia at, venenatis non, arcu. Nunc nec libero. In cursus dictum risus. Etiam tristique nisl a nulla. Ut a orci. Curabitur dolor nunc, egestas at, accumsan at, malesuada nec, magna.<br>
Nulla facilisi. Nunc volutpat. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut sit amet orci vel mauris blandit vehicula. Nullam quis enim. Integer dignissim viverra velit. Curabitur in odio. In hac habitasse platea dictumst. Ut consequat, tellus eu volutpat varius, justo orci elementum dolor, sed imperdiet nulla tellus ut diam. Vestibulum ipsum ante, malesuada quis, tempus ac, placerat sit amet, elit.<br>
Sed eget turpis a pede tempor malesuada. Vivamus quis mi at leo pulvinar hendrerit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Pellentesque aliquet lacus vitae pede. Nullam mollis dolor ac nisi. Phasellus sit amet urna. Praesent pellentesque sapien sed lacus. Donec lacinia odio in odio. In sit amet elit. Maecenas gravida interdum urna. Integer pretium, arcu vitae imperdiet facilisis, elit tellus tempor nisi, vel feugiat ante velit sit amet mauris. Vivamus arcu. Integer pharetra magna ac lacus. Aliquam vitae sapien in nibh vehicula auctor. Suspendisse leo mauris, pulvinar sed, tempor et, consequat ac, lacus. Proin velit. Nulla semper lobortis mauris. Duis urna erat, ornare et, imperdiet eu, suscipit sit amet, massa. Nulla nulla nisi, pellentesque at, egestas quis, fringilla eu, diam.<br>
Donec semper, sem nec tristique tempus, justo neque commodo nisl, ut gravida sem tellus suscipit nunc. Aliquam erat volutpat. Ut tincidunt pretium elit. Aliquam pulvinar. Nulla cursus. Suspendisse potenti. Etiam condimentum hendrerit felis. Duis iaculis aliquam enim. Donec dignissim augue vitae orci. Curabitur luctus felis a metus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In varius neque at enim. Suspendisse massa nulla, viverra in, bibendum vitae, tempor quis, lorem.<br>
Donec dapibus orci sit amet elit. Maecenas rutrum ultrices lectus. Aliquam suscipit, lacus a iaculis adipiscing, eros orci pellentesque nisl, non pharetra dolor urna nec dolor. Integer cursus dolor vel magna. Integer ultrices feugiat sem. Proin nec nibh. Duis eu dui quis nunc sagittis lobortis. Fusce pharetra, enim ut sodales luctus, lectus arcu rhoncus purus, in fringilla augue elit vel lacus. In hac habitasse platea dictumst. Aliquam erat volutpat. Fusce iaculis elit id tellus. Ut accumsan malesuada turpis. Suspendisse potenti. Vestibulum lacus augue, lobortis mattis, laoreet in, varius at, nisi. Nunc gravida. Phasellus faucibus. In hac habitasse platea dictumst. Integer tempor lacus eget lectus. Praesent fringilla augue fringilla dui.";
        $details_ch = "一个长期存在的事实是，读者会被页面的可读内容分散注意力。一个长期存在的事实是，读者会被页面的可读内容分散注意力。一个长期存在的事实是，读者会被页面的可读内容分散注意力。<br>一个长期存在的事实是，读者会被页面的可读内容分散注意力。一个长期存在的事实是，读者会被页面的可读内容分散注意力。一个长期存在的事实是，读者会被页面的可读内容分散注意力。一个长期存在的事实是，读者会被页面的可读内容分散注意力。一个长期存在的事实是，读者会被页面的可读内容分散注意力。一个长期存在的事实是，读者会被页面的可读内容分散注意力。一个长期存在的事实是，读者会被页面的可读内容分散注意力。<br>一个长期存在的事实是，读者会被页面的可读内容分散注意力。一个长期存在的事实是，读者会被页面的可读内容分散注意力。一个长期存在的事实是，读者会被页面的可读内容分散注意力。一个长期存在的事实是，读者会被页面的可读内容分散注意力。";
        $details_hi = "यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक किसी पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा। यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक किसी पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा। यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक किसी पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा।<br> यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक किसी पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा। यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक किसी पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा। यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक किसी पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा।<br> यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक किसी पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा। यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक किसी पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा। यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक किसी पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा। यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक किसी पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा। यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक किसी पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा। यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक किसी पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा।";
        $details_es = "Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página.Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página.Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página.<br>Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página.Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página.Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página.<br>Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página.Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página.<br>Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página.Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página.Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página.Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página.Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página.";
        $details_ru = "Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы.Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы.<br>Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы.Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы.Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы.Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы.Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы.Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы.<br>Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы.Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы.Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы.<br>Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы.Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы.Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы.";
        $details_pt = "É um fato estabelecido há muito tempo que um leitor se distrairá com o conteúdo legível de uma página.É um fato estabelecido há muito tempo que um leitor se distrairá com o conteúdo legível de uma página.<br>É um fato estabelecido há muito tempo que um leitor se distrairá com o conteúdo legível de uma página.É um fato estabelecido há muito tempo que um leitor se distrairá com o conteúdo legível de uma página.É um fato estabelecido há muito tempo que um leitor se distrairá com o conteúdo legível de uma página.<br>É um fato estabelecido há muito tempo que um leitor se distrairá com o conteúdo legível de uma página.É um fato estabelecido há muito tempo que um leitor se distrairá com o conteúdo legível de uma página.É um fato estabelecido há muito tempo que um leitor se distrairá com o conteúdo legível de uma página.É um fato estabelecido há muito tempo que um leitor se distrairá com o conteúdo legível de uma página.É um fato estabelecido há muito tempo que um leitor se distrairá com o conteúdo legível de uma página.<br>É um fato estabelecido há muito tempo que um leitor se distrairá com o conteúdo legível de uma página.É um fato estabelecido há muito tempo que um leitor se distrairá com o conteúdo legível de uma página.";
        $details_fr = "C'est un fait établi de longue date qu'un lecteur sera distrait par le contenu lisible d'une page.C'est un fait établi de longue date qu'un lecteur sera distrait par le contenu lisible d'une page.<br>C'est un fait établi de longue date qu'un lecteur sera distrait par le contenu lisible d'une page.C'est un fait établi de longue date qu'un lecteur sera distrait par le contenu lisible d'une page.<br>C'est un fait établi de longue date qu'un lecteur sera distrait par le contenu lisible d'une page.C'est un fait établi de longue date qu'un lecteur sera distrait par le contenu lisible d'une page.C'est un fait établi de longue date qu'un lecteur sera distrait par le contenu lisible d'une page.C'est un fait établi de longue date qu'un lecteur sera distrait par le contenu lisible d'une page.<br>C'est un fait établi de longue date qu'un lecteur sera distrait par le contenu lisible d'une page.C'est un fait établi de longue date qu'un lecteur sera distrait par le contenu lisible d'une page.";
        $details_de = "Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina.Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina.Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina.<br>Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina.Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina.Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina.Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina.<br>Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina.Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina.Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina.<br>Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina.Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina.Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina.";
        $details_th = "เป็นข้อเท็จจริงที่มีมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า เป็นข้อเท็จจริงที่มีมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า เป็นข้อเท็จจริงที่มีมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า<br> เป็นข้อเท็จจริงที่มีมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า เป็นข้อเท็จจริงที่มีมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า เป็นข้อเท็จจริงที่มีมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า<br> เป็นข้อเท็จจริงที่มีมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า เป็นข้อเท็จจริงที่มีมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า เป็นข้อเท็จจริงที่มีมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า เป็นข้อเท็จจริงที่มีมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า เป็นข้อเท็จจริงที่มีมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า";
        $details_br = "É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página. É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página. É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página.<br>É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página.É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página.<br>É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página.É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página.É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página.É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página.<br>É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página.É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página.";

        // About
        $Topic = new Topic();
        $Topic->row_no = 1;
        $Topic->webmaster_id = 1;

        $Topic->title_ar = "من نحن";
        $Topic->title_en = "About Us";
        $Topic->title_ch = "关于";
        $Topic->title_hi = "के बारे में";
        $Topic->title_es = "Acerca de";
        $Topic->title_ru = "О";
        $Topic->title_pt = "Cerca de";
        $Topic->title_fr = "À propos";
        $Topic->title_de = "Over";
        $Topic->title_th = "เกี่ยวกับ";
        $Topic->title_br = "Sobre nós";
        $Topic->seo_url_slug_ar = "من-نحن";
        $Topic->seo_url_slug_en = "about";
        $Topic->seo_url_slug_ch = "关于我们";
        $Topic->seo_url_slug_hi = "के बारे में";
        $Topic->seo_url_slug_es = "acerca-de";
        $Topic->seo_url_slug_ru = "о-нас";
        $Topic->seo_url_slug_pt = "sobre";
        $Topic->seo_url_slug_fr = "à-propos";
        $Topic->seo_url_slug_de = "um";
        $Topic->seo_url_slug_th = "เกี่ยวกับ";
        $Topic->seo_url_slug_br = "sobre-nós";

        $Topic->date = date('Y-m-d');
        $Topic->photo_file = "default.png";
        $Topic->status = 1;
        $Topic->visits = 0;
        $Topic->section_id = 0;
        $Topic->created_by = 1;
        $Topic->save();

        $TopicBlock = new TopicBlock;
        $TopicBlock->row_no = 1;
        $TopicBlock->topic_id = $Topic->id;
        $TopicBlock->block_name = "About Us";
        $TopicBlock->type = 0;
        $TopicBlock->content = '{"title_en":"","desc_en":"","details_en":'.json_encode("<h1>".$Topic->title_en."</h1><div style='text-align: justify'>".$details_en."</div>").',"bg_en":null,"title_ar":"","desc_ar":"","details_ar":'.json_encode("<h1>".$Topic->title_ar."</h1><div style='text-align: justify'>".$details_ar."</div>").',"bg_ar":null,"title_ch":"","desc_ch":"","details_ch":'.json_encode("<h1>".$Topic->title_ch."</h1><div style='text-align: justify'>".$details_ch."</div>").',"bg_ch":null,"title_hi":"","desc_hi":"","details_hi":'.json_encode("<h1>".$Topic->title_hi."</h1><div style='text-align: justify'>".$details_hi."</div>").',"bg_hi":null,"title_es":"","desc_es":"","details_es":'.json_encode("<h1>".$Topic->title_es."</h1><div style='text-align: justify'>".$details_es."</div>").',"bg_es":null,"title_ru":"","desc_ru":"","details_ru":'.json_encode("<h1>".$Topic->title_ru."</h1><div style='text-align: justify'>".$details_ru."</div>").',"bg_ru":null,"title_pt":"","desc_pt":"","details_pt":'.json_encode("<h1>".$Topic->title_pt."</h1><div style='text-align: justify'>".$details_pt."</div>").',"bg_pt":null,"title_fr":"","desc_fr":"","details_fr":'.json_encode("<h1>".$Topic->title_fr."</h1><div style='text-align: justify'>".$details_fr."</div>").',"bg_fr":null,"title_de":"","desc_de":"","details_de":'.json_encode("<h1>".$Topic->title_de."</h1><div style='text-align: justify'>".$details_de."</div>").',"bg_de":null,"title_th":"","desc_th":"","details_th":'.json_encode("<h1>".$Topic->title_th."</h1><div style='text-align: justify'>".$details_th."</div>").',"bg_th":null,"title_br":"","desc_br":"","details_br":'.json_encode("<h1>".$Topic->title_br."</h1><div style='text-align: justify'>".$details_br."</div>").',"bg_br":null}';
        $TopicBlock->title_status = 0;
        $TopicBlock->desc_status = 0;
        $TopicBlock->image_status = 0;
        $TopicBlock->divider_status = 0;
        $TopicBlock->more_btn_status = 0;
        $TopicBlock->bg_color = null;
        $TopicBlock->css_classes = null;
        $TopicBlock->status = 1;
        $TopicBlock->created_by = 1;
        $TopicBlock->save();
        $TopicBlock = new TopicBlock;
        $TopicBlock->row_no = 4;
        $TopicBlock->topic_id = $Topic->id;
        $TopicBlock->block_name = "Our Services";
        $TopicBlock->type = 3;
        $TopicBlock->content = '{"title_en":"Our Services","desc_en":"","bg_en":null,"title_ar":"\u062e\u062f\u0645\u0627\u062a\u0646\u0627","desc_ar":"","bg_ar":null,"title_ch":"\u670d\u52a1","desc_ch":"","bg_ch":null,"title_hi":"\u0938\u0947\u0935\u093e\u090f\u0902","desc_hi":"","bg_hi":null,"title_es":"Servicios","desc_es":"","bg_es":null,"title_ru":"\u0423\u0441\u043b\u0443\u0433\u0438","desc_ru":"","bg_ru":null,"title_pt":"Servi\u00e7os","desc_pt":"","bg_pt":null,"title_fr":"services","desc_fr":"","bg_fr":null,"title_de":"Dienstleistungen","desc_de":"","bg_de":null,"title_th":"\u0e1a\u0e23\u0e34\u0e01\u0e32\u0e23","desc_th":"","bg_th":null,"title_br":"Servi\u00e7os","desc_br":"","bg_br":null,"module_id":"2","category_ids":null,"records_count":"12","records_order":"1","view_style":"News"}';
        $TopicBlock->title_status = 1;
        $TopicBlock->desc_status = 0;
        $TopicBlock->image_status = 0;
        $TopicBlock->divider_status = 0;
        $TopicBlock->more_btn_status = 0;
        $TopicBlock->bg_color = null;
        $TopicBlock->css_classes = "section-bg";
        $TopicBlock->status = 1;
        $TopicBlock->created_by = 1;
        $TopicBlock->save();

        // Contact
        $Topic = new Topic();
        $Topic->row_no = 2;
        $Topic->webmaster_id = 1;

        $Topic->title_ar = "اتصل بنا";
        $Topic->title_en = "Contact Us";
        $Topic->title_ch = "接触";
        $Topic->title_hi = "संपर्क करें";
        $Topic->title_es = "Contacto";
        $Topic->title_ru = "Контакт";
        $Topic->title_pt = "Contato";
        $Topic->title_fr = "Contact";
        $Topic->title_de = "Contact";
        $Topic->title_th = "ติดต่อ";
        $Topic->title_br = "Contate-nos";

        $Topic->seo_url_slug_ar = "اتصل-بنا";
        $Topic->seo_url_slug_en = "contact";
        $Topic->seo_url_slug_ch = "接触";
        $Topic->seo_url_slug_hi = "संपर्क";
        $Topic->seo_url_slug_es = "contacto";
        $Topic->seo_url_slug_ru = "контакт";
        $Topic->seo_url_slug_pt = "contato";
        $Topic->seo_url_slug_fr = "Contactez-nous";
        $Topic->seo_url_slug_de = "kontaktiere-uns";
        $Topic->seo_url_slug_th = "ติดต่อเรา";
        $Topic->seo_url_slug_br = "Contate-nos";

        $Topic->date = date('Y-m-d');
        $Topic->status = 1;
        $Topic->visits = 0;
        $Topic->section_id = 0;
        $Topic->created_by = 1;
        $Topic->save();

        // Privacy
        $Topic = new Topic();
        $Topic->row_no = 3;
        $Topic->webmaster_id = 1;
        $Topic->title_ar = "سياسة الخصوصية";
        $Topic->title_en = "Privacy Policy";
        $Topic->title_ch = "隐私";
        $Topic->title_hi = "गोपनीयता";
        $Topic->title_es = "Intimidad";
        $Topic->title_ru = "Конфиденциальность";
        $Topic->title_pt = "Privacidade";
        $Topic->title_fr = "Intimité";
        $Topic->title_de = "Privacy";
        $Topic->title_th = "ความเป็นส่วนตัว";
        $Topic->title_br = "Privacidade";
        $Topic->seo_url_slug_ar = "الخصوصية";
        $Topic->seo_url_slug_en = "privacy";
        $Topic->seo_url_slug_ch = "隐私";
        $Topic->seo_url_slug_hi = "गोपनीयता";
        $Topic->seo_url_slug_es = "privacidad";
        $Topic->seo_url_slug_ru = "конфиденциальность";
        $Topic->seo_url_slug_pt = "privacidade";
        $Topic->seo_url_slug_fr = "confidentialité";
        $Topic->seo_url_slug_de = "Privatsphäre";
        $Topic->seo_url_slug_th = "ความเป็นส่วนตัว";
        $Topic->seo_url_slug_br = "privacidade-2";
        $Topic->date = date('Y-m-d');
        $Topic->status = 1;
        $Topic->visits = 0;
        $Topic->section_id = 0;
        $Topic->created_by = 1;
        $Topic->save();

        $TopicBlock = new TopicBlock;
        $TopicBlock->row_no = 1;
        $TopicBlock->topic_id = $Topic->id;
        $TopicBlock->block_name = "Privacy";
        $TopicBlock->type = 0;
        $TopicBlock->content = '{"title_en":"","desc_en":"","details_en":'.json_encode($details_en).',"bg_en":null,"title_ar":"","desc_ar":"","details_ar":'.json_encode($details_ar).',"bg_ar":null,"title_ch":"","desc_ch":"","details_ch":'.json_encode($details_ch).',"bg_ch":null,"title_hi":"","desc_hi":"","details_hi":'.json_encode($details_hi).',"bg_hi":null,"title_es":"","desc_es":"","details_es":'.json_encode($details_es).',"bg_es":null,"title_ru":"","desc_ru":"","details_ru":'.json_encode($details_ru).',"bg_ru":null,"title_pt":"","desc_pt":"","details_pt":'.json_encode($details_pt).',"bg_pt":null,"title_fr":"","desc_fr":"","details_fr":'.json_encode($details_fr).',"bg_fr":null,"title_de":"","desc_de":"","details_de":'.json_encode($details_de).',"bg_de":null,"title_th":"","desc_th":"","details_th":'.json_encode($details_th).',"bg_th":null,"title_br":"","desc_br":"","details_br":'.json_encode($details_br).',"bg_br":null}';
        $TopicBlock->title_status = 0;
        $TopicBlock->desc_status = 0;
        $TopicBlock->image_status = 0;
        $TopicBlock->divider_status = 0;
        $TopicBlock->more_btn_status = 0;
        $TopicBlock->bg_color = null;
        $TopicBlock->css_classes = null;
        $TopicBlock->status = 1;
        $TopicBlock->created_by = 1;
        $TopicBlock->save();

        // Terms
        $Topic = new Topic();
        $Topic->row_no = 4;
        $Topic->webmaster_id = 1;
        $Topic->title_ar = "الشروط والأحكام";
        $Topic->title_en = "Terms & Conditions";
        $Topic->title_ch = "条款和条件";
        $Topic->title_hi = "नियम एवं शर्तें";
        $Topic->title_es = "Términos y condiciones";
        $Topic->title_ru = "Условия и положения";
        $Topic->title_pt = "termos e Condições";
        $Topic->title_fr = "termes et conditions";
        $Topic->title_de = "algemene voorwaarden";
        $Topic->title_th = "ข้อตกลงและเงื่อนไข";
        $Topic->title_br = "termos e Condições";
        $Topic->seo_url_slug_ar = "الشروط-والأحكام";
        $Topic->seo_url_slug_en = "terms";
        $Topic->seo_url_slug_ch = "条款";
        $Topic->seo_url_slug_hi = "शर्तें";
        $Topic->seo_url_slug_es = "términos";
        $Topic->seo_url_slug_ru = "условия";
        $Topic->seo_url_slug_pt = "termos";
        $Topic->seo_url_slug_fr = "termes";
        $Topic->seo_url_slug_de = "Bedingungen";
        $Topic->seo_url_slug_th = "เงื่อนไข";
        $Topic->seo_url_slug_br = "termos-2";
        $Topic->date = date('Y-m-d');
        $Topic->status = 1;
        $Topic->visits = 0;
        $Topic->section_id = 0;
        $Topic->created_by = 1;
        $Topic->save();

        $TopicBlock = new TopicBlock;
        $TopicBlock->row_no = 1;
        $TopicBlock->topic_id = $Topic->id;
        $TopicBlock->block_name = "Terms & Conditions";
        $TopicBlock->type = 0;
        $TopicBlock->content = '{"title_en":"","desc_en":"","details_en":'.json_encode($details_en).',"bg_en":null,"title_ar":"","desc_ar":"","details_ar":'.json_encode($details_ar).',"bg_ar":null,"title_ch":"","desc_ch":"","details_ch":'.json_encode($details_ch).',"bg_ch":null,"title_hi":"","desc_hi":"","details_hi":'.json_encode($details_hi).',"bg_hi":null,"title_es":"","desc_es":"","details_es":'.json_encode($details_es).',"bg_es":null,"title_ru":"","desc_ru":"","details_ru":'.json_encode($details_ru).',"bg_ru":null,"title_pt":"","desc_pt":"","details_pt":'.json_encode($details_pt).',"bg_pt":null,"title_fr":"","desc_fr":"","details_fr":'.json_encode($details_fr).',"bg_fr":null,"title_de":"","desc_de":"","details_de":'.json_encode($details_de).',"bg_de":null,"title_th":"","desc_th":"","details_th":'.json_encode($details_th).',"bg_th":null,"title_br":"","desc_br":"","details_br":'.json_encode($details_br).',"bg_br":null}';
        $TopicBlock->title_status = 0;
        $TopicBlock->desc_status = 0;
        $TopicBlock->image_status = 0;
        $TopicBlock->divider_status = 0;
        $TopicBlock->more_btn_status = 0;
        $TopicBlock->bg_color = null;
        $TopicBlock->css_classes = null;
        $TopicBlock->status = 1;
        $TopicBlock->created_by = 1;
        $TopicBlock->save();

        // home
        $Topic = new Topic();
        $Topic->row_no = 5;
        $Topic->webmaster_id = 1;
        $Topic->title_ar = "الصفحة الرئيسية";
        $Topic->title_en = "Home Welcome";
        $Topic->title_ch = "家";
        $Topic->title_hi = "घर";
        $Topic->title_es = "Casa";
        $Topic->title_ru = "Дом";
        $Topic->title_pt = "Lar";
        $Topic->title_fr = "Domicile";
        $Topic->title_de = "Thuis";
        $Topic->title_th = "บ้าน";
        $Topic->title_br = "Home Welcome";


        $Topic->details_ar = "<div style='text-align: center'><h1>مرحبا بكم في موقعنا</h1>
هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص.هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص.هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص.هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص.هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء لصفحة ما سيلهي القارئ عن التركيز على الشكل الخارجي للنص.</div>"."<div class='text-center mt-3'><a href='/ar/طلب-عرض-سعر' class='btn btn-lg btn-primary'><i class='fa-solid fa-send-o'></i> ارسال طلب عرض سعر</a></div>";

        $Topic->details_en = "<div style='text-align: center'><h1>Welcome to our website</h1>It is a long established fact that a reader will be distracted by the readable content of a page.It is a long established fact that a reader will be distracted by the readable content of a page.It is a long established fact that a reader will be distracted by the readable content of a page.It is a long established fact that a reader will be distracted by the readable content of a page.It is a long established fact that a reader will be distracted by the readable content of a page.</div><div class='text-center mt-3'><a href='/en/quote-request' class='btn btn-lg btn-primary'><i class='fa-solid fa-send-o'></i> Submit Quote Request</a></div>";

        $Topic->details_ch = "<div style='text-align: center'><h1>欢迎来到我们的网站</h1>485 / 5000
Translation results
读者会被页面的可读内容分心是一个长期确立的事实 被页面的可读内容分心。长期以来，读者会被页面的可读内容分心，这是一个长期确立的事实。长期以来，读者会被页面的可读内容分心。 </div>"."<div class='text-center mt-3'><a href='/ch/报价请求' class='btn btn-lg btn-primary'><i class='fa-solid fa-send-o'></i> 提交 报价请求</a></div>";

        $Topic->details_hi = "<div style='text-align: center'><h1>हमारी वैबसाइट पर आपका स्वागत है</h1>यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक एक पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा। यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक एक पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा। यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक होगा एक पृष्ठ की पठनीय सामग्री से विचलित हो। यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक एक पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा। यह एक लंबे समय से स्थापित तथ्य है कि एक पाठक एक पृष्ठ की पठनीय सामग्री से विचलित हो जाएगा।.</div>"."<div class='text-center mt-3'><a href='/hi/उद्धरण-अनुरोध' class='btn btn-lg btn-primary'><i class='fa-solid fa-send-o'></i> जमा करें उद्धरण अनुरोध</a></div>";

        $Topic->details_es = "<div style='text-align: center'><h1>Bienvenido a nuestro sitio web</h1>Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página. Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página. distraerse con el contenido legible de una página. Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página. Es un hecho establecido desde hace mucho tiempo que un lector se distraerá con el contenido legible de una página.</div>"."<div class='text-center mt-3'><a href='/es/solicitudes-de-cotización' class='btn btn-lg btn-primary'><i class='fa-solid fa-send-o'></i> Enviar Solicitudes de cotización</a></div>";

        $Topic->details_ru = "<div style='text-align: center'><h1>Добро пожаловать на наш сайт</h1>То, что читатель будет отвлекаться на удобочитаемое содержание страницы, - давно установленный факт. То, что читатель будет отвлекаться на читаемое содержание страницы, - давно установленный факт. отвлекаться на читабельное содержание страницы. Давно установлено, что читатель будет отвлекаться на читабельное содержание страницы. Давно установившийся факт, что читатель будет отвлекаться на читаемое содержание страницы.</div>"."<div class='text-center mt-3'><a href='/ru/запросы-цен' class='btn btn-lg btn-primary'><i class='fa-solid fa-send-o'></i> Отправить Запросы цен</a></div>";

        $Topic->details_pt = "<div style='text-align: center'><h1>Bem-vindo ao nosso site</h1>É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página. É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página. É um fato estabelecido há muito tempo que um leitor irá ser distraído pelo conteúdo legível de uma página. É um fato estabelecido que um leitor será distraído pelo conteúdo legível de uma página. É um fato estabelecido que um leitor será distraído pelo conteúdo legível de uma página.</div>"."<div class='text-center mt-3'><a href='/pt/solicitações-de-cotação' class='btn btn-lg btn-primary'><i class='fa-solid fa-send-o'></i> Submeter Solicitações de cotação</a></div>";

        $Topic->details_fr = "<div style='text-align: center'><h1>Bienvenue sur notre site</h1>C'est un fait établi de longue date qu'un lecteur sera distrait par le contenu lisible d'une page.C'est un fait établi de longue date qu'un lecteur sera distrait par le contenu lisible d'une page.C'est un fait établi de longue date qu'un lecteur être distrait par le contenu lisible d'une page. C'est un fait établi de longue date qu'un lecteur sera distrait par le contenu lisible d'une page. C'est un fait établi de longue date qu'un lecteur sera distrait par le contenu lisible d'une page.</div>"."<div class='text-center mt-3'><a href='/fr/demandes-de-devis' class='btn btn-lg btn-primary'><i class='fa-solid fa-send-o'></i> Soumettre Demandes de devis</a></div>";

        $Topic->details_de = "<div style='text-align: center'><h1>Welkom op onze website</h1>Het staat al lang vast dat een lezer wordt afgeleid door de leesbare inhoud van een pagina. Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina. worden afgeleid door de leesbare inhoud van een pagina. Het staat al lang vast dat een lezer wordt afgeleid door de leesbare inhoud van een pagina. Het is een vaststaand feit dat een lezer wordt afgeleid door de leesbare inhoud van een pagina.</div>"."<div class='text-center mt-3'><a href='/de/angebotsanfragen' class='btn btn-lg btn-primary'><i class='fa-solid fa-send-o'></i> Absenden Angebotsanfragen</a></div>";

        $Topic->details_th = "<div style='text-align: center'><h2>ยินดีต้อนรับสู่เว็บไซต์ของเรา</h2>เป็นข้อเท็จจริงที่มีมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า ข้อเท็จจริงที่เป็นที่ยอมรับมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า เป็นความจริงที่เป็นที่ยอมรับมานานแล้วว่าผู้อ่านจะ ฟุ้งซ่านโดยเนื้อหาที่อ่านได้ของหน้า เป็นความจริงที่มีมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า ข้อเท็จจริงที่มีมาช้านานว่าผู้อ่านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า</div>"."<div class='text-center mt-3'><a href='/th/คำขอใบเสนอราคา' class='btn btn-lg btn-primary'><i class='fa-solid fa-send-o'></i> ส่ง คำขอใบเสนอราคา</a></div>";

        $Topic->details_br = "<div style='text-align: center'><h1>Bem-vindo ao nosso site</h1>É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página. É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página. ser distraído pelo conteúdo legível de uma página. É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página. É um fato há muito estabelecido que um leitor será distraído pelo conteúdo legível de uma página.</div>"."<div class='text-center mt-3'><a href='/br/solicitações-de-cotação' class='btn btn-lg btn-primary'><i class='fa-solid fa-send-o'></i> Enviar Solicitações de cotação</a></div>";

        $Topic->seo_url_slug_ar = "home";
        $Topic->seo_url_slug_en = "home";
        $Topic->seo_url_slug_ch = "home";
        $Topic->seo_url_slug_hi = "home";
        $Topic->seo_url_slug_es = "home";
        $Topic->seo_url_slug_ru = "home";
        $Topic->seo_url_slug_pt = "home";
        $Topic->seo_url_slug_fr = "home";
        $Topic->seo_url_slug_de = "home";
        $Topic->seo_url_slug_th = "home";
        $Topic->seo_url_slug_br = "home";
        $Topic->date = date('Y-m-d');
        $Topic->form_id = 14;
        $Topic->status = 1;
        $Topic->visits = 0;
        $Topic->section_id = 0;
        $Topic->created_by = 1;
        $Topic->save();

        // Homepage Block for landing page
        $TopicBlock = new TopicBlock;
        $TopicBlock->row_no = 1;
        $TopicBlock->topic_id = $Topic->id;
        $TopicBlock->block_name = "Banners Slider";
        $TopicBlock->type = 2;
        $TopicBlock->content = '{"title_en":"","desc_en":"","title_ar":"","desc_ar":"","title_ch":"","desc_ch":"","title_hi":"","desc_hi":"","title_es":"","desc_es":"","title_ru":"","desc_ru":"","title_pt":"","desc_pt":"","title_fr":"","desc_fr":"","title_de":"","desc_de":"","title_th":"","desc_th":"","title_br":"","desc_br":"","banner_area_id":"1","banner_style":"slider"}';
        $TopicBlock->title_status = 0;
        $TopicBlock->desc_status = 0;
        $TopicBlock->image_status = 0;
        $TopicBlock->divider_status = 0;
        $TopicBlock->more_btn_status = 0;
        $TopicBlock->bg_color = null;
        $TopicBlock->css_classes = null;
        $TopicBlock->status = 1;
        $TopicBlock->created_by = 1;
        $TopicBlock->save();
        $TopicBlock = new TopicBlock;
        $TopicBlock->row_no = 2;
        $TopicBlock->topic_id = $Topic->id;
        $TopicBlock->block_name = "Welcome Message";
        $TopicBlock->type = 0;
        $TopicBlock->content = '{"title_en":"","desc_en":"","details_en":'.json_encode($Topic->details_en).',"bg_en":null,"title_ar":"","desc_ar":"","details_ar":'.json_encode($Topic->details_ar).',"bg_ar":null,"title_ch":"","desc_ch":"","details_ch":'.json_encode($Topic->details_ch).',"bg_ch":null,"title_hi":"","desc_hi":"","details_hi":'.json_encode($Topic->details_hi).',"bg_hi":null,"title_es":"","desc_es":"","details_es":'.json_encode($Topic->details_es).',"bg_es":null,"title_ru":"","desc_ru":"","details_ru":'.json_encode($Topic->details_ru).',"bg_ru":null,"title_pt":"","desc_pt":"","details_pt":'.json_encode($Topic->details_pt).',"bg_pt":null,"title_fr":"","desc_fr":"","details_fr":'.json_encode($Topic->details_fr).',"bg_fr":null,"title_de":"","desc_de":"","details_de":'.json_encode($Topic->details_de).',"bg_de":null,"title_th":"","desc_th":"","details_th":'.json_encode($Topic->details_th).',"bg_th":null,"title_br":"","desc_br":"","details_br":'.json_encode($Topic->details_br).',"bg_br":null}';
        $TopicBlock->title_status = 0;
        $TopicBlock->desc_status = 0;
        $TopicBlock->image_status = 0;
        $TopicBlock->divider_status = 0;
        $TopicBlock->more_btn_status = 0;
        $TopicBlock->bg_color = null;
        $TopicBlock->css_classes = null;
        $TopicBlock->status = 1;
        $TopicBlock->created_by = 1;
        $TopicBlock->save();
        $TopicBlock = new TopicBlock;
        $TopicBlock->row_no = 3;
        $TopicBlock->topic_id = $Topic->id;
        $TopicBlock->block_name = "Text Banners";
        $TopicBlock->type = 2;
        $TopicBlock->content = '{"title_en":"","desc_en":"","title_ar":"","desc_ar":"","title_ch":"","desc_ch":"","title_hi":"","desc_hi":"","title_es":"","desc_es":"","title_ru":"","desc_ru":"","title_pt":"","desc_pt":"","title_fr":"","desc_fr":"","title_de":"","desc_de":"","title_th":"","desc_th":"","title_br":"","desc_br":"","banner_area_id":"2","banner_style":"banners"}';
        $TopicBlock->title_status = 0;
        $TopicBlock->desc_status = 0;
        $TopicBlock->image_status = 0;
        $TopicBlock->divider_status = 0;
        $TopicBlock->more_btn_status = 0;
        $TopicBlock->bg_color = null;
        $TopicBlock->css_classes = null;
        $TopicBlock->status = 1;
        $TopicBlock->created_by = 1;
        $TopicBlock->save();
        $TopicBlock = new TopicBlock;
        $TopicBlock->row_no = 4;
        $TopicBlock->topic_id = $Topic->id;
        $TopicBlock->block_name = "Latest Articles";
        $TopicBlock->type = 3;
        $TopicBlock->content = '{"title_en":"'.__("frontend.homeContents1Title",[],"en").'","desc_en":"'.__("frontend.homeContents1desc",[],"en").'","bg_en":null,"title_ar":"'.__("frontend.homeContents1Title",[],"ar").'","desc_ar":"'.__("frontend.homeContents1desc",[],"ar").'","bg_ar":null,"title_ch":"'.__("frontend.homeContents1Title",[],"ch").'","desc_ch":"'.__("frontend.homeContents1desc",[],"ch").'","bg_ch":null,"title_hi":"'.__("frontend.homeContents1Title",[],"hi").'","desc_hi":"'.__("frontend.homeContents1desc",[],"hi").'","bg_hi":null,"title_es":"'.__("frontend.homeContents1Title",[],"es").'","desc_es":"'.__("frontend.homeContents1desc",[],"es").'","bg_es":null,"title_ru":"'.__("frontend.homeContents1Title",[],"ru").'","desc_ru":"'.__("frontend.homeContents1desc",[],"ru").'","bg_ru":null,"title_pt":"'.__("frontend.homeContents1Title",[],"pt").'","desc_pt":"'.__("frontend.homeContents1desc",[],"pt").'","bg_pt":null,"title_fr":"'.__("frontend.homeContents1Title",[],"fr").'","desc_fr":"'.__("frontend.homeContents1desc",[],"fr").'","bg_fr":null,"title_de":"'.__("frontend.homeContents1Title",[],"de").'","desc_de":"'.__("frontend.homeContents1desc",[],"de").'","bg_de":null,"title_th":"'.__("frontend.homeContents1Title",[],"th").'","desc_th":"'.__("frontend.homeContents1desc",[],"th").'","bg_th":null,"title_br":"'.__("frontend.homeContents1Title",[],"br").'","desc_br":"'.__("frontend.homeContents1desc",[],"br").'","bg_br":null,"module_id":"7","category_ids":null,"records_count":"12","records_order":"1","view_style":"Carousel"}';
        $TopicBlock->title_status = 1;
        $TopicBlock->desc_status = 1;
        $TopicBlock->image_status = 0;
        $TopicBlock->divider_status = 0;
        $TopicBlock->more_btn_status = 1;
        $TopicBlock->bg_color = null;
        $TopicBlock->css_classes = "section-bg";
        $TopicBlock->status = 1;
        $TopicBlock->created_by = 1;
        $TopicBlock->save();
        $TopicBlock = new TopicBlock;
        $TopicBlock->row_no = 5;
        $TopicBlock->topic_id = $Topic->id;
        $TopicBlock->block_name = "Our Staffs";
        $TopicBlock->type = 3;
        $TopicBlock->content = '{"title_en":"'.__("frontend.homeStaffTitle",[],"en").'","desc_en":"'.__("frontend.homeStaffDesc",[],"en").'","bg_en":null,"title_ar":"'.__("frontend.homeStaffTitle",[],"ar").'","desc_ar":"'.__("frontend.homeStaffDesc",[],"ar").'","bg_ar":null,"title_ch":"'.__("frontend.homeStaffTitle",[],"ch").'","desc_ch":"'.__("frontend.homeStaffDesc",[],"ch").'","bg_ch":null,"title_hi":"'.__("frontend.homeStaffTitle",[],"hi").'","desc_hi":"'.__("frontend.homeStaffDesc",[],"hi").'","bg_hi":null,"title_es":"'.__("frontend.homeStaffTitle",[],"es").'","desc_es":"'.__("frontend.homeStaffDesc",[],"es").'","bg_es":null,"title_ru":"'.__("frontend.homeStaffTitle",[],"ru").'","desc_ru":"'.__("frontend.homeStaffDesc",[],"ru").'","bg_ru":null,"title_pt":"'.__("frontend.homeStaffTitle",[],"pt").'","desc_pt":"'.__("frontend.homeStaffDesc",[],"pt").'","bg_pt":null,"title_fr":"'.__("frontend.homeStaffTitle",[],"fr").'","desc_fr":"'.__("frontend.homeStaffDesc",[],"fr").'","bg_fr":null,"title_de":"'.__("frontend.homeStaffTitle",[],"de").'","desc_de":"'.__("frontend.homeStaffDesc",[],"de").'","bg_de":null,"title_th":"'.__("frontend.homeStaffTitle",[],"th").'","desc_th":"'.__("frontend.homeStaffDesc",[],"th").'","bg_th":null,"title_br":"'.__("frontend.homeStaffTitle",[],"br").'","desc_br":"'.__("frontend.homeStaffDesc",[],"br").'","bg_br":null,"module_id":"12","category_ids":null,"records_count":"6","records_order":"1","view_style":"Staff"}';
        $TopicBlock->title_status = 1;
        $TopicBlock->desc_status = 1;
        $TopicBlock->image_status = 0;
        $TopicBlock->divider_status = 0;
        $TopicBlock->more_btn_status = 1;
        $TopicBlock->bg_color = null;
        $TopicBlock->css_classes = null;
        $TopicBlock->status = 1;
        $TopicBlock->created_by = 1;
        $TopicBlock->save();
        $TopicBlock = new TopicBlock;
        $TopicBlock->row_no = 6;
        $TopicBlock->topic_id = $Topic->id;
        $TopicBlock->block_name = "Photo Gallary";
        $TopicBlock->type = 3;
        $TopicBlock->content = '{"title_en":"'.__("frontend.homeContents2Title",[],"en").'","desc_en":"'.__("frontend.homeContents2desc",[],"en").'","bg_en":null,"title_ar":"'.__("frontend.homeContents2Title",[],"ar").'","desc_ar":"'.__("frontend.homeContents2desc",[],"ar").'","bg_ar":null,"title_ch":"'.__("frontend.homeContents2Title",[],"ch").'","desc_ch":"'.__("frontend.homeContents2desc",[],"ch").'","bg_ch":null,"title_hi":"'.__("frontend.homeContents2Title",[],"hi").'","desc_hi":"'.__("frontend.homeContents2desc",[],"hi").'","bg_hi":null,"title_es":"'.__("frontend.homeContents2Title",[],"es").'","desc_es":"'.__("frontend.homeContents2desc",[],"es").'","bg_es":null,"title_ru":"'.__("frontend.homeContents2Title",[],"ru").'","desc_ru":"'.__("frontend.homeContents2desc",[],"ru").'","bg_ru":null,"title_pt":"'.__("frontend.homeContents2Title",[],"pt").'","desc_pt":"'.__("frontend.homeContents2desc",[],"pt").'","bg_pt":null,"title_fr":"'.__("frontend.homeContents2Title",[],"fr").'","desc_fr":"'.__("frontend.homeContents2desc",[],"fr").'","bg_fr":null,"title_de":"'.__("frontend.homeContents2Title",[],"de").'","desc_de":"'.__("frontend.homeContents2desc",[],"de").'","bg_de":null,"title_th":"'.__("frontend.homeContents2Title",[],"th").'","desc_th":"'.__("frontend.homeContents2desc",[],"th").'","bg_th":null,"title_br":"'.__("frontend.homeContents2Title",[],"br").'","desc_br":"'.__("frontend.homeContents2desc",[],"br").'","bg_br":null,"module_id":"4","category_ids":null,"records_count":"12","records_order":"1","view_style":"Gallery"}';
        $TopicBlock->title_status = 1;
        $TopicBlock->desc_status = 1;
        $TopicBlock->image_status = 0;
        $TopicBlock->divider_status = 0;
        $TopicBlock->more_btn_status = 1;
        $TopicBlock->bg_color = null;
        $TopicBlock->css_classes = null;
        $TopicBlock->status = 1;
        $TopicBlock->created_by = 1;
        $TopicBlock->save();
        $TopicBlock = new TopicBlock;
        $TopicBlock->row_no = 7;
        $TopicBlock->topic_id = $Topic->id;
        $TopicBlock->block_name = "FAQ";
        $TopicBlock->type = 3;
        $TopicBlock->content = '{"title_en":"'.__("frontend.homeFAQTitle",[],"en").'","desc_en":"'.__("frontend.homeFAQDesc",[],"en").'","bg_en":null,"title_ar":"'.__("frontend.homeFAQTitle",[],"ar").'","desc_ar":"'.__("frontend.homeFAQDesc",[],"ar").'","bg_ar":null,"title_ch":"'.__("frontend.homeFAQTitle",[],"ch").'","desc_ch":"'.__("frontend.homeFAQDesc",[],"ch").'","bg_ch":null,"title_hi":"'.__("frontend.homeFAQTitle",[],"hi").'","desc_hi":"'.__("frontend.homeFAQDesc",[],"hi").'","bg_hi":null,"title_es":"'.__("frontend.homeFAQTitle",[],"es").'","desc_es":"'.__("frontend.homeFAQDesc",[],"es").'","bg_es":null,"title_ru":"'.__("frontend.homeFAQTitle",[],"ru").'","desc_ru":"'.__("frontend.homeFAQDesc",[],"ru").'","bg_ru":null,"title_pt":"'.__("frontend.homeFAQTitle",[],"pt").'","desc_pt":"'.__("frontend.homeFAQDesc",[],"pt").'","bg_pt":null,"title_fr":"'.__("frontend.homeFAQTitle",[],"fr").'","desc_fr":"'.__("frontend.homeFAQDesc",[],"fr").'","bg_fr":null,"title_de":"'.__("frontend.homeFAQTitle",[],"de").'","desc_de":"'.__("frontend.homeFAQDesc",[],"de").'","bg_de":null,"title_th":"'.__("frontend.homeFAQTitle",[],"th").'","desc_th":"'.__("frontend.homeFAQDesc",[],"th").'","bg_th":null,"title_br":"'.__("frontend.homeFAQTitle",[],"br").'","desc_br":"'.__("frontend.homeFAQDesc",[],"br").'","bg_br":null,"module_id":"10","category_ids":null,"records_count":"12","records_order":"1","view_style":"FAQ"}';
        $TopicBlock->title_status = 1;
        $TopicBlock->desc_status = 1;
        $TopicBlock->image_status = 0;
        $TopicBlock->divider_status = 0;
        $TopicBlock->more_btn_status = 1;
        $TopicBlock->bg_color = null;
        $TopicBlock->css_classes = null;
        $TopicBlock->status = 1;
        $TopicBlock->created_by = 1;
        $TopicBlock->save();
        $TopicBlock = new TopicBlock;
        $TopicBlock->row_no = 8;
        $TopicBlock->topic_id = $Topic->id;
        $TopicBlock->block_name = "Testimonials";
        $TopicBlock->type = 3;
        $TopicBlock->content = '{"title_en":"'.__("frontend.homeTestimonialsTitle",[],"en").'","desc_en":"'.__("frontend.homeTestimonialsDesc",[],"en").'","bg_en":null,"title_ar":"'.__("frontend.homeTestimonialsTitle",[],"ar").'","desc_ar":"'.__("frontend.homeTestimonialsDesc",[],"ar").'","bg_ar":null,"title_ch":"'.__("frontend.homeTestimonialsTitle",[],"ch").'","desc_ch":"'.__("frontend.homeTestimonialsDesc",[],"ch").'","bg_ch":null,"title_hi":"'.__("frontend.homeTestimonialsTitle",[],"hi").'","desc_hi":"'.__("frontend.homeTestimonialsDesc",[],"hi").'","bg_hi":null,"title_es":"'.__("frontend.homeTestimonialsTitle",[],"es").'","desc_es":"'.__("frontend.homeTestimonialsDesc",[],"es").'","bg_es":null,"title_ru":"'.__("frontend.homeTestimonialsTitle",[],"ru").'","desc_ru":"'.__("frontend.homeTestimonialsDesc",[],"ru").'","bg_ru":null,"title_pt":"'.__("frontend.homeTestimonialsTitle",[],"pt").'","desc_pt":"'.__("frontend.homeTestimonialsDesc",[],"pt").'","bg_pt":null,"title_fr":"'.__("frontend.homeTestimonialsTitle",[],"fr").'","desc_fr":"'.__("frontend.homeTestimonialsDesc",[],"fr").'","bg_fr":null,"title_de":"'.__("frontend.homeTestimonialsTitle",[],"de").'","desc_de":"'.__("frontend.homeTestimonialsDesc",[],"de").'","bg_de":null,"title_th":"'.__("frontend.homeTestimonialsTitle",[],"th").'","desc_th":"'.__("frontend.homeTestimonialsDesc",[],"th").'","bg_th":null,"title_br":"'.__("frontend.homeTestimonialsTitle",[],"br").'","desc_br":"'.__("frontend.homeTestimonialsDesc",[],"br").'","bg_br":null,"module_id":"11","category_ids":null,"records_count":"12","records_order":"1","view_style":"Testimonials"}';
        $TopicBlock->title_status = 1;
        $TopicBlock->desc_status = 1;
        $TopicBlock->image_status = 0;
        $TopicBlock->divider_status = 0;
        $TopicBlock->more_btn_status = 0;
        $TopicBlock->bg_color = null;
        $TopicBlock->css_classes = null;
        $TopicBlock->status = 1;
        $TopicBlock->created_by = 1;
        $TopicBlock->save();
        $TopicBlock = new TopicBlock;
        $TopicBlock->row_no = 9;
        $TopicBlock->topic_id = $Topic->id;
        $TopicBlock->block_name = "Partners";
        $TopicBlock->type = 3;
        $TopicBlock->content = '{"title_en":"'.__("frontend.partners",[],"en").'","desc_en":"'.__("frontend.partnersMsg",[],"en").'","bg_en":null,"title_ar":"'.__("frontend.partners",[],"ar").'","desc_ar":"'.__("frontend.partnersMsg",[],"ar").'","bg_ar":null,"title_ch":"'.__("frontend.partners",[],"ch").'","desc_ch":"'.__("frontend.partnersMsg",[],"ch").'","bg_ch":null,"title_hi":"'.__("frontend.partners",[],"hi").'","desc_hi":"'.__("frontend.partnersMsg",[],"hi").'","bg_hi":null,"title_es":"'.__("frontend.partners",[],"es").'","desc_es":"'.__("frontend.partnersMsg",[],"es").'","bg_es":null,"title_ru":"'.__("frontend.partners",[],"ru").'","desc_ru":"'.__("frontend.partnersMsg",[],"ru").'","bg_ru":null,"title_pt":"'.__("frontend.partners",[],"pt").'","desc_pt":"'.__("frontend.partnersMsg",[],"pt").'","bg_pt":null,"title_fr":"'.__("frontend.partners",[],"fr").'","desc_fr":"'.__("frontend.partnersMsg",[],"fr").'","bg_fr":null,"title_de":"'.__("frontend.partners",[],"de").'","desc_de":"'.__("frontend.partnersMsg",[],"de").'","bg_de":null,"title_th":"'.__("frontend.partners",[],"th").'","desc_th":"'.__("frontend.partnersMsg",[],"th").'","bg_th":null,"title_br":"'.__("frontend.partners",[],"br").'","desc_br":"'.__("frontend.partnersMsg",[],"br").'","bg_br":null,"module_id":"9","category_ids":null,"records_count":"12","records_order":"1","view_style":"Partners"}';
        $TopicBlock->title_status = 1;
        $TopicBlock->desc_status = 1;
        $TopicBlock->image_status = 0;
        $TopicBlock->divider_status = 0;
        $TopicBlock->more_btn_status = 0;
        $TopicBlock->bg_color = null;
        $TopicBlock->css_classes = "section-bg";
        $TopicBlock->status = 1;
        $TopicBlock->created_by = 1;
        $TopicBlock->save();


        // Services

        $Topic = new Topic();
        $Topic->row_no = 1;
        $Topic->webmaster_id = 2;

        $Topic->title_ar = "كتابة وإدارة المحتوى";
        $Topic->title_en = "Content writing and SEO";
        $Topic->title_ch = "内容写作和搜索引擎优化";
        $Topic->title_hi = "सामग्री लेखन और एसईओ";
        $Topic->title_es = "Redacción de contenidos y SEO";
        $Topic->title_ru = "Написание контента и SEO";
        $Topic->title_pt = "Redação de conteúdo e SEO";
        $Topic->title_fr = "Rédaction de contenu et référencement";
        $Topic->title_de = "Schreiben von Inhalten und SEO";
        $Topic->title_th = "การเขียนเนื้อหาและ SEO";
        $Topic->title_br = "Redação de conteúdo e SEO";


        $Topic->details_ar = $details_ar;
        $Topic->details_en = $details_en;
        $Topic->details_ch = $details_ch;
        $Topic->details_hi = $details_hi;
        $Topic->details_es = $details_es;
        $Topic->details_ru = $details_ru;
        $Topic->details_pt = $details_pt;
        $Topic->details_fr = $details_fr;
        $Topic->details_de = $details_de;
        $Topic->details_th = $details_th;
        $Topic->details_br = $details_br;

        $Topic->seo_url_slug_ar = "كتابة-وإدارة-المحتوى";
        $Topic->seo_url_slug_en = "Content-writing-and-SEO";
        $Topic->seo_url_slug_ch = "内容写作和搜索引擎优化";
        $Topic->seo_url_slug_hi = "सामग्र-ीलेखन-और-एसईओ";
        $Topic->seo_url_slug_es = "Redacción-de-contenidos-y-SEO";
        $Topic->seo_url_slug_ru = "Написание-контента-и-SEO";
        $Topic->seo_url_slug_pt = "Redação-de-conteúdo-e-SEO";
        $Topic->seo_url_slug_fr = "Rédaction-de-contenu-et-référencement";
        $Topic->seo_url_slug_de = "Schreiben-von-Inhalten-und-SEO";
        $Topic->seo_url_slug_th = "การเขียนเนื้อหาและ-SEO";
        $Topic->seo_url_slug_br = "Redação-conteúdo-e-SEO";

        $Topic->date = date('Y-m-d');
        $Topic->photo_file = "default.png";
        $Topic->icon = "fa fa-align-left";
        $Topic->status = 1;
        $Topic->visits = 0;
        $Topic->section_id = 0;
        $Topic->created_by = 1;
        $Topic->save();

        $Topic = new Topic();
        $Topic->row_no = 2;
        $Topic->webmaster_id = 2;

        $Topic->title_ar = "خدمات ترجمة النصوص";
        $Topic->title_en = "translation service";
        $Topic->title_ch = "翻译服务";
        $Topic->title_hi = "अनुवादन सेवा";
        $Topic->title_es = "servicio de traduccion";
        $Topic->title_ru = "услуги перевода";
        $Topic->title_pt = "serviço de tradução";
        $Topic->title_fr = "service de traduction";
        $Topic->title_de = "übersetzungsdienst";
        $Topic->title_th = "บริการแปล";
        $Topic->title_br = "serviço de tradução";


        $Topic->details_ar = $details_ar;
        $Topic->details_en = $details_en;
        $Topic->details_ch = $details_ch;
        $Topic->details_hi = $details_hi;
        $Topic->details_es = $details_es;
        $Topic->details_ru = $details_ru;
        $Topic->details_pt = $details_pt;
        $Topic->details_fr = $details_fr;
        $Topic->details_de = $details_de;
        $Topic->details_th = $details_th;
        $Topic->details_br = $details_br;

        $Topic->seo_url_slug_ar = "خدمات-ترجمة-النصوص";
        $Topic->seo_url_slug_en = "translation-service";
        $Topic->seo_url_slug_ch = "翻译服务";
        $Topic->seo_url_slug_hi = "अनुवादन-सेवा";
        $Topic->seo_url_slug_es = "servicio- de-traduccion";
        $Topic->seo_url_slug_ru = "услуги-перевода";
        $Topic->seo_url_slug_pt = "serviço-de-tradução";
        $Topic->seo_url_slug_fr = "service-de-traduction";
        $Topic->seo_url_slug_de = "übersetzungsdienst";
        $Topic->seo_url_slug_th = "บริการแปล";
        $Topic->seo_url_slug_br = "serviço-tradução";

        $Topic->date = date('Y-m-d');
        $Topic->photo_file = "default.png";
        $Topic->icon = "fa fa-flag-checkered";
        $Topic->status = 1;
        $Topic->visits = 0;
        $Topic->section_id = 0;
        $Topic->created_by = 1;
        $Topic->save();

        $Topic = new Topic();
        $Topic->row_no = 3;
        $Topic->webmaster_id = 2;

        $Topic->title_ar = "إدارة الحملات التسويقية";
        $Topic->title_en = "Marketing & campaigns";
        $Topic->title_ch = "营销活动管理";
        $Topic->title_hi = "विपणन अभियान प्रबंधन";
        $Topic->title_es = "Gestión de campañas";
        $Topic->title_ru = "Управление маркетинговыми";
        $Topic->title_pt = "Gestão de campanhas";
        $Topic->title_fr = "campagnes marketing";
        $Topic->title_de = "Marketingkampagnen";
        $Topic->title_th = "การจัดการแคมเปญการตลาด";
        $Topic->title_br = "Gestão de campanhas";

        $Topic->details_ar = $details_ar;
        $Topic->details_en = $details_en;
        $Topic->details_ch = $details_ch;
        $Topic->details_hi = $details_hi;
        $Topic->details_es = $details_es;
        $Topic->details_ru = $details_ru;
        $Topic->details_pt = $details_pt;
        $Topic->details_fr = $details_fr;
        $Topic->details_de = $details_de;
        $Topic->details_th = $details_th;
        $Topic->details_br = $details_br;

        $Topic->seo_url_slug_ar = "إدارة-الحملات-التسويقية";
        $Topic->seo_url_slug_en = "marketing-campaigns-management";
        $Topic->seo_url_slug_ch = "营销活动管理";
        $Topic->seo_url_slug_hi = "विपणन-अभियान-प्रबंधन";
        $Topic->seo_url_slug_es = "gestión-de-campañas-de-marketing.";
        $Topic->seo_url_slug_ru = "управление-маркетинговыми-кампаниями";
        $Topic->seo_url_slug_pt = "gestão-de-campanhas-de-marketing";
        $Topic->seo_url_slug_fr = "gestion-des-campagnes-marketing";
        $Topic->seo_url_slug_de = "verwaltung-von-marketingkampagnen";
        $Topic->seo_url_slug_th = "การจัดการแคมเปญการตลาด";
        $Topic->seo_url_slug_br = "gestão-campanhas-de-marketing";

        $Topic->date = date('Y-m-d');
        $Topic->photo_file = "default.png";
        $Topic->icon = "fa fa-shopping-bag";
        $Topic->status = 1;
        $Topic->visits = 0;
        $Topic->section_id = 0;
        $Topic->created_by = 1;
        $Topic->save();

        $Topic = new Topic();
        $Topic->row_no = 4;
        $Topic->webmaster_id = 2;

        $Topic->title_ar = "تصميم المواقع الإلكترونية";
        $Topic->title_en = "Website design";
        $Topic->title_ch = "网站设计";
        $Topic->title_hi = "वेबसाइट डिज़ाइन";
        $Topic->title_es = "Diseño de páginas web";
        $Topic->title_ru = "Дизайн сайта";
        $Topic->title_pt = "Design do site";
        $Topic->title_fr = "Conception de sites Web";
        $Topic->title_de = "Website design";
        $Topic->title_th = "การออกแบบเว็บไซต์";
        $Topic->title_br = "Conception de sites Web";

        $Topic->details_ar = $details_ar;
        $Topic->details_en = $details_en;
        $Topic->details_ch = $details_ch;
        $Topic->details_hi = $details_hi;
        $Topic->details_es = $details_es;
        $Topic->details_ru = $details_ru;
        $Topic->details_pt = $details_pt;
        $Topic->details_fr = $details_fr;
        $Topic->details_de = $details_de;
        $Topic->details_th = $details_th;
        $Topic->details_br = $details_br;

        $Topic->seo_url_slug_ar = "تصميم-المواقع-الإلكترونية";
        $Topic->seo_url_slug_en = "Website-design";
        $Topic->seo_url_slug_ch = "网站设计";
        $Topic->seo_url_slug_hi = "वेबसाइट-डिज़ाइन";
        $Topic->seo_url_slug_es = "Diseño-de-páginas-web";
        $Topic->seo_url_slug_ru = "Дизайн-сайта";
        $Topic->seo_url_slug_pt = "Design-do-site";
        $Topic->seo_url_slug_fr = "Conception-de-sites-Web";
        $Topic->seo_url_slug_de = "Websites-design";
        $Topic->seo_url_slug_th = "การออกแบบเว็บไซต์";
        $Topic->seo_url_slug_br = "Conception-de-sites-Web";

        $Topic->date = date('Y-m-d');
        $Topic->photo_file = "default.png";
        $Topic->icon = "fa fa-object-group";
        $Topic->status = 1;
        $Topic->visits = 0;
        $Topic->section_id = 0;
        $Topic->created_by = 1;
        $Topic->save();

        $Topic = new Topic();
        $Topic->row_no = 5;
        $Topic->webmaster_id = 2;

        $Topic->title_ar = "تصميم العروض التقديمية";
        $Topic->title_en = "Presentation design";
        $Topic->title_ch = "演示设计";
        $Topic->title_hi = "प्रस्तुति डिज़ाइन";
        $Topic->title_es = "Diseño de presentación";
        $Topic->title_ru = "Дизайн презентации";
        $Topic->title_pt = "Design de apresentação";
        $Topic->title_fr = "Conception de présentation";
        $Topic->title_de = "Präsentationsdesign";
        $Topic->title_th = "การออกแบบการนำเสนอ";
        $Topic->title_br = "Design de apresentação";

        $Topic->details_ar = $details_ar;
        $Topic->details_en = $details_en;
        $Topic->details_ch = $details_ch;
        $Topic->details_hi = $details_hi;
        $Topic->details_es = $details_es;
        $Topic->details_ru = $details_ru;
        $Topic->details_pt = $details_pt;
        $Topic->details_fr = $details_fr;
        $Topic->details_de = $details_de;
        $Topic->details_th = $details_th;
        $Topic->details_br = $details_br;

        $Topic->seo_url_slug_ar = "تصميم-العروض-التقديمية";
        $Topic->seo_url_slug_en = "Presentation-design";
        $Topic->seo_url_slug_ch = "演示设计";
        $Topic->seo_url_slug_hi = "प्रस्तुति-डिज़ाइन";
        $Topic->seo_url_slug_es = "Diseño-de-presentación";
        $Topic->seo_url_slug_ru = "Дизайн-презентации";
        $Topic->seo_url_slug_pt = "Design-de-apresentação";
        $Topic->seo_url_slug_fr = "Conception-de-présentation";
        $Topic->seo_url_slug_de = "Präsentationsdesign";
        $Topic->seo_url_slug_th = "การออกแบบการนำเสนอ";
        $Topic->seo_url_slug_br = "Design-apresentação";

        $Topic->date = date('Y-m-d');
        $Topic->photo_file = "default.png";
        $Topic->icon = "fa fa-desktop";
        $Topic->status = 1;
        $Topic->visits = 0;
        $Topic->section_id = 0;
        $Topic->created_by = 1;
        $Topic->save();


        $titles = [
            [
                "ar" => "هناك حقيقة مثبتة منذ زمن طويل وهي أن المحتوى المقروء",
                "en" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit",
                "ch" => "无可否认，当读当读者在浏览一个页面的排版时",
                "hi" => "यह एक लंबा स्थापित तथ्य है कि जब एक पाठक एक पृष्ठ के खाखेतथ्य है कि ",
                "es" => "Es un hecho establecido re demasiado tiempo que",
                "ru" => "Давно выяснено, что при оценке дизайна и композиции",
                "pt" => "texto modelo da indústria tipográfica e de impressão",
                "fr" => "On sait depuis longtemps que travailler avec du texte lisible",
                "de" => "einfacher Demo-Text für die Print- und Schriftindustrie",
                "th" => "านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า ่เป็นที่ยอมรับมาช้านานว่าผู้",
                "br" => "Tem raízes numa peça de literatura clássica em Latim",
            ],
            [
                "ar" => "هنالك العديد من الأنواع المتوفرة لنصوص لوريم إيبسوم",
                "en" => "Curabitur vitae leo vitae ipsum varius laoreet",
                "ch" => "无可否认，当读者在浏览一个页面的排版时 当",
                "hi" => "यह एक लंबा स्थापित तथ्य है कि जब एक एक पृष्ठ के खाखे",
                "es" => "Es un hecho establecido hace de demasiado tiempo que",
                "ru" => "Давно выяснено, что при оценке дизайна композиции",
                "pt" => "texto modelo da indústria tipográfica e de impressão de",
                "fr" => "On sait depuis avec du texte lisible et contenant du sens est",
                "de" => "einfacher Demo-Text für die für die Print- und Schriftindustrie",
                "th" => "านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า ข้อเท็จจริงที่เป็นที่ยอมรับมาช้า",
                "br" => "Tem raízes numa peça de literatura clássica em Latim em",
            ],
            [
                "ar" => "خلافاَ للإعتقاد السائد فإن لوريم إيبسوم ليس نصاَ عشوائياً",
                "en" => "Vivamus a sapien vitae leo ultricies euismod eu at erat",
                "ch" => "无可否认，当读者在浏览 页面的排版时",
                "hi" => "यह एक लंबा स्थापित तथ्य है कि एक पाठक एक पृष्ठ के खाखे",
                "es" => "Es un hecho establecido we demasiado tiempo que",
                "ru" => "Давно выяснено, при оценке дизайна и композиции",
                "pt" => "texto modelo da indústria tipográfica e impressão de",
                "fr" => "On que travailler avec du texte lisible et contenant du sens est",
                "de" => "einfacher Demo-Text für die Print- und Print- und Schriftindustrie",
                "th" => "านจะถูกรบกวนโดยเ่อ่านได้ของหน้า ข้อเท็จจริงที่เป็นที่ยอมรับมาช้านานว่าผู้",
                "br" => "Tem raízes numa peça literatura clássica em Latim em",
            ],
            [
                "ar" => "كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر",
                "en" => "Donec sit amet sem non mauris lacinia blandit non ut sem",
                "ch" => "无可否认，当读者在浏览一个页面的排版时 一个",
                "hi" => "यह एक लंबा स्थापित जब एक पाठक एक पृष्ठ के खाखे जब एक",
                "es" => "Es un hecho establecido de demasiado tiempo que",
                "ru" => "Давно выяснено, что дизайна и композиции",
                "pt" => "texto modelo da indústria tipográfica impressão de",
                "fr" => "longtemps que travailler avec du texte lisible et contenant",
                "de" => "und einfacher Demo-Text für die Print- und Schriftindustrie",
                "th" => "านจะถูกรบกวนโ ที่อ่านได้ของหน้า ข้อเท็จจริงที่เป็นที่ยอมรับมาช้านานว่าผู้",
                "br" => "Tem raízes numa peça rw literatura clássica em Latim",
            ],
            [
                "ar" => "إن كنت تريد أن تستخدم نص لوريم إيبسوم ما، عليك أن تتحقق أولاً",
                "en" => "Proin ultrices sem at magna porttitor dignissim",
                "ch" => "无可否认，当读者在浏览一个页面的排版时 页面",
                "hi" => "Proin ultrices sem at magna porttitor dignissim magna",
                "es" => "Es un hecho establecido hace demasiado tiempo que tiempo",
                "ru" => "Давно выяснено, что при оценке дизайна и композиции оценке",
                "pt" => "texto modelo da indústria tipográfica e de impressão da",
                "fr" => "On sait depuis avec du texte lisible et contenant du sens est",
                "de" => "und einfacher Demo-Text für die und Schriftindustrie",
                "th" => "านจะถูกรบกวนโดยเนื้อหาที่อ่านได้ของหน้า ข้อเท็จจริงที่ อมรับมาช้านานว่าผู้",
                "br" => "Tem raízes numa de literatura clássica em Latim em",
            ],
            [
                "ar" => " التركيز على الشكل الخارجي للنص أو شكل الصفحة التي يقرأها",
                "en" => "Cras vel neque nec mauris luctus convallis vel vitae ante",
                "ch" => "无可否认，当读者在浏览一个页面的排版时 览",
                "hi" => "यह एक लंबा स्थापित तथ्य है कि जब एक पाठक एक पृष्ठ के खाखे पृष्ठ",
                "es" => "Es un hecho establecido hace demasiado tiempo que hecho",
                "ru" => "Давно, что при оценке дизайна и композиции оценке",
                "pt" => "texto da indústria tipográfica e de impressão da",
                "fr" => "On sait depuis longtemps que travailler et contenant du sens est",
                "de" => "die und einfacher Demo-Text für die Print- und Schriftindustrie",
                "th" => "านจะถูกรบกว โดยเนื้อหาที่อ่า ได้ของหน้า ข้อเท็จจริงที่เป็นที่ยอมรับมาช้านานว่าผู้",
                "br" => "Tem raízes de numa peça de literatura clássica em Latim",
            ],
        ];

        // news
        for ($i = 0; $i <= 11; $i++) {
            $sel = $i;
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            $webmaster_id = 3;

            $Topic = new Topic();
            $Topic->row_no = 0;
            $Topic->webmaster_id = $webmaster_id;

            $Topic->title_ar = @$titles[$sel]["ar"];
            $Topic->title_en = @$titles[$sel]["en"];
            $Topic->title_ch = @$titles[$sel]["ch"];
            $Topic->title_hi = @$titles[$sel]["hi"];
            $Topic->title_es = @$titles[$sel]["es"];
            $Topic->title_ru = @$titles[$sel]["ru"];
            $Topic->title_pt = @$titles[$sel]["pt"];
            $Topic->title_fr = @$titles[$sel]["fr"];
            $Topic->title_de = @$titles[$sel]["de"];
            $Topic->title_th = @$titles[$sel]["th"];
            $Topic->title_br = @$titles[$sel]["br"];

            $Topic->details_ar = $details_ar;
            $Topic->details_en = $details_en;
            $Topic->details_ch = $details_ch;
            $Topic->details_hi = $details_hi;
            $Topic->details_es = $details_es;
            $Topic->details_ru = $details_ru;
            $Topic->details_pt = $details_pt;
            $Topic->details_fr = $details_fr;
            $Topic->details_de = $details_de;
            $Topic->details_th = $details_th;
            $Topic->details_br = $details_br;

            $Topic->seo_url_slug_ar = str_replace(" ", "-", @$titles[$sel]["ar"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_en = str_replace(" ", "-", @$titles[$sel]["en"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ch = str_replace(" ", "-", @$titles[$sel]["ch"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_hi = str_replace(" ", "-", @$titles[$sel]["hi"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_es = str_replace(" ", "-", @$titles[$sel]["es"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ru = str_replace(" ", "-", @$titles[$sel]["ru"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_pt = str_replace(" ", "-", @$titles[$sel]["pt"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_fr = str_replace(" ", "-", @$titles[$sel]["fr"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_de = str_replace(" ", "-", @$titles[$sel]["de"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_th = str_replace(" ", "-", @$titles[$sel]["th"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_br = str_replace(" ", "-", @$titles[$sel]["br"]) . "-" . $webmaster_id . $i;

            $Topic->date = date('Y-m-d');
            $Topic->photo_file = "default.png";
            $Topic->icon = "";
            $Topic->status = 1;
            $Topic->visits = 0;
            $Topic->section_id = 0;
            $Topic->created_by = 1;
            $Topic->save();
        }

        // photos
        for ($i = 0; $i <= 5; $i++) {
            $sel = $i;
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            $webmaster_id = 4;

            $Topic = new Topic();
            $Topic->row_no = 0;
            $Topic->webmaster_id = $webmaster_id;

            $Topic->title_ar = @$titles[$sel]["ar"];
            $Topic->title_en = @$titles[$sel]["en"];
            $Topic->title_ch = @$titles[$sel]["ch"];
            $Topic->title_hi = @$titles[$sel]["hi"];
            $Topic->title_es = @$titles[$sel]["es"];
            $Topic->title_ru = @$titles[$sel]["ru"];
            $Topic->title_pt = @$titles[$sel]["pt"];
            $Topic->title_fr = @$titles[$sel]["fr"];
            $Topic->title_de = @$titles[$sel]["de"];
            $Topic->title_th = @$titles[$sel]["th"];
            $Topic->title_br = @$titles[$sel]["br"];

            $Topic->details_ar = $details_ar;
            $Topic->details_en = $details_en;
            $Topic->details_ch = $details_ch;
            $Topic->details_hi = $details_hi;
            $Topic->details_es = $details_es;
            $Topic->details_ru = $details_ru;
            $Topic->details_pt = $details_pt;
            $Topic->details_fr = $details_fr;
            $Topic->details_de = $details_de;
            $Topic->details_th = $details_th;
            $Topic->details_br = $details_br;

            $Topic->seo_url_slug_ar = str_replace(" ", "-", @$titles[$sel]["ar"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_en = str_replace(" ", "-", @$titles[$sel]["en"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ch = str_replace(" ", "-", @$titles[$sel]["ch"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_hi = str_replace(" ", "-", @$titles[$sel]["hi"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_es = str_replace(" ", "-", @$titles[$sel]["es"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ru = str_replace(" ", "-", @$titles[$sel]["ru"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_pt = str_replace(" ", "-", @$titles[$sel]["pt"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_fr = str_replace(" ", "-", @$titles[$sel]["fr"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_de = str_replace(" ", "-", @$titles[$sel]["de"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_th = str_replace(" ", "-", @$titles[$sel]["th"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_br = str_replace(" ", "-", @$titles[$sel]["br"]) . "-" . $webmaster_id . $i;

            $Topic->date = date('Y-m-d');
            $Topic->photo_file = "default.png";
            $Topic->icon = "";
            $Topic->status = 1;
            $Topic->visits = 0;
            $Topic->section_id = 0;
            $Topic->created_by = 1;
            $Topic->save();

            for ($ii = 0; $ii <= 7; $ii++) {
                $Photo = new Photo;
                $Photo->row_no = 0;
                $Photo->file = "default.png";
                $Photo->title = @$titles[$sel]["en"];
                $Photo->topic_id = $Topic->id;
                $Photo->created_by = 1;
                $Photo->save();
            }
        }

        // video
        for ($i = 0; $i <= 11; $i++) {
            $sel = $i;
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            $webmaster_id = 5;

            $Topic = new Topic();
            $Topic->row_no = 0;
            $Topic->webmaster_id = $webmaster_id;

            $Topic->title_ar = @$titles[$sel]["ar"];
            $Topic->title_en = @$titles[$sel]["en"];
            $Topic->title_ch = @$titles[$sel]["ch"];
            $Topic->title_hi = @$titles[$sel]["hi"];
            $Topic->title_es = @$titles[$sel]["es"];
            $Topic->title_ru = @$titles[$sel]["ru"];
            $Topic->title_pt = @$titles[$sel]["pt"];
            $Topic->title_fr = @$titles[$sel]["fr"];
            $Topic->title_de = @$titles[$sel]["de"];
            $Topic->title_th = @$titles[$sel]["th"];
            $Topic->title_br = @$titles[$sel]["br"];

            $Topic->seo_url_slug_ar = str_replace(" ", "-", @$titles[$sel]["ar"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_en = str_replace(" ", "-", @$titles[$sel]["en"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ch = str_replace(" ", "-", @$titles[$sel]["ch"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_hi = str_replace(" ", "-", @$titles[$sel]["hi"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_es = str_replace(" ", "-", @$titles[$sel]["es"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ru = str_replace(" ", "-", @$titles[$sel]["ru"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_pt = str_replace(" ", "-", @$titles[$sel]["pt"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_fr = str_replace(" ", "-", @$titles[$sel]["fr"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_de = str_replace(" ", "-", @$titles[$sel]["de"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_th = str_replace(" ", "-", @$titles[$sel]["th"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_br = str_replace(" ", "-", @$titles[$sel]["br"]) . "-" . $webmaster_id . $i;

            $Topic->date = date('Y-m-d');
            $Topic->video_type = 1;
            $Topic->video_file = "https://www.youtube.com/watch?v=D0UnqGm_miA";
            $Topic->icon = "";
            $Topic->status = 1;
            $Topic->visits = 0;
            $Topic->section_id = 0;
            $Topic->created_by = 1;
            $Topic->save();

            $TopicCategory = new TopicCategory;
            $TopicCategory->topic_id = $Topic->id;
            $TopicCategory->section_id = rand(1, 3);
            $TopicCategory->save();
        }

        // audio
        for ($i = 0; $i <= 5; $i++) {
            $sel = $i;
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            $webmaster_id = 6;

            $Topic = new Topic();
            $Topic->row_no = 0;
            $Topic->webmaster_id = $webmaster_id;

            $Topic->title_ar = @$titles[$sel]["ar"];
            $Topic->title_en = @$titles[$sel]["en"];
            $Topic->title_ch = @$titles[$sel]["ch"];
            $Topic->title_hi = @$titles[$sel]["hi"];
            $Topic->title_es = @$titles[$sel]["es"];
            $Topic->title_ru = @$titles[$sel]["ru"];
            $Topic->title_pt = @$titles[$sel]["pt"];
            $Topic->title_fr = @$titles[$sel]["fr"];
            $Topic->title_de = @$titles[$sel]["de"];
            $Topic->title_th = @$titles[$sel]["th"];
            $Topic->title_br = @$titles[$sel]["br"];

            $Topic->seo_url_slug_ar = str_replace(" ", "-", @$titles[$sel]["ar"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_en = str_replace(" ", "-", @$titles[$sel]["en"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ch = str_replace(" ", "-", @$titles[$sel]["ch"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_hi = str_replace(" ", "-", @$titles[$sel]["hi"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_es = str_replace(" ", "-", @$titles[$sel]["es"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ru = str_replace(" ", "-", @$titles[$sel]["ru"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_pt = str_replace(" ", "-", @$titles[$sel]["pt"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_fr = str_replace(" ", "-", @$titles[$sel]["fr"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_de = str_replace(" ", "-", @$titles[$sel]["de"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_th = str_replace(" ", "-", @$titles[$sel]["th"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_br = str_replace(" ", "-", @$titles[$sel]["br"]) . "-" . $webmaster_id . $i;

            $Topic->date = date('Y-m-d');
            $Topic->audio_file = "audio_file_$i.mp3";
            $Topic->icon = "";
            $Topic->status = 1;
            $Topic->visits = 0;
            $Topic->section_id = 0;
            $Topic->created_by = 1;
            $Topic->save();

            $TopicCategory = new TopicCategory;
            $TopicCategory->topic_id = $Topic->id;
            $TopicCategory->section_id = 6;
            $TopicCategory->save();
        }

        // blog
        for ($i = 0; $i <= 11; $i++) {
            $sel = $i;
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            if ($sel > 5) {
                $sel = $sel - 5;
            }

            $webmaster_id = 7;

            $Topic = new Topic();
            $Topic->row_no = 0;
            $Topic->webmaster_id = $webmaster_id;

            $Topic->title_ar = @$titles[$sel]["ar"];
            $Topic->title_en = @$titles[$sel]["en"];
            $Topic->title_ch = @$titles[$sel]["ch"];
            $Topic->title_hi = @$titles[$sel]["hi"];
            $Topic->title_es = @$titles[$sel]["es"];
            $Topic->title_ru = @$titles[$sel]["ru"];
            $Topic->title_pt = @$titles[$sel]["pt"];
            $Topic->title_fr = @$titles[$sel]["fr"];
            $Topic->title_de = @$titles[$sel]["de"];
            $Topic->title_th = @$titles[$sel]["th"];
            $Topic->title_br = @$titles[$sel]["br"];

            $Topic->details_ar = $details_ar;
            $Topic->details_en = $details_en;
            $Topic->details_ch = $details_ch;
            $Topic->details_hi = $details_hi;
            $Topic->details_es = $details_es;
            $Topic->details_ru = $details_ru;
            $Topic->details_pt = $details_pt;
            $Topic->details_fr = $details_fr;
            $Topic->details_de = $details_de;
            $Topic->details_th = $details_th;
            $Topic->details_br = $details_br;

            $Topic->seo_url_slug_ar = str_replace(" ", "-", @$titles[$sel]["ar"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_en = str_replace(" ", "-", @$titles[$sel]["en"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ch = str_replace(" ", "-", @$titles[$sel]["ch"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_hi = str_replace(" ", "-", @$titles[$sel]["hi"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_es = str_replace(" ", "-", @$titles[$sel]["es"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ru = str_replace(" ", "-", @$titles[$sel]["ru"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_pt = str_replace(" ", "-", @$titles[$sel]["pt"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_fr = str_replace(" ", "-", @$titles[$sel]["fr"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_de = str_replace(" ", "-", @$titles[$sel]["de"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_th = str_replace(" ", "-", @$titles[$sel]["th"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_br = str_replace(" ", "-", @$titles[$sel]["br"]) . "-" . $webmaster_id . $i;

            $Topic->date = date('Y-m-d');
            $Topic->photo_file = "default.png";
            $Topic->icon = "";
            $Topic->status = 1;
            $Topic->visits = 0;
            $Topic->section_id = 0;
            $Topic->created_by = 1;
            $Topic->save();


            $TopicCategory = new TopicCategory;
            $TopicCategory->topic_id = $Topic->id;
            $TopicCategory->section_id = rand(20, 25);
            $TopicCategory->save();
        }

        // products
        for ($i = 0; $i <= 11; $i++) {
            $sel = $i;
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            if ($sel > 5) {
                $sel = $sel - 5;
            }

            $webmaster_id = 8;

            $Topic = new Topic();
            $Topic->row_no = 0;
            $Topic->webmaster_id = $webmaster_id;

            $Topic->title_ar = @$titles[$sel]["ar"];
            $Topic->title_en = @$titles[$sel]["en"];
            $Topic->title_ch = @$titles[$sel]["ch"];
            $Topic->title_hi = @$titles[$sel]["hi"];
            $Topic->title_es = @$titles[$sel]["es"];
            $Topic->title_ru = @$titles[$sel]["ru"];
            $Topic->title_pt = @$titles[$sel]["pt"];
            $Topic->title_fr = @$titles[$sel]["fr"];
            $Topic->title_de = @$titles[$sel]["de"];
            $Topic->title_th = @$titles[$sel]["th"];
            $Topic->title_br = @$titles[$sel]["br"];

            $Topic->details_ar = $details_ar;
            $Topic->details_en = $details_en;
            $Topic->details_ch = $details_ch;
            $Topic->details_hi = $details_hi;
            $Topic->details_es = $details_es;
            $Topic->details_ru = $details_ru;
            $Topic->details_pt = $details_pt;
            $Topic->details_fr = $details_fr;
            $Topic->details_de = $details_de;
            $Topic->details_th = $details_th;
            $Topic->details_br = $details_br;

            $Topic->seo_url_slug_ar = str_replace(" ", "-", @$titles[$sel]["ar"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_en = str_replace(" ", "-", @$titles[$sel]["en"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ch = str_replace(" ", "-", @$titles[$sel]["ch"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_hi = str_replace(" ", "-", @$titles[$sel]["hi"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_es = str_replace(" ", "-", @$titles[$sel]["es"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ru = str_replace(" ", "-", @$titles[$sel]["ru"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_pt = str_replace(" ", "-", @$titles[$sel]["pt"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_fr = str_replace(" ", "-", @$titles[$sel]["fr"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_de = str_replace(" ", "-", @$titles[$sel]["de"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_th = str_replace(" ", "-", @$titles[$sel]["th"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_br = str_replace(" ", "-", @$titles[$sel]["br"]) . "-" . $webmaster_id . $i;

            $Topic->date = date('Y-m-d');
            $Topic->photo_file = "default.png";
            $Topic->icon = "";
            $Topic->status = 1;
            $Topic->visits = 0;
            $Topic->section_id = 0;
            $Topic->created_by = 1;
            $Topic->save();

            $TopicField = new TopicField;
            $TopicField->topic_id = $Topic->id;
            $TopicField->field_id = 1;
            $TopicField->field_value = rand(1, 7);
            $TopicField->save();
            $TopicField = new TopicField;
            $TopicField->topic_id = $Topic->id;
            $TopicField->field_id = 2;
            $TopicField->field_value = rand(1500, 7500);
            $TopicField->save();

            $TopicCategory = new TopicCategory;
            $TopicCategory->topic_id = $Topic->id;
            $TopicCategory->section_id = rand(10, 19);
            $TopicCategory->save();
            $TopicCategory = new TopicCategory;
            $TopicCategory->topic_id = $Topic->id;
            $TopicCategory->section_id = 18;
            $TopicCategory->save();
        }

        // partners
        for ($i = 0; $i <= 11; $i++) {
            $sel = $i;
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            $webmaster_id = 9;

            $Topic = new Topic();
            $Topic->row_no = 0;
            $Topic->webmaster_id = $webmaster_id;

            $Topic->title_ar = @$titles[$sel]["ar"];
            $Topic->title_en = @$titles[$sel]["en"];
            $Topic->title_ch = @$titles[$sel]["ch"];
            $Topic->title_hi = @$titles[$sel]["hi"];
            $Topic->title_es = @$titles[$sel]["es"];
            $Topic->title_ru = @$titles[$sel]["ru"];
            $Topic->title_pt = @$titles[$sel]["pt"];
            $Topic->title_fr = @$titles[$sel]["fr"];
            $Topic->title_de = @$titles[$sel]["de"];
            $Topic->title_th = @$titles[$sel]["th"];
            $Topic->title_br = @$titles[$sel]["br"];

            $Topic->seo_url_slug_ar = str_replace(" ", "-", @$titles[$sel]["ar"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_en = str_replace(" ", "-", @$titles[$sel]["en"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ch = str_replace(" ", "-", @$titles[$sel]["ch"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_hi = str_replace(" ", "-", @$titles[$sel]["hi"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_es = str_replace(" ", "-", @$titles[$sel]["es"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ru = str_replace(" ", "-", @$titles[$sel]["ru"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_pt = str_replace(" ", "-", @$titles[$sel]["pt"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_fr = str_replace(" ", "-", @$titles[$sel]["fr"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_de = str_replace(" ", "-", @$titles[$sel]["de"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_th = str_replace(" ", "-", @$titles[$sel]["th"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_br = str_replace(" ", "-", @$titles[$sel]["br"]) . "-" . $webmaster_id . $i;

            $Topic->date = date('Y-m-d');
            $Topic->photo_file = "default.png";
            $Topic->icon = "";
            $Topic->status = 1;
            $Topic->visits = 0;
            $Topic->section_id = 0;
            $Topic->created_by = 1;
            $Topic->save();

            $TopicField = new TopicField;
            $TopicField->topic_id = $Topic->id;
            $TopicField->field_id = 3;
            $TopicField->field_value = "#";
            $TopicField->save();
        }

        // FAQ
        for ($i = 0; $i <= 11; $i++) {
            $sel = $i;
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            if ($sel > 5) {
                $sel = $sel - 5;
            }

            $webmaster_id = 10;

            $Topic = new Topic();
            $Topic->row_no = 0;
            $Topic->webmaster_id = $webmaster_id;

            $Topic->title_ar = @$titles[$sel]["ar"];
            $Topic->title_en = @$titles[$sel]["en"];
            $Topic->title_ch = @$titles[$sel]["ch"];
            $Topic->title_hi = @$titles[$sel]["hi"];
            $Topic->title_es = @$titles[$sel]["es"];
            $Topic->title_ru = @$titles[$sel]["ru"];
            $Topic->title_pt = @$titles[$sel]["pt"];
            $Topic->title_fr = @$titles[$sel]["fr"];
            $Topic->title_de = @$titles[$sel]["de"];
            $Topic->title_th = @$titles[$sel]["th"];
            $Topic->title_br = @$titles[$sel]["br"];

            $Topic->details_ar = mb_substr(strip_tags($details_ar), 0, 250);
            $Topic->details_en = mb_substr(strip_tags($details_en), 0, 250);
            $Topic->details_ch = mb_substr(strip_tags($details_ch), 0, 250);
            $Topic->details_hi = mb_substr(strip_tags($details_hi), 0, 250);
            $Topic->details_es = mb_substr(strip_tags($details_es), 0, 250);
            $Topic->details_ru = mb_substr(strip_tags($details_ru), 0, 250);
            $Topic->details_pt = mb_substr(strip_tags($details_pt), 0, 250);
            $Topic->details_fr = mb_substr(strip_tags($details_fr), 0, 250);
            $Topic->details_de = mb_substr(strip_tags($details_de), 0, 250);
            $Topic->details_th = mb_substr(strip_tags($details_th), 0, 250);
            $Topic->details_br = mb_substr(strip_tags($details_br), 0, 250);

            $Topic->seo_url_slug_ar = str_replace(" ", "-", @$titles[$sel]["ar"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_en = str_replace(" ", "-", @$titles[$sel]["en"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ch = str_replace(" ", "-", @$titles[$sel]["ch"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_hi = str_replace(" ", "-", @$titles[$sel]["hi"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_es = str_replace(" ", "-", @$titles[$sel]["es"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ru = str_replace(" ", "-", @$titles[$sel]["ru"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_pt = str_replace(" ", "-", @$titles[$sel]["pt"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_fr = str_replace(" ", "-", @$titles[$sel]["fr"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_de = str_replace(" ", "-", @$titles[$sel]["de"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_th = str_replace(" ", "-", @$titles[$sel]["th"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_br = str_replace(" ", "-", @$titles[$sel]["br"]) . "-" . $webmaster_id . $i;

            $Topic->date = date('Y-m-d');
            $Topic->photo_file = "";
            $Topic->icon = "";
            $Topic->status = 1;
            $Topic->visits = 0;
            $Topic->section_id = 0;
            $Topic->created_by = 1;
            $Topic->save();

            $s = 26;
            if ($i > 8) {
                $s = 29;
            } elseif ($i > 5) {
                $s = 28;
            } elseif ($i > 2) {
                $s = 27;
            }
            $TopicCategory = new TopicCategory;
            $TopicCategory->topic_id = $Topic->id;
            $TopicCategory->section_id = $s;
            $TopicCategory->save();
        }

        // Testimonials
        for ($i = 0; $i <= 1; $i++) {
            $sel = $i;
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            if ($sel > 5) {
                $sel = $sel - 5;
            }

            $webmaster_id = 11;

            $Topic = new Topic();
            $Topic->row_no = 0;
            $Topic->webmaster_id = $webmaster_id;

            $Topic->title_ar = @$titles[$sel]["ar"];
            $Topic->title_en = @$titles[$sel]["en"];
            $Topic->title_ch = @$titles[$sel]["ch"];
            $Topic->title_hi = @$titles[$sel]["hi"];
            $Topic->title_es = @$titles[$sel]["es"];
            $Topic->title_ru = @$titles[$sel]["ru"];
            $Topic->title_pt = @$titles[$sel]["pt"];
            $Topic->title_fr = @$titles[$sel]["fr"];
            $Topic->title_de = @$titles[$sel]["de"];
            $Topic->title_th = @$titles[$sel]["th"];
            $Topic->title_br = @$titles[$sel]["br"];

            $Topic->details_ar = mb_substr(strip_tags($details_ar), 0, 250);
            $Topic->details_en = mb_substr(strip_tags($details_en), 0, 250);
            $Topic->details_ch = mb_substr(strip_tags($details_ch), 0, 250);
            $Topic->details_hi = mb_substr(strip_tags($details_hi), 0, 250);
            $Topic->details_es = mb_substr(strip_tags($details_es), 0, 250);
            $Topic->details_ru = mb_substr(strip_tags($details_ru), 0, 250);
            $Topic->details_pt = mb_substr(strip_tags($details_pt), 0, 250);
            $Topic->details_fr = mb_substr(strip_tags($details_fr), 0, 250);
            $Topic->details_de = mb_substr(strip_tags($details_de), 0, 250);
            $Topic->details_th = mb_substr(strip_tags($details_th), 0, 250);
            $Topic->details_br = mb_substr(strip_tags($details_br), 0, 250);

            $Topic->seo_url_slug_ar = str_replace(" ", "-", @$titles[$sel]["ar"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_en = str_replace(" ", "-", @$titles[$sel]["en"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ch = str_replace(" ", "-", @$titles[$sel]["ch"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_hi = str_replace(" ", "-", @$titles[$sel]["hi"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_es = str_replace(" ", "-", @$titles[$sel]["es"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ru = str_replace(" ", "-", @$titles[$sel]["ru"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_pt = str_replace(" ", "-", @$titles[$sel]["pt"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_fr = str_replace(" ", "-", @$titles[$sel]["fr"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_de = str_replace(" ", "-", @$titles[$sel]["de"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_th = str_replace(" ", "-", @$titles[$sel]["th"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_br = str_replace(" ", "-", @$titles[$sel]["br"]) . "-" . $webmaster_id . $i;

            $Topic->date = date('Y-m-d');
            $Topic->photo_file = "default.png";
            $Topic->icon = "";
            $Topic->status = 1;
            $Topic->visits = 0;
            $Topic->section_id = 0;
            $Topic->created_by = 1;
            $Topic->save();
        }

        // Staff
        for ($i = 0; $i <= 11; $i++) {
            $sel = $i;
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            if ($sel > 5) {
                $sel = $sel - 5;
            }

            $webmaster_id = 12;

            $Topic = new Topic();
            $Topic->row_no = 0;
            $Topic->webmaster_id = $webmaster_id;

            $Topic->title_ar = mb_substr(@$titles[$sel]["ar"], 0, 30);
            $Topic->title_en = mb_substr(@$titles[$sel]["en"], 0, 30);
            $Topic->title_ch = mb_substr(@$titles[$sel]["ch"], 0, 30);
            $Topic->title_hi = mb_substr(@$titles[$sel]["hi"], 0, 30);
            $Topic->title_es = mb_substr(@$titles[$sel]["es"], 0, 30);
            $Topic->title_ru = mb_substr(@$titles[$sel]["ru"], 0, 30);
            $Topic->title_pt = mb_substr(@$titles[$sel]["pt"], 0, 30);
            $Topic->title_fr = mb_substr(@$titles[$sel]["fr"], 0, 30);
            $Topic->title_de = mb_substr(@$titles[$sel]["de"], 0, 30);
            $Topic->title_th = mb_substr(@$titles[$sel]["th"], 0, 30);
            $Topic->title_br = mb_substr(@$titles[$sel]["br"], 0, 30);

            $Topic->details_ar = $details_ar;
            $Topic->details_en = $details_en;
            $Topic->details_ch = $details_ch;
            $Topic->details_hi = $details_hi;
            $Topic->details_es = $details_es;
            $Topic->details_ru = $details_ru;
            $Topic->details_pt = $details_pt;
            $Topic->details_fr = $details_fr;
            $Topic->details_de = $details_de;
            $Topic->details_th = $details_th;
            $Topic->details_br = $details_br;

            $Topic->seo_url_slug_ar = str_replace(" ", "-", @$titles[$sel]["ar"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_en = str_replace(" ", "-", @$titles[$sel]["en"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ch = str_replace(" ", "-", @$titles[$sel]["ch"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_hi = str_replace(" ", "-", @$titles[$sel]["hi"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_es = str_replace(" ", "-", @$titles[$sel]["es"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_ru = str_replace(" ", "-", @$titles[$sel]["ru"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_pt = str_replace(" ", "-", @$titles[$sel]["pt"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_fr = str_replace(" ", "-", @$titles[$sel]["fr"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_de = str_replace(" ", "-", @$titles[$sel]["de"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_th = str_replace(" ", "-", @$titles[$sel]["th"]) . "-" . $webmaster_id . $i;
            $Topic->seo_url_slug_br = str_replace(" ", "-", @$titles[$sel]["br"]) . "-" . $webmaster_id . $i;

            $Topic->date = date('Y-m-d');
            $Topic->photo_file = "default.png";
            $Topic->icon = "";
            $Topic->status = 1;
            $Topic->visits = 0;
            $Topic->section_id = 0;
            $Topic->created_by = 1;
            $Topic->save();

            $TopicField = new TopicField;
            $TopicField->topic_id = $Topic->id;
            $TopicField->field_id = 4;
            $TopicField->field_value = "Job title or description";
            $TopicField->save();
        }

        // branches
        for ($i = 0; $i <= 11; $i++) {
            $sel = $i;
            if ($sel > 5) {
                $sel = $sel - 5;
            }
            if ($sel > 5) {
                $sel = $sel - 5;
            }

            $webmaster_id = 13;

            $Topic = new Topic();
            $Topic->row_no = 0;
            $Topic->webmaster_id = $webmaster_id;

            $Topic->date = date('Y-m-d');
            $Topic->photo_file = "";
            $Topic->icon = "";
            $Topic->status = 1;
            $Topic->visits = 0;
            $Topic->section_id = 0;
            $Topic->created_by = 1;
            $Topic->save();

            $TopicField = new TopicField;
            $TopicField->topic_id = $Topic->id;
            $TopicField->field_id = 5;
            $TopicField->field_value = mb_substr(@$titles[$sel]["en"], 0, 20);
            $TopicField->save();
            $TopicField = new TopicField;
            $TopicField->topic_id = $Topic->id;
            $TopicField->field_id = 6;
            $TopicField->field_value = mb_substr(@$titles[$sel]["en"], 0, 50);
            $TopicField->save();
            $TopicField = new TopicField;
            $TopicField->topic_id = $Topic->id;
            $TopicField->field_id = 7;
            $TopicField->field_value = rand(1, 7);
            $TopicField->save();
            $TopicField = new TopicField;
            $TopicField->topic_id = $Topic->id;
            $TopicField->field_id = 8;
            $TopicField->field_value = "0123" . rand(10000, 999999);
            $TopicField->save();
        }
    }
}
