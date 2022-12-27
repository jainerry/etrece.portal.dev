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
            'Citizens',
            'Employees',
            'FAAS Masterlist',
            'FAAS Masterlist > Lands',
            'FAAS Masterlist > Buildings',
            'FAAS Masterlist > Machineries',
            'Authentication',
            'Authentication > Users',
            'Authentication > Roles',
            'Authentication > Permissions',
            'Configurations',
            'Configurations > Regions',
            'Configurations > Provinces',
            'Configurations > Cities',
            'Configurations > Barangays',
            'Configurations > Streets',
            'FAAS Configurations',
            'FAAS Configurations > Structural Types',
            'FAAS Configurations > Structural Roofs',
            'FAAS Configurations > Land Classifications',
            'FAAS Configurations > Building Classifications',
            'FAAS Configurations > Machinery Classifications',
            'FAAS Configurations > Structural Floorings',
            'FAAS Configurations >Structural Wallings',
            'Treasury Configurations',
            'Treasury Configurations > CTC Types',
            'Treasury Configurations > RPT Rates',
            'RPT Assessments',
            'RPT Assessments > Lands',
            'RPT Assessments > Buildings',
            'RPT Assessments > Machineries',
            'Treasury',
            'Treasury > RPT',
            'Treasury > Business',
            'Treasury > CTC',
            'Treasury > Other',
            'Chart of Accounts',
            'Chart of Accounts > Level 1',
            'Chart of Accounts > Level 2',
            'Chart of Accounts > Level 3',
            'Chart of Accounts > Level 4',
            'Business',
            'Business > Business Profile',
            'Business > Name Profiles',
            'Business > Business Vehicles',
            'Business > Business Types',
            'Business > Business Activities',
            'Business > Business Categories',
            'Business > Business Fees',
            'Business > Business Tax Fees',
            'Business > Business Tax Assessments',
            'Transaction Logs',
            'Modules',
        ];

        //Create Permissions
        foreach ($objects as $key => $object) {
            Permission::create(['name' => $object,'guard_name' => 'backpack']);
        }

        //Create Roles & Users
        $superAdmin = Role::create(['name' => 'Super Admin','guard_name' => 'backpack']);
        $superAdmin->givePermissionTo(Permission::all());
        $superAdminUser = User::create(['name' => 'Super Admin','email' => 'superadmin@etreceportal.com','password' => Hash::make('superadmin@etreceportal.com')]);
        $superAdminUser->assignRole($superAdmin);

        $rptAdmin = Role::create(['name' => 'RPT Admin','guard_name' => 'backpack']);
        $rptAdmin->givePermissionTo([
            'RPT Assessments',
            'RPT Assessments > Lands',
            'RPT Assessments > Buildings',
            'RPT Assessments > Machineries',
        ]);
        $rptAdminUser = User::create(['name' => 'RPT Admin','email' => 'rptadmin@etreceportal.com','password' => Hash::make('rptadmin@etreceportal.com')]);
        $rptAdminUser->assignRole($rptAdmin);

        $trsUser = Role::create(['name' => 'TRS User','guard_name' => 'backpack']);
        $trsUser->givePermissionTo([
            'Treasury',
            'Treasury > RPT',
            'Treasury > Business',
            'Treasury > CTC',
            'Treasury > Other',
        ]);
        $trsUserUser = User::create(['name' => 'TRS User','email' => 'trsuser@etreceportal.com','password' => Hash::make('trsuser@etreceportal.com')]);
        $trsUserUser->assignRole($trsUser);

    }
}
