<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\NotificationsSetting;
use App\Models\settings;
use App\Models\SmsTemplate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\MailTemplates\Models\MailTemplate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'apikey'            => ['manage', 'create', 'edit', 'delete', 'export'],
        ];



        $role = Role::firstOrCreate([
            'name' => 'Admin'
        ]);
        foreach ($permissions as $module => $adminpermission) {
            Module::firstOrCreate(['name' => $module]);
            foreach ($adminpermission as $permission) {
                $temp = Permission::firstOrCreate(['name' => $permission . '-' . $module]);
                $role->givePermissionTo($temp);
            }
        }

    }
}
