<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Permissions;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $Permissions = new Permissions();
        $Permissions->name = "Webmaster";
        $Permissions->view_status = false;
        $Permissions->add_status = true;
        $Permissions->edit_status = true;
        $Permissions->delete_status = true;
        $Permissions->active_status = true;
        $Permissions->analytics_status = true;
        $Permissions->inbox_status = true;
        $Permissions->newsletter_status = true;
        $Permissions->calendar_status = true;
        $Permissions->banners_status = true;
        $Permissions->settings_status = true;
        $Permissions->roles_status = true;
        $Permissions->popups_status = true;
        $Permissions->tags_status = true;
        $Permissions->menus_status = true;
        $Permissions->file_manager_status = true;
        $Permissions->webmaster_status = true;
        $Permissions->modules_status = true;
        $Permissions->data_sections = "1,2,3,4,5,6,7,8,9,10,11,12,13,14";
        $Permissions->status = true;
        $Permissions->created_by = 1;
        $Permissions->save();

        $Permissions = new Permissions();
        $Permissions->name = "Website Manager";
        $Permissions->view_status = false;
        $Permissions->add_status = true;
        $Permissions->edit_status = true;
        $Permissions->delete_status = true;
        $Permissions->active_status = true;
        $Permissions->analytics_status = true;
        $Permissions->inbox_status = true;
        $Permissions->newsletter_status = true;
        $Permissions->calendar_status = true;
        $Permissions->banners_status = true;
        $Permissions->settings_status = true;
        $Permissions->roles_status = true;
        $Permissions->popups_status = true;
        $Permissions->tags_status = true;
        $Permissions->menus_status = true;
        $Permissions->file_manager_status = true;
        $Permissions->webmaster_status = false;
        $Permissions->modules_status = false;
        $Permissions->data_sections = "1,2,3,4,5,6,7,8,9,10,11,12,13,14";
        $Permissions->status = true;
        $Permissions->created_by = 1;
        $Permissions->save();


        $Permissions = new Permissions();
        $Permissions->name = "Limited User";
        $Permissions->view_status = true;
        $Permissions->add_status = true;
        $Permissions->edit_status = true;
        $Permissions->delete_status = false;
        $Permissions->active_status = false;
        $Permissions->analytics_status = false;
        $Permissions->inbox_status = false;
        $Permissions->newsletter_status = false;
        $Permissions->calendar_status = true;
        $Permissions->banners_status = false;
        $Permissions->settings_status = false;
        $Permissions->roles_status = false;
        $Permissions->popups_status = false;
        $Permissions->tags_status = false;
        $Permissions->menus_status = false;
        $Permissions->file_manager_status = false;
        $Permissions->webmaster_status = false;
        $Permissions->modules_status = false;
        $Permissions->data_sections = "1,2,3,4,5,6,7,8,9,10,11,12,13,14";
        $Permissions->status = true;
        $Permissions->created_by = 1;
        $Permissions->save();

    }
}
