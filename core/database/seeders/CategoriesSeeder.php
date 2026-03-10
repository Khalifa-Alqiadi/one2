<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Section;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Video Categories

        $cat = new Section();
        $cat->row_no = 1;
        $cat->webmaster_id = 5;
        $cat->title_ar = "خلف العدسة";
        $cat->title_en = "Behind the Lens";
        $cat->title_ch = "镜头背后";
        $cat->title_hi = "लेंस के पीछे";
        $cat->title_es = "Detrás de la lente";
        $cat->title_ru = "За объективом";
        $cat->title_pt = "Atrás das lentes";
        $cat->title_fr = "Derrière l'objectif";
        $cat->title_de = "Hinter der Linse";
        $cat->title_th = "หลังเลนส์";
        $cat->title_br = "Atrás das lentes";

        $cat->seo_url_slug_ar = "خلف-العدسة";
        $cat->seo_url_slug_en = "behind-the-lens";
        $cat->seo_url_slug_ch = "镜头背后";
        $cat->seo_url_slug_hi = "लेंस-के-पीछे";
        $cat->seo_url_slug_es = "detrás-de-la-lente";
        $cat->seo_url_slug_ru = "за-объективом";
        $cat->seo_url_slug_pt = "atrás-das-lentes";
        $cat->seo_url_slug_fr = "derrière-l'objectif";
        $cat->seo_url_slug_de = "hinter-der-linse";
        $cat->seo_url_slug_th = "หลังเลนส์";
        $cat->seo_url_slug_br = "atrás-lentes";

        $cat->icon = "fa fa-camera";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 2;
        $cat->webmaster_id = 5;
        $cat->title_ar = "السمفونية البصرية";
        $cat->title_en = "Visual Symphony";
        $cat->title_ch = "视觉交响乐";
        $cat->title_hi = "दृश्य सिम्फनी";
        $cat->title_es = "Sinfonía visual";
        $cat->title_ru = "Визуальная симфония";
        $cat->title_pt = "Sinfonia Visual";
        $cat->title_fr = "Symphonie visuelle";
        $cat->title_de = "Visuelle Symphonie";
        $cat->title_th = "วิชวลซิมโฟนี";
        $cat->title_br = "Sinfonia Visual";

        $cat->seo_url_slug_ar = "السمفونية-البصرية";
        $cat->seo_url_slug_en = "visual-symphony";
        $cat->seo_url_slug_ch = "视觉交响乐";
        $cat->seo_url_slug_hi = "दृश्य-िम्फनी";
        $cat->seo_url_slug_es = "sinfonía-visual";
        $cat->seo_url_slug_ru = "визуальная-симфония";
        $cat->seo_url_slug_pt = "sinfonia-visual";
        $cat->seo_url_slug_fr = "symphonie-visuelle";
        $cat->seo_url_slug_de = "visuelle-symphonie";
        $cat->seo_url_slug_th = "วิชวลซิมโฟนี";
        $cat->seo_url_slug_br = "sinfoniavisual";

        $cat->icon = "fa fa-tripadvisor";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 3;
        $cat->webmaster_id = 5;
        $cat->title_ar = "الإطارات المتحركة";
        $cat->title_en = "Frames in Motion";
        $cat->title_ch = "运动中的帧";
        $cat->title_hi = "गति में फ़्रेम";
        $cat->title_es = "Cuadros en movimiento";
        $cat->title_ru = "Кадры в движении";
        $cat->title_pt = "Quadros em movimento";
        $cat->title_fr = "Images en mouvement";
        $cat->title_de = "Frames in Bewegung";
        $cat->title_th = "เฟรมในการเคลื่อนไหว";
        $cat->title_br = "Quadros em movimento";

        $cat->seo_url_slug_ar = "الإطارات-المتحركة";
        $cat->seo_url_slug_en = "frames-in-motion";
        $cat->seo_url_slug_ch = "运动中的帧";
        $cat->seo_url_slug_hi = "गति-में-फ़्रेम";
        $cat->seo_url_slug_es = "cuadros-en-movimiento";
        $cat->seo_url_slug_ru = "кадры-в-движении";
        $cat->seo_url_slug_pt = "quadros-em-movimento";
        $cat->seo_url_slug_fr = "images-en-mouvement";
        $cat->seo_url_slug_de = "frames-in-bewegung";
        $cat->seo_url_slug_th = "เฟรมในการเคลื่อนไหว";
        $cat->seo_url_slug_br = "quadros-movimento";

        $cat->icon = "fa fa-modx";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 4;
        $cat->webmaster_id = 5;
        $cat->title_ar = "لحضات مميزة";
        $cat->title_en = "Moments Unscripted";
        $cat->title_ch = "即兴时刻";
        $cat->title_hi = "अप्रकाशित क्षण";
        $cat->title_es = "Momentos sin guión";
        $cat->title_ru = "Моменты без сценария";
        $cat->title_pt = "Momentos improvisados";
        $cat->title_fr = "Moments non scénarisés";
        $cat->title_de = "Momente ohne Drehbuch";
        $cat->title_th = "ช่วงเวลาที่ไม่มีสคริปต์";
        $cat->title_br = "Momentos improvisados";

        $cat->seo_url_slug_ar = "لحضات-مميزة";
        $cat->seo_url_slug_en = "moments-unscripted";
        $cat->seo_url_slug_ch = "即兴时刻";
        $cat->seo_url_slug_hi = "अप्रकाशित-क्षण";
        $cat->seo_url_slug_es = "momentos-sin-guión";
        $cat->seo_url_slug_ru = "моменты-без-сценария";
        $cat->seo_url_slug_pt = "momentos-improvisados";
        $cat->seo_url_slug_fr = "moments-non-scénarisés";
        $cat->seo_url_slug_de = "momente-ohne-drehbuch";
        $cat->seo_url_slug_th = "ช่วงเวลาที่ไม่มีสคริปต์";
        $cat->seo_url_slug_br = "momentosimprovisados";

        $cat->icon = "fa fa-youtube-play";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 5;
        $cat->webmaster_id = 5;
        $cat->title_ar = "إنطباعات العملاء";
        $cat->title_en = "Reel Impressions";
        $cat->title_ch = "卷轴印象";
        $cat->title_hi = "रील इंप्रेशन";
        $cat->title_es = "Impresiones de carrete";
        $cat->title_ru = "Впечатления от катушки";
        $cat->title_pt = "Impressões do rolo";
        $cat->title_fr = "Impressions de bobines";
        $cat->title_de = "Rollenimpressionen";
        $cat->title_th = "การแสดงผลรีล";
        $cat->title_br = "Impressões do rolo";

        $cat->seo_url_slug_ar = "إنطباعات-العملاء";
        $cat->seo_url_slug_en = "reel-impressions";
        $cat->seo_url_slug_ch = "卷轴印象";
        $cat->seo_url_slug_hi = "रील-इंप्रेशन";
        $cat->seo_url_slug_es = "impresiones-de-carrete";
        $cat->seo_url_slug_ru = "впечатления-от-катушки";
        $cat->seo_url_slug_pt = "impressões-do-rolo";
        $cat->seo_url_slug_fr = "impressions-de-bobines";
        $cat->seo_url_slug_de = "rollenimpressionen";
        $cat->seo_url_slug_th = "การแสดงผลรีล";
        $cat->seo_url_slug_br = "impressões-rolo";

        $cat->icon = "fa fa-hourglass-start";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();


        // Audio Categories

        $cat = new Section();
        $cat->row_no = 1;
        $cat->webmaster_id = 6;
        $cat->title_ar = "كتب صوتية";
        $cat->title_en = "Audio books";
        $cat->title_ch = "有声读物";
        $cat->title_hi = "ऑडियो पुस्तकें";
        $cat->title_es = "audiolibros";
        $cat->title_ru = "Аудиокниги";
        $cat->title_pt = "Livros de áudio";
        $cat->title_fr = "Livres audio";
        $cat->title_de = "Hörbücher";
        $cat->title_th = "หนังสือเสียง";
        $cat->title_br = "Livros de áudio";

        $cat->seo_url_slug_ar = "كتب-صوتية";
        $cat->seo_url_slug_en = "audio-books";
        $cat->seo_url_slug_ch = "有声读物";
        $cat->seo_url_slug_hi = "ऑडियो-पुस्तकें";
        $cat->seo_url_slug_es = "audiolibros";
        $cat->seo_url_slug_ru = "аудиокниги";
        $cat->seo_url_slug_pt = "livros-d- áudio";
        $cat->seo_url_slug_fr = "livres-audio";
        $cat->seo_url_slug_de = "hörbücher";
        $cat->seo_url_slug_th = "หนังสือเสียง";
        $cat->seo_url_slug_br = "livros-áudio";

        $cat->icon = "fa fa-soundcloud";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 2;
        $cat->webmaster_id = 6;
        $cat->title_ar = "تسجيلات حصرية";
        $cat->title_en = "Exclusive recordings";
        $cat->title_ch = "独家录音";
        $cat->title_hi = "विशेष रिकॉर्डिंग";
        $cat->title_es = "Grabaciones exclusivas";
        $cat->title_ru = "Эксклюзивные записи";
        $cat->title_pt = "Gravações exclusivas";
        $cat->title_fr = "Enregistrements exclusifs";
        $cat->title_de = "Exklusive Aufnahmen";
        $cat->title_th = "บันทึกพิเศษ";
        $cat->title_br = "Gravações exclusivas";

        $cat->seo_url_slug_ar = "تسجيلات-حصرية";
        $cat->seo_url_slug_en = "exclusive-recordings";
        $cat->seo_url_slug_ch = "独家录音";
        $cat->seo_url_slug_hi = "विशेष-रिकॉर्डिंग";
        $cat->seo_url_slug_es = "grabaciones-exclusivas";
        $cat->seo_url_slug_ru = "эксклюзивные-записи";
        $cat->seo_url_slug_pt = "gravações-exclusivas";
        $cat->seo_url_slug_fr = "enregistrements-exclusifs";
        $cat->seo_url_slug_de = "exklusive-aufnahmen";
        $cat->seo_url_slug_th = "บันทึกพิเศษ";
        $cat->seo_url_slug_br = "gravaçõesexclusivas";

        $cat->icon = "fa fa-music";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 3;
        $cat->webmaster_id = 6;
        $cat->title_ar = "حلقات بودكاست";
        $cat->title_en = "Podcast episode";
        $cat->title_ch = "播客剧集";
        $cat->title_hi = "पॉडकास्ट एपिसोड";
        $cat->title_es = "episodio de podcast";
        $cat->title_ru = "Эпизод подкаста";
        $cat->title_pt = "Episódio de podcast";
        $cat->title_fr = "Épisode de podcast";
        $cat->title_de = "Podcast-Folge";
        $cat->title_th = "ตอนพอดแคสต์";
        $cat->title_br = "Episódio de podcast";


        $cat->seo_url_slug_ar = "حلقات-بودكاست";
        $cat->seo_url_slug_en = "podcast-episode";
        $cat->seo_url_slug_ch = "播客剧集";
        $cat->seo_url_slug_hi = "पॉडकास्ट-एपिसोड";
        $cat->seo_url_slug_es = "episodio-de-podcast";
        $cat->seo_url_slug_ru = "эпизод-подкаста";
        $cat->seo_url_slug_pt = "episódio-de-podcast";
        $cat->seo_url_slug_fr = "épisode-de-podcast";
        $cat->seo_url_slug_de = "podcast-folge";
        $cat->seo_url_slug_th = "ตอนพอดแคสต์";
        $cat->seo_url_slug_br = "episódio-podcast";

        $cat->icon = "fa fa-empire";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        // Products

        $cat = new Section();
        $cat->row_no = 1;
        $cat->webmaster_id = 8;
        $cat->title_ar = "كتب تعليمية";
        $cat->title_en = "Educational books";
        $cat->title_ch = "教育书籍";
        $cat->title_hi = "शैक्षिक पुस्तकें";
        $cat->title_es = "libros educativos";
        $cat->title_ru = "Учебные книги";
        $cat->title_pt = "Livros educativos";
        $cat->title_fr = "Livres pédagogiques";
        $cat->title_de = "Livres pédagogiques";
        $cat->title_th = "หนังสือการศึกษา";
        $cat->title_br = "Livros educativos";


        $cat->seo_url_slug_ar = "كتب-تعليمية";
        $cat->seo_url_slug_en = "educational-books";
        $cat->seo_url_slug_ch = "教育书籍";
        $cat->seo_url_slug_hi = "शैक्षिक-पुस्तकें";
        $cat->seo_url_slug_es = "libros-educativos";
        $cat->seo_url_slug_ru = "учебные-книги";
        $cat->seo_url_slug_pt = "livros-educativos";
        $cat->seo_url_slug_fr = "livres-pédagogiques";
        $cat->seo_url_slug_de = "livres-pédagogiques";
        $cat->seo_url_slug_th = "หนังสือการศึกษา";
        $cat->seo_url_slug_br = "livroseducativos";

        $cat->icon = "fa fa-book";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 2;
        $cat->webmaster_id = 8;
        $cat->title_ar = "كتب تعليم اللغات";
        $cat->title_en = "Language books";
        $cat->title_ch = "语言书籍";
        $cat->title_hi = "भाषा की किताबें";
        $cat->title_es = "Libros de idiomas";
        $cat->title_ru = "Языковые книги";
        $cat->title_pt = "Livros de idiomas";
        $cat->title_fr = "Livres de langue";
        $cat->title_de = "Sprachbücher";
        $cat->title_th = "หนังสือภาษา";
        $cat->title_br = "Livros de idiomas";


        $cat->seo_url_slug_ar = "كتب-تعليم-اللغات";
        $cat->seo_url_slug_en = "language-books";
        $cat->seo_url_slug_ch = "语言书籍";
        $cat->seo_url_slug_hi = "भाषा-की-िताबें";
        $cat->seo_url_slug_es = "libros-de-idiomas";
        $cat->seo_url_slug_ru = "языковые-книги";
        $cat->seo_url_slug_pt = "livros-de-idiomas";
        $cat->seo_url_slug_fr = "livres-de-langue";
        $cat->seo_url_slug_de = "sprachbücher";
        $cat->seo_url_slug_th = "หนังสือภาษา";
        $cat->seo_url_slug_br = "livros-idiomas";

        $cat->icon = "fa fa-language";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 3;
        $cat->webmaster_id = 8;
        $cat->title_ar = "التجارة والأعمال";
        $cat->title_en = "Business books";
        $cat->title_ch = "商业书籍";
        $cat->title_hi = "व्यवसायिक पुस्तकें";
        $cat->title_es = "Бизнес-книги";
        $cat->title_ru = "Бизнес-книги";
        $cat->title_pt = "Livros de negócios";
        $cat->title_fr = "Livres d'affaires";
        $cat->title_de = "Wirtschaftsbücher";
        $cat->title_th = "หนังสือธุรกิจ";
        $cat->title_br = "Livros de negócios";


        $cat->seo_url_slug_ar = "التجارة-والأعمال";
        $cat->seo_url_slug_en = "business-books";
        $cat->seo_url_slug_ch = "商业书籍";
        $cat->seo_url_slug_hi = "व्यवसायिक-पुस्तकें";
        $cat->seo_url_slug_es = "бизнес-книги";
        $cat->seo_url_slug_ru = "бизнес-книги";
        $cat->seo_url_slug_pt = "livros-de-negócios";
        $cat->seo_url_slug_fr = "livres-d'affaires";
        $cat->seo_url_slug_de = "wirtschaftsbücher";
        $cat->seo_url_slug_th = "หนังสือธุรกิจ";
        $cat->seo_url_slug_br = "livros-negócios";

        $cat->icon = "fa fa-leanpub";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 4;
        $cat->webmaster_id = 8;
        $cat->title_ar = "المهن والحرف";
        $cat->title_en = "Crafts books";
        $cat->title_ch = "工艺品书籍";
        $cat->title_hi = "शिल्प पुस्तकें";
        $cat->title_es = "Libros de manualidades";
        $cat->title_ru = "Книги по рукоделию";
        $cat->title_pt = "Livros de artesanato";
        $cat->title_fr = "Livres d'artisanat";
        $cat->title_de = "Bastelbücher";
        $cat->title_th = "หนังสืองานฝีมือ";
        $cat->title_br = "Livros de artesanato";


        $cat->seo_url_slug_ar = "المهن-والحرف";
        $cat->seo_url_slug_en = "crafts-books";
        $cat->seo_url_slug_ch = "工艺品书籍";
        $cat->seo_url_slug_hi = "शिल्प-पुस्तकें";
        $cat->seo_url_slug_es = "libros-de-manualidades";
        $cat->seo_url_slug_ru = "книги-по-рукоделию";
        $cat->seo_url_slug_pt = "livros-de-artesanato";
        $cat->seo_url_slug_fr = "livres-d'artisanat";
        $cat->seo_url_slug_de = "bastelbücher";
        $cat->seo_url_slug_th = "หนังสืองานฝีมือ";
        $cat->seo_url_slug_br = "livros-artesanato";

        $cat->icon = "fa fa-american-sign-language-interpreting";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 5;
        $cat->webmaster_id = 8;
        $cat->title_ar = "الصحة والجمال";
        $cat->title_en = "Health & Fitness";
        $cat->title_ch = "健康与健身";
        $cat->title_hi = "स्वास्थ्य और फिटनेस";
        $cat->title_es = "salud y estado fisico";
        $cat->title_ru = "Здоровье и фитнес";
        $cat->title_pt = "Saúde";
        $cat->title_fr = "santé et forme";
        $cat->title_de = "Gesundheit";
        $cat->title_th = "สุขภาพและฟิตเนส";
        $cat->title_br = "Saúde";


        $cat->seo_url_slug_ar = "الصحة-والجمال";
        $cat->seo_url_slug_en = "health-fitness";
        $cat->seo_url_slug_ch = "健康与健身";
        $cat->seo_url_slug_hi = "स्वास्थ्य-और-फिटनेस";
        $cat->seo_url_slug_es = "salud-y-estado-fisico";
        $cat->seo_url_slug_ru = "здоровье-и-фитнес";
        $cat->seo_url_slug_pt = "saúde";
        $cat->seo_url_slug_fr = "santé-et-forme";
        $cat->seo_url_slug_de = "gesundheit";
        $cat->seo_url_slug_th = "สุขภาพและฟิตเนส";
        $cat->seo_url_slug_br = "saúde2";

        $cat->icon = "fa fa-heartbeat";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 6;
        $cat->webmaster_id = 8;
        $cat->title_ar = "الفن والإبداع";
        $cat->title_en = "Art & Photography";
        $cat->title_ch = "艺术与摄影";
        $cat->title_hi = "कला एवं फोटोग्राफी";
        $cat->title_es = "Arte y fotografía";
        $cat->title_ru = "Искусство и фотография";
        $cat->title_pt = "Arte e Fotografia";
        $cat->title_fr = "Art et photographie";
        $cat->title_de = "Kunst & Fotografie";
        $cat->title_th = "ศิลปะและการถ่ายภาพ";
        $cat->title_br = "Arte e Fotografia";


        $cat->seo_url_slug_ar = "الفن-والإبداع";
        $cat->seo_url_slug_en = "art-photography";
        $cat->seo_url_slug_ch = "艺术与摄影";
        $cat->seo_url_slug_hi = "कला-एवं--फोटोग्राफी";
        $cat->seo_url_slug_es = "arte-fotografía";
        $cat->seo_url_slug_ru = "искусство-фотография";
        $cat->seo_url_slug_pt = "arte-e-fotografia";
        $cat->seo_url_slug_fr = "art-et-photographie";
        $cat->seo_url_slug_de = "kunst-fotografie";
        $cat->seo_url_slug_th = "ศิลปะและการถ่ายภาพ";
        $cat->seo_url_slug_br = "arte-fotografia";

        $cat->icon = "fa fa-photo";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 1;
        $cat->webmaster_id = 8;
        $cat->title_ar = "كتب الأطفال";
        $cat->title_en = "Kids books";
        $cat->title_ch = "儿童书籍";
        $cat->title_hi = "बच्चों की किताबें";
        $cat->title_es = "Libros para niños";
        $cat->title_ru = "Детские книги";
        $cat->title_pt = "Livros infantis";
        $cat->title_fr = "Livres pour enfants";
        $cat->title_de = "Kinderbücher";
        $cat->title_th = "หนังสือสำหรับเด็ก";
        $cat->title_br = "Livros infantis";


        $cat->seo_url_slug_ar = "كتب-الأطفال";
        $cat->seo_url_slug_en = "kids-books";
        $cat->seo_url_slug_ch = "儿童书籍";
        $cat->seo_url_slug_hi = "बच्चो-की-किताबें";
        $cat->seo_url_slug_es = "libros-para-niños";
        $cat->seo_url_slug_ru = "детские-книги";
        $cat->seo_url_slug_pt = "livros-infantis";
        $cat->seo_url_slug_fr = "livres-pour-enfants";
        $cat->seo_url_slug_de = "kinderbücher";
        $cat->seo_url_slug_th = "หนังสือสำหรับเด็ก";
        $cat->seo_url_slug_br = "livrosinfantis";

        $cat->icon = "fa fa-graduation-cap";
        $cat->father_id = 9;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 2;
        $cat->webmaster_id = 8;
        $cat->title_ar = "علوم المعلومات";
        $cat->title_en = "Information Science";
        $cat->title_ch = "信息科学";
        $cat->title_hi = "सूचना विज्ञान";
        $cat->title_es = "Ciencias de la Información";
        $cat->title_ru = "Информационная наука";
        $cat->title_pt = "Ciência da Informação";
        $cat->title_fr = "Science de l'information";
        $cat->title_de = "Informations wissenschaft";
        $cat->title_th = "สารสนเทศศาสตร์";
        $cat->title_br = "Ciência da Informação";


        $cat->seo_url_slug_ar = "علوم-المعلومات";
        $cat->seo_url_slug_en = "information-science";
        $cat->seo_url_slug_ch = "信息科学";
        $cat->seo_url_slug_hi = "सूचना-विज्ञान";
        $cat->seo_url_slug_es = "ciencias-información";
        $cat->seo_url_slug_ru = "информационная-наука";
        $cat->seo_url_slug_pt = "ciência-da-informação";
        $cat->seo_url_slug_fr = "science-de-l'information";
        $cat->seo_url_slug_de = "informations-wissenschaft";
        $cat->seo_url_slug_th = "สารสนเทศศาสตร์";
        $cat->seo_url_slug_br = "ciência-informação";

        $cat->icon = "fa fa-television";
        $cat->father_id = 9;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();


        $cat = new Section();
        $cat->row_no = 3;
        $cat->webmaster_id = 8;
        $cat->title_ar = "التعلم الذاتي";
        $cat->title_en = "Self Education";
        $cat->title_ch = "自我教育";
        $cat->title_hi = "स्वयं शिक्षा";
        $cat->title_es = "Autoeducación";
        $cat->title_ru = "Самообразование";
        $cat->title_pt = "Autoeducação";
        $cat->title_fr = "Auto-éducation";
        $cat->title_de = "Selbstbildung";
        $cat->title_th = "การศึกษาด้วยตนเอง";
        $cat->title_br = "Autoeducação";


        $cat->seo_url_slug_ar = "التعلم-الذاتي";
        $cat->seo_url_slug_en = "self-education";
        $cat->seo_url_slug_ch = "自我教育";
        $cat->seo_url_slug_hi = "स्वयं-शिक्षा";
        $cat->seo_url_slug_es = "autoeducación";
        $cat->seo_url_slug_ru = "самообразование";
        $cat->seo_url_slug_pt = "autoeducação";
        $cat->seo_url_slug_fr = "auto-éducation";
        $cat->seo_url_slug_de = "selbstbildung";
        $cat->seo_url_slug_th = "การศึกษาด้วยตนเอง";
        $cat->seo_url_slug_br = "autoeducação2";

        $cat->icon = "fa fa-black-tie";
        $cat->father_id = 9;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();


        $cat = new Section();
        $cat->row_no = 1;
        $cat->webmaster_id = 8;
        $cat->title_ar = "كتب دراسية";
        $cat->title_en = "School Books";
        $cat->title_ch = "教科书";
        $cat->title_hi = "स्कूल की किताबें";
        $cat->title_es = "Libros escolares";
        $cat->title_ru = "Школьные книги";
        $cat->title_pt = "Livros da escola";
        $cat->title_fr = "Livres d'école";
        $cat->title_de = "Schulbücher";
        $cat->title_th = "หนังสือเรียน";
        $cat->title_br = "Livros da escola";


        $cat->seo_url_slug_ar = "كتب-دراسية";
        $cat->seo_url_slug_en = "school -books";
        $cat->seo_url_slug_ch = "教科书";
        $cat->seo_url_slug_hi = "स्कूल-की-किताबें";
        $cat->seo_url_slug_es = "libros-escolares";
        $cat->seo_url_slug_ru = "школьные-книги";
        $cat->seo_url_slug_pt = "livros-da-escola";
        $cat->seo_url_slug_fr = "livres-d'école";
        $cat->seo_url_slug_de = "schulbücher";
        $cat->seo_url_slug_th = "หนังสือเรียน";
        $cat->seo_url_slug_br = "livros-escola";

        $cat->icon = "fa fa-address-book-o";
        $cat->father_id = 15;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 2;
        $cat->webmaster_id = 8;
        $cat->title_ar = "كتب الأنشطة";
        $cat->title_en = "Activities Books";
        $cat->title_ch = "活动书籍";
        $cat->title_hi = "गतिविधियाँ पुस्तकें";
        $cat->title_es = "Libros de actividades";
        $cat->title_ru = "Книги по занятиям";
        $cat->title_pt = "Livros de atividades";
        $cat->title_fr = "Livres d'activités";
        $cat->title_de = "Aktivitätenbücher";
        $cat->title_th = "หนังสือกิจกรรม";
        $cat->title_br = "Livros de atividades";


        $cat->seo_url_slug_ar = "كتب-الأنشطة";
        $cat->seo_url_slug_en = "activities-books";
        $cat->seo_url_slug_ch = "活动书籍";
        $cat->seo_url_slug_hi = "गतिविधियाँ-पुस्तकें";
        $cat->seo_url_slug_es = "libros-de-actividades";
        $cat->seo_url_slug_ru = "книги-по-занятиям";
        $cat->seo_url_slug_pt = "livros-de-atividades";
        $cat->seo_url_slug_fr = "livres-d'activités";
        $cat->seo_url_slug_de = "aktivitätenbücher";
        $cat->seo_url_slug_th = "หนังสือกิจกรรม";
        $cat->seo_url_slug_br = "livros-atividades";

        $cat->icon = "fa fa-cut";
        $cat->father_id = 15;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();


        // Blog Categories

        $cat = new Section();
        $cat->row_no = 1;
        $cat->webmaster_id = 7;
        $cat->title_ar = "برامج وتطبيقات";
        $cat->title_en = "Apps & Programs";
        $cat->title_ch = "应用程序和程序";
        $cat->title_hi = "ऐप्स और प्रोग्राम";
        $cat->title_es = "Aplicaciones y programas";
        $cat->title_ru = "Приложения и программы";
        $cat->title_pt = "Aplicativos e programas";
        $cat->title_fr = "Applications et programmes";
        $cat->title_de = "Apps und Programme";
        $cat->title_th = "แอพและโปรแกรม";
        $cat->title_br = "Aplicativos e programas";

        $cat->seo_url_slug_ar = "برامج-وتطبيقات";
        $cat->seo_url_slug_en = "apps-programs";
        $cat->seo_url_slug_ch = "应用程序和程序";
        $cat->seo_url_slug_hi = "ऐप्स-और-प्रोग्राम";
        $cat->seo_url_slug_es = "aplicaciones-y-programas";
        $cat->seo_url_slug_ru = "приложения-и-программы";
        $cat->seo_url_slug_pt = "aplicativos-e-programas";
        $cat->seo_url_slug_fr = "applications-et-programmes";
        $cat->seo_url_slug_de = "apps-und-programme";
        $cat->seo_url_slug_th = "แอพและโปรแกรม";
        $cat->seo_url_slug_br = "aplicativos-programas";

        $cat->icon = "fa fa-cloud-download";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 2;
        $cat->webmaster_id = 7;
        $cat->title_ar = "التصميم والبرمجيات";
        $cat->title_en = "Design & Development";
        $cat->title_ch = "设计开发";
        $cat->title_hi = "डिजाइन विकास";
        $cat->title_es = "Desarrollo de diseño";
        $cat->title_ru = "Развитие дизайна";
        $cat->title_pt = "Desenvolvimento de design";
        $cat->title_fr = "Conception et développement";
        $cat->title_de = "Design-Entwicklung";
        $cat->title_th = "การออกแบบและพัฒนา";
        $cat->title_br = "Desenvolvimento de design";

        $cat->seo_url_slug_ar = "التصميم-والبرمجيات";
        $cat->seo_url_slug_en = "design-development";
        $cat->seo_url_slug_ch = "设计开发";
        $cat->seo_url_slug_hi = "डिजाइन-विकास";
        $cat->seo_url_slug_es = "desarrollo-de-diseño";
        $cat->seo_url_slug_ru = "развитие-дизайна";
        $cat->seo_url_slug_pt = "desenvolvimento-de-design";
        $cat->seo_url_slug_fr = "conception-et-développement";
        $cat->seo_url_slug_de = "design-entwicklung";
        $cat->seo_url_slug_th = "การออกแบบและพัฒนา";
        $cat->seo_url_slug_br = "desenvolvimento-design";

        $cat->icon = "fa fa-desktop";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 3;
        $cat->webmaster_id = 7;
        $cat->title_ar = "الأجهزة الإلكترونية";
        $cat->title_en = "Hardware & devices";
        $cat->title_ch = "硬件和设备";
        $cat->title_hi = "हार्डवेयर और उपकरण";
        $cat->title_es = "Dispositivos de hardware";
        $cat->title_ru = "Оборудование и устройства";
        $cat->title_pt = "Hardware e dispositivos";
        $cat->title_fr = "Matériel et appareils";
        $cat->title_de = "Hardware und Geräte";
        $cat->title_th = "ฮาร์ดแวร์และอุปกรณ์";
        $cat->title_br = "Hardware e dispositivos";

        $cat->seo_url_slug_ar = "الأجهزة-الإلكترونية";
        $cat->seo_url_slug_en = "hardware-devices";
        $cat->seo_url_slug_ch = "硬件和设备";
        $cat->seo_url_slug_hi = "हार्डवेयर-और-उपकरण";
        $cat->seo_url_slug_es = "dispositivos-de-hardware";
        $cat->seo_url_slug_ru = "оборудование-и-устройства";
        $cat->seo_url_slug_pt = "hardware-e-dispositivos";
        $cat->seo_url_slug_fr = "matériel-et-appareils";
        $cat->seo_url_slug_de = "hardware-und-geräte";
        $cat->seo_url_slug_th = "ฮาร์ดแวร์และอุปกรณ์";
        $cat->seo_url_slug_br = "hardware-dispositivos";

        $cat->icon = "fa fa-laptop";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 4;
        $cat->webmaster_id = 7;
        $cat->title_ar = "حماية المعلومات";
        $cat->title_en = "Cyber security";
        $cat->title_ch = "网络安全";
        $cat->title_hi = "साइबर सुरक्षा";
        $cat->title_es = "La seguridad cibernética";
        $cat->title_ru = "Информационная безопасность";
        $cat->title_pt = "Cíber segurança";
        $cat->title_fr = "La cyber-sécurité";
        $cat->title_de = "Internet-Sicherheit";
        $cat->title_th = "ความปลอดภัยทางไซเบอร์";
        $cat->title_br = "Cíber segurança";

        $cat->seo_url_slug_ar = "حماية-المعلومات";
        $cat->seo_url_slug_en = "cyber-security";
        $cat->seo_url_slug_ch = "网络安全";
        $cat->seo_url_slug_hi = "साइबर-सुरक्षा";
        $cat->seo_url_slug_es = "la-seguridad-cibernética";
        $cat->seo_url_slug_ru = "информационная-безопасность";
        $cat->seo_url_slug_pt = "cíber-segurança";
        $cat->seo_url_slug_fr = "la-cyber-sécurité";
        $cat->seo_url_slug_de = "internet-sicherheit";
        $cat->seo_url_slug_th = "ความปลอดภัยทางไซเบอร์";
        $cat->seo_url_slug_br = "cíbersegurança";

        $cat->icon = "fa fa-user-secret";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 5;
        $cat->webmaster_id = 7;
        $cat->title_ar = "الذكاء الاصطناعي";
        $cat->title_en = "Artificial Intelligence";
        $cat->title_ch = "人工智能";
        $cat->title_hi = "कृत्रिम होशियारी";
        $cat->title_es = "Inteligencia artificial";
        $cat->title_ru = "Искусственный интеллект";
        $cat->title_pt = "Inteligência artificial";
        $cat->title_fr = "Intelligence artificielle";
        $cat->title_de = "Künstliche Intelligenz";
        $cat->title_th = "ปัญญาประดิษฐ์";
        $cat->title_br = "Inteligência artificial";

        $cat->seo_url_slug_ar = "الذكاء-الاصطناعي";
        $cat->seo_url_slug_en = "artificial-intelligence";
        $cat->seo_url_slug_ch = "人工智能";
        $cat->seo_url_slug_hi = "कृत्रिम-होशियारी";
        $cat->seo_url_slug_es = "inteligencia-artificial";
        $cat->seo_url_slug_ru = "искусственный-интеллект";
        $cat->seo_url_slug_pt = "inteligência-artificial";
        $cat->seo_url_slug_fr = "intelligence-artificielle";
        $cat->seo_url_slug_de = "künstliche-intelligenz";
        $cat->seo_url_slug_th = "ปัญญาประดิษฐ์";
        $cat->seo_url_slug_br = "inteligênciaartificial";

        $cat->icon = "fa fa-edge";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 6;
        $cat->webmaster_id = 7;
        $cat->title_ar = "الواقع الإفتراضي";
        $cat->title_en = "Virtual Reality";
        $cat->title_ch = "虚拟现实";
        $cat->title_hi = "आभासी वास्तविकता";
        $cat->title_es = "Realidad virtual";
        $cat->title_ru = "Виртуальная реальность";
        $cat->title_pt = "Realidade virtual";
        $cat->title_fr = "Réalité virtuelle";
        $cat->title_de = "Virtuelle Realität";
        $cat->title_th = "ความจริงเสมือน";
        $cat->title_br = "Realidade virtual";

        $cat->seo_url_slug_ar = "الواقع-الإفتراضي";
        $cat->seo_url_slug_en = "virtual-reality";
        $cat->seo_url_slug_ch = "虚拟现实";
        $cat->seo_url_slug_hi = "आभासी-वास्तविकता";
        $cat->seo_url_slug_es = "realidad-virtual";
        $cat->seo_url_slug_ru = "виртуальная-реальность";
        $cat->seo_url_slug_pt = "realidade-virtual";
        $cat->seo_url_slug_fr = "réalité-virtuelle";
        $cat->seo_url_slug_de = "virtuelle-realität";
        $cat->seo_url_slug_th = "ความจริงเสมือน";
        $cat->seo_url_slug_br = "realidadevirtual";

        $cat->icon = "fa fa-globe";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        // FAQ

        $cat = new Section();
        $cat->row_no = 1;
        $cat->webmaster_id = 10;
        $cat->title_ar = "الإتفاق والتعاقد";
        $cat->title_en = "Getting Started";
        $cat->title_ch = "开始使用我们";
        $cat->title_hi = "हमारे साथ शुरुआत करना";
        $cat->title_es = "Empezando con nosotros";
        $cat->title_ru = "Начало работы с нами";
        $cat->title_pt = "Começando conosco";
        $cat->title_fr = "Commencer avec nous";
        $cat->title_de = "Starten Sie mit uns durch";
        $cat->title_th = "เริ่มต้นกับเรา";
        $cat->title_br = "Começando conosco";

        $cat->seo_url_slug_ar = "الإتفاق-والتعاقد";
        $cat->seo_url_slug_en = "getting-started";
        $cat->seo_url_slug_ch = "开始使用我们";
        $cat->seo_url_slug_hi = "हमारे-साथ-शुरुआत-करना";
        $cat->seo_url_slug_es = "empezando-con-nosotros";
        $cat->seo_url_slug_ru = "начало-работы-с-нами";
        $cat->seo_url_slug_pt = "começando-conosco";
        $cat->seo_url_slug_fr = "commencer-avec-nous";
        $cat->seo_url_slug_de = "starten-sie-mit-uns-durch";
        $cat->seo_url_slug_th = "เริ่มต้นกับเรา";
        $cat->seo_url_slug_br = "começandoconosco";

        $cat->icon = "";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 2;
        $cat->webmaster_id = 10;
        $cat->title_ar = "الخدمات والرسوم";
        $cat->title_en = "Services & Fees";
        $cat->title_ch = "服务及费用";
        $cat->title_hi = "सेवाएँ एवं शुल्क";
        $cat->title_es = "Servicios y tarifas";
        $cat->title_ru = "Услуги и сборы";
        $cat->title_pt = "Serviços e taxas";
        $cat->title_fr = "Services et frais";
        $cat->title_de = "Dienstleistungen und Gebühren";
        $cat->title_th = "บริการและค่าธรรมเนียม";
        $cat->title_br = "Serviços e taxas";

        $cat->seo_url_slug_ar = "الخدمات-والرسوم";
        $cat->seo_url_slug_en = "services-fees";
        $cat->seo_url_slug_ch = "服务及费用";
        $cat->seo_url_slug_hi = "सेवाएँ-एवं-शुल्क";
        $cat->seo_url_slug_es = "servicios-y-tarifas";
        $cat->seo_url_slug_ru = "услуги-и-сборы";
        $cat->seo_url_slug_pt = "serviços-e-taxas";
        $cat->seo_url_slug_fr = "services-et-frais";
        $cat->seo_url_slug_de = "dienstleistungen-und-gebühren";
        $cat->seo_url_slug_th = "บริการและค่าธรรมเนียม";
        $cat->seo_url_slug_br = "serviços-taxas";

        $cat->icon = "";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 3;
        $cat->webmaster_id = 10;
        $cat->title_ar = "الضمان والإسترجاع";
        $cat->title_en = "Warranty & Return";
        $cat->title_ch = "保修和退货";
        $cat->title_hi = "वारंटी और वापसी";
        $cat->title_es = "Garantía y devolución";
        $cat->title_ru = "Гарантия и возврат";
        $cat->title_pt = "Garantia e Devolução";
        $cat->title_fr = "Garantie et retour";
        $cat->title_de = "Garantie & Rückgabe";
        $cat->title_th = "การรับประกันและการคืนสินค้า";
        $cat->title_br = "Garantia e Devolução";

        $cat->seo_url_slug_ar = "الضمان-والإسترجاع";
        $cat->seo_url_slug_en = "warranty-return";
        $cat->seo_url_slug_ch = "保修和退货";
        $cat->seo_url_slug_hi = "वारंटी-और-वापसी";
        $cat->seo_url_slug_es = "garantía-y-devolución";
        $cat->seo_url_slug_ru = "гарантия-и-возврат";
        $cat->seo_url_slug_pt = "garantia-e-devolução";
        $cat->seo_url_slug_fr = "garantie-et-retour";
        $cat->seo_url_slug_de = "garantie-rückgabe";
        $cat->seo_url_slug_th = "การรับประกันและการคืนสินค้า";
        $cat->seo_url_slug_br = "garantia-devolução";

        $cat->icon = "";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();

        $cat = new Section();
        $cat->row_no = 4;
        $cat->webmaster_id = 10;
        $cat->title_ar = "خدمة ما بعد البيع";
        $cat->title_en = "After Sales Services";
        $cat->title_ch = "售后服务";
        $cat->title_hi = "बिक्री के बाद सेवा";
        $cat->title_es = "Servicios posventa";
        $cat->title_ru = "Послепродажное обслуживание";
        $cat->title_pt = "Послепродажное обслуживание";
        $cat->title_fr = "Services après-vente";
        $cat->title_de = "Kundenbetreuung";
        $cat->title_th = "บริการหลังการขาย";
        $cat->title_br = "Послепродажное обслуживание";

        $cat->seo_url_slug_ar = "خدمة-ما-بعد-البيع";
        $cat->seo_url_slug_en = "After-Sales-Services";
        $cat->seo_url_slug_ch = "售后服务";
        $cat->seo_url_slug_hi = "बिक्री-के-बाद-सेवा";
        $cat->seo_url_slug_es = "Servicios-posventa";
        $cat->seo_url_slug_ru = "Послепродажное-обслуживание";
        $cat->seo_url_slug_pt = "Послепродажное-обслуживание";
        $cat->seo_url_slug_fr = "Services-après-vente";
        $cat->seo_url_slug_de = "Kundenbetreuung";
        $cat->seo_url_slug_th = "บริการหลังการขาย";
        $cat->seo_url_slug_br = "Послепродажноеобслуживание";

        $cat->icon = "";
        $cat->father_id = 0;
        $cat->visits = 0;
        $cat->status = 1;
        $cat->created_by = 1;
        $cat->save();
    }
}
