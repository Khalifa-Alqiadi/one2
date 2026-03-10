<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Popup;

class PopupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $Popup = new Popup;
        $Popup->row_no = 1;

        $Popup->title_ar = "نافذة منبثقة للاشتراك في النشرة الإخبارية";
        $Popup->title_en = "Newsletter subscribe popup";
        $Popup->title_ch = "时事通讯订阅弹出窗口";
        $Popup->title_hi = "न्यूज़लैटर सदस्यता पॉपअप";
        $Popup->title_es = "Ventana emergente de suscripción al boletín informativo";
        $Popup->title_ru = "Всплывающее окно подписки на рассылку новостей";
        $Popup->title_pt = "Pop-up de subscrição de newsletters";
        $Popup->title_fr = "Fenêtre contextuelle d'inscription à la newsletter";
        $Popup->title_de = "Popup zum Abonnieren des Newsletters";
        $Popup->title_th = "ป๊อปอัปสมัครรับจดหมายข่าว";
        $Popup->title_br = "Pop-up de inscrição no boletim informativo";

        $Popup->details_ar = "<br><div><h1>النشرة البريدية</h1><p>اشترك الآن في النشرة البريدية لدينا لتلقي آخر التحديثات.</p></div><br>";
        $Popup->details_en = "<br><div><h1>Subscribe Now</h1><p>Sign up for our newsletter to receive the latest updates.</p></div><br>";
        $Popup->details_ch = "<br><div><h1>立即订阅</h1><p>注册我们的新闻通讯以接收最新更新。</p></div><br>";
        $Popup->details_hi = "<br><div><h1>अभी सदस्यता लें</h1><p>नवीनतम अपडेट प्राप्त करने के लिए हमारे न्यूज़लेटर के लिए साइन अप करें।</p></div><br>";
        $Popup->details_es = "<br><div><h1>Suscríbete ahora</h1><p>Suscríbete a nuestro boletín para recibir las últimas novedades.</p></div><br>";
        $Popup->details_ru= "<br><div><h1>Подпишитесь сейчас</h1><p>Подпишитесь на нашу рассылку, чтобы получать последние обновления.</p></div><br>";
        $Popup->details_pt = "<br><div><h1>Inscreva-se já</h1><p>Subscreva a nossa newsletter para receber as últimas atualizações.</p></div><br>";
        $Popup->details_fr = "<br><div><h1>Abonnez-vous maintenant</h1><p>Inscrivez-vous à notre newsletter pour recevoir les dernières mises à jour.</p></div><br>";
        $Popup->details_de = "<br><div><h1>Jetzt abonnieren</h1><p>Melden Sie sich für unseren Newsletter an, um die neuesten Updates zu erhalten.</p></div><br>";
        $Popup->details_th = "<br><div><h1>สมัครสมาชิกทันที</h1><p>สมัครรับจดหมายข่าวของเราเพื่อรับข้อมูลอัปเดตล่าสุด</p></div><br>";
        $Popup->details_br = "<br><div><h1>Assine agora</h1><p>Assine nossa newsletter para receber as últimas atualizações.</p></div><br>";


        $Popup->settings = '{"background_color":"#ffffff","period":"0","closable":"1","delay":"5","width":"700","height":"320","backdrop_opacity":"70","photo_position":"1"}';
        $Popup->photo = "newsletter.png";

        $Popup->show_in = 0;
        $Popup->form_id = -1;
        $Popup->status = 1;
        $Popup->created_by = 1;
        $Popup->save();

    }
}
