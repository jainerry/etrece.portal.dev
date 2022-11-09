<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $objects = [
            'citizen-profiles',
            'employees',
            'building-profiles',
            'faas-machineries',
            'faas-lands',
            'faas-idle-lands',
            'faas-others',
            'users',
            'roles',
            'permissions',
            'office-locations',
            'offices',
            'departments',
            'sections',
            'positions',
            'appointment-statuses',
            'provinces',
            'municipalities',
            'barangays',
            'streets',
            'structural-types',
            'kind-of-buildings',
            'structural-roofs',
            'faas-assessment-statuses',
            'faas-classifications'
        ];

        foreach ($objects as $object) {

            for($i=0; $i<4; $i++ ){
                switch ($i) {
                case 0:
                    $permission_name = 'view-'.$object;
                    break;
                case 1:
                    $permission_name = 'create-'.$object;
                    break;
                case 2:
                    $permission_name = 'edit-'.$object;
                    break;
                default:
                    $permission_name = 'delete-'.$object;
                }

                Permission::create(['name' => $permission_name,'guard_name' => 'backpack']);
            }
        }

        //specific permissions

        Permission::create(['name' => 'rpt-view-assessment-requests','guard_name' => 'backpack']);
        Permission::create(['name' => 'rpt-create-new-assessment-request','guard_name' => 'backpack']);
        Permission::create(['name' => 'rpt-edit-assessment-request','guard_name' => 'backpack']);
        Permission::create(['name' => 'rpt-delete-assessment-request','guard_name' => 'backpack']);
        Permission::create(['name' => 'rpt-approve-assessment-request','guard_name' => 'backpack']);

        $superAdmin = Role::create(['name' => 'Super Admin','guard_name' => 'backpack']);
        $superAdmin->givePermissionTo(Permission::all());

        $superAdminUser = User::create(['name' => 'Super Admin','email' => 'superadmin@etreceportal.com','password' => Hash::make('superadmin@etreceportal.com')]);
        $superAdminUser->assignRole($superAdmin);

        $rptAdmin = Role::create(['name' => 'RPT Admin','guard_name' => 'backpack']);
        $rptAdmin->givePermissionTo([
            'rpt-view-assessment-requests',
            'rpt-create-new-assessment-request',
            'rpt-edit-assessment-request',
            'rpt-approve-assessment-request',
        ]);

        $rptAdminUser = User::create(['name' => 'RPT Admin','email' => 'rptadmin@etreceportal.com','password' => Hash::make('rptadmin@etreceportal.com')]);
        $rptAdminUser->assignRole($rptAdmin);

        $caoUser = Role::create(['name' => 'CAO User','guard_name' => 'backpack']);
        $caoUser->givePermissionTo([
            'rpt-view-assessment-requests',
            'rpt-approve-assessment-request',
        ]);

        $caoUserUser = User::create(['name' => 'CAO User','email' => 'caouser@etreceportal.com','password' => Hash::make('caouser@etreceportal.com')]);
        $caoUserUser->assignRole($caoUser);

        $trsUser = Role::create(['name' => 'TRS User','guard_name' => 'backpack']);

        $trsUserUser = User::create(['name' => 'TRS User','email' => 'trsuser@etreceportal.com','password' => Hash::make('trsuser@etreceportal.com')]);
        $trsUserUser->assignRole($trsUser);

    }
}
