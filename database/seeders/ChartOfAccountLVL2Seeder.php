<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ChartOfAccountLVL2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        
        $accounts = [
            //Assets
            [
                'id' => '98045de5-3684-4731-a52d-6bfafb53706d',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name' => 'Cash',
                'code' => '01',
                'lvl1ID' => '98045273-c28f-4ba0-9b15-7c6ba8c32c20',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98045df5-3132-4877-aa6c-ea0c3ecaf36a',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'name' => 'Investments',
                'code' => '02',
                'lvl1ID' => '98045273-c28f-4ba0-9b15-7c6ba8c32c20',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98045e07-a816-4105-be42-9e4aee65f4ec',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'name' => 'Receivables',
                'code' => '03',
                'lvl1ID' => '98045273-c28f-4ba0-9b15-7c6ba8c32c20',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98045e18-0ab2-4a4e-b614-518c070897e1',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((3), 4, "0", STR_PAD_LEFT),
                'name' => 'Inventories',
                'code' => '04',
                'lvl1ID' => '98045273-c28f-4ba0-9b15-7c6ba8c32c20',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98045e25-8503-4b6e-a46e-ceaf4ad24245',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((4), 4, "0", STR_PAD_LEFT),
                'name' => 'Prepayments and Deferred Charges',
                'code' => '05',
                'lvl1ID' => '98045273-c28f-4ba0-9b15-7c6ba8c32c20',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98045e35-54d4-4abe-bf6e-6aa60dc388f0',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((5), 4, "0", STR_PAD_LEFT),
                'name' => 'Investment Property',
                'code' => '06',
                'lvl1ID' => '98045273-c28f-4ba0-9b15-7c6ba8c32c20',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98045e42-d28a-4a71-861a-14493ae6079d',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((6), 4, "0", STR_PAD_LEFT),
                'name' => 'Property, Plant and Equipment',
                'code' => '07',
                'lvl1ID' => '98045273-c28f-4ba0-9b15-7c6ba8c32c20',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98045e52-b866-49ce-95f9-6b4a94fb5c4a',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((7), 4, "0", STR_PAD_LEFT),
                'name' => 'Biological Assets',
                'code' => '08',
                'lvl1ID' => '98045273-c28f-4ba0-9b15-7c6ba8c32c20',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98045e62-de0d-44cf-aa68-1b9ca94824b5',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((8), 4, "0", STR_PAD_LEFT),
                'name' => 'Intangible Assets',
                'code' => '09',
                'lvl1ID' => '98045273-c28f-4ba0-9b15-7c6ba8c32c20',
                'created_at' => Carbon::now(),
            ],

            //Liabilities
            [
                'id' => '98046334-9ef4-4b9a-b753-4bfe5a9484d2',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((9), 4, "0", STR_PAD_LEFT),
                'name' => 'Financial  Liabilities',
                'code' => '01',
                'lvl1ID' => '98045291-a025-47ce-ab13-cc593e19850d',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98046346-2ad2-4f80-867f-3ab6acb10894',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((10), 4, "0", STR_PAD_LEFT),
                'name' => 'Inter-Agency Payables',
                'code' => '02',
                'lvl1ID' => '98045291-a025-47ce-ab13-cc593e19850d',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98046354-e49d-4ce8-9fdf-a7bfb7a42ac1',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((11), 4, "0", STR_PAD_LEFT),
                'name' => 'Intra-Agency Payables',
                'code' => '03',
                'lvl1ID' => '98045291-a025-47ce-ab13-cc593e19850d',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98046362-f2a5-47e5-95b1-eaf5802a54d7',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((12), 4, "0", STR_PAD_LEFT),
                'name' => 'Trust Liabilities',
                'code' => '04',
                'lvl1ID' => '98045291-a025-47ce-ab13-cc593e19850d',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98046374-d0ea-48ee-a59b-700b9185354b',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((13), 4, "0", STR_PAD_LEFT),
                'name' => 'Deferred Credits/Unearned Income',
                'code' => '05',
                'lvl1ID' => '98045291-a025-47ce-ab13-cc593e19850d',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98046383-af2f-45bd-bbc1-bb57093c9903',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((14), 4, "0", STR_PAD_LEFT),
                'name' => 'Provisions',
                'code' => '06',
                'lvl1ID' => '98045291-a025-47ce-ab13-cc593e19850d',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98046394-3d19-4c7d-9181-a5f4fe3ae75d',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((15), 4, "0", STR_PAD_LEFT),
                'name' => 'Other Payables',
                'code' => '99',
                'lvl1ID' => '98045291-a025-47ce-ab13-cc593e19850d',
                'created_at' => Carbon::now(),
            ],

            //Equity
            [
                'id' => '980463aa-6b5e-46c5-8ea4-9b6405f664e1',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((16), 4, "0", STR_PAD_LEFT),
                'name' => 'Government Equity',
                'code' => '01',
                'lvl1ID' => '980452ae-0855-4232-a63c-d5fa420ed155',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980463bb-4128-4134-923e-6df4619ab847',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((17), 4, "0", STR_PAD_LEFT),
                'name' => 'Intermediate Accounts',
                'code' => '02',
                'lvl1ID' => '980452ae-0855-4232-a63c-d5fa420ed155',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980463ca-2d72-4f67-93fb-c5335207f7c5',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((18), 4, "0", STR_PAD_LEFT),
                'name' => 'Equity in Joint Venture',
                'code' => '03',
                'lvl1ID' => '980452ae-0855-4232-a63c-d5fa420ed155',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980463d8-09b0-40a9-897c-adaf3bbb62a0',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((19), 4, "0", STR_PAD_LEFT),
                'name' => 'Unrealized Gain/(Loss)',
                'code' => '04',
                'lvl1ID' => '980452ae-0855-4232-a63c-d5fa420ed155',
                'created_at' => Carbon::now(),
            ],

            //Income
            [
                'id' => '980463f6-4843-4f0c-be75-21690aa5459d',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((20), 4, "0", STR_PAD_LEFT),
                'name' => 'Tax Revenue',
                'code' => '01',
                'lvl1ID' => '980452b7-8f7d-44ce-934a-4996e1ffeef2',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98046407-9361-4ee2-8439-2238d89bcb3b',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((21), 4, "0", STR_PAD_LEFT),
                'name' => 'Service and Business Income',
                'code' => '02',
                'lvl1ID' => '980452b7-8f7d-44ce-934a-4996e1ffeef2',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98046417-afa0-4648-af2e-c70fe652b312',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((22), 4, "0", STR_PAD_LEFT),
                'name' => 'Transfers, Assistance',
                'code' => '03',
                'lvl1ID' => '980452b7-8f7d-44ce-934a-4996e1ffeef2',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98046432-bb9a-4d57-b436-29883e10a016',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((23), 4, "0", STR_PAD_LEFT),
                'name' => 'Shares, Grants and Donations',
                'code' => '04',
                'lvl1ID' => '980452b7-8f7d-44ce-934a-4996e1ffeef2',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98046440-87f2-45fa-8a50-57b1efd6d70b',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((24), 4, "0", STR_PAD_LEFT),
                'name' => 'Gains',
                'code' => '05',
                'lvl1ID' => '980452b7-8f7d-44ce-934a-4996e1ffeef2',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '98046450-174b-4e38-8e30-890edb79d8a6',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((25), 4, "0", STR_PAD_LEFT),
                'name' => 'Miscellaneous Income',
                'code' => '06',
                'lvl1ID' => '980452b7-8f7d-44ce-934a-4996e1ffeef2',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '9804645d-0afb-403b-aa30-f54e791c5c7d',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((26), 4, "0", STR_PAD_LEFT),
                'name' => 'Other Non-Operating Income',
                'code' => '07',
                'lvl1ID' => '980452b7-8f7d-44ce-934a-4996e1ffeef2',
                'created_at' => Carbon::now(),
            ],

            //Expenses
            [
                'id' => '9804646b-d697-49ba-b863-5ae8fde27442',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((27), 4, "0", STR_PAD_LEFT),
                'name' => 'Personnel Services',
                'code' => '01',
                'lvl1ID' => '980452c8-d5d9-4f5e-b319-fbcb5cfadffb',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '9804647c-ab84-4a34-8dca-27ba40d1f389',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((28), 4, "0", STR_PAD_LEFT),
                'name' => 'Maintenance and Other Operating Expenses',
                'code' => '02',
                'lvl1ID' => '980452c8-d5d9-4f5e-b319-fbcb5cfadffb',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '9804648a-84f3-475b-a18d-947436f32efe',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((29), 4, "0", STR_PAD_LEFT),
                'name' => 'Financial Expenses',
                'code' => '03',
                'lvl1ID' => '980452c8-d5d9-4f5e-b319-fbcb5cfadffb',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980464a5-3ff6-45f6-ac7b-60aeae5fc845',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((30), 4, "0", STR_PAD_LEFT),
                'name' => 'Direct Costs',
                'code' => '04',
                'lvl1ID' => '980452c8-d5d9-4f5e-b319-fbcb5cfadffb',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => '980464b4-94f4-4038-add7-c21d4d01e02e',
                'refID' => 'CHART-ACC-LVL2'.'-'.str_pad((31), 4, "0", STR_PAD_LEFT),
                'name' => 'Non-Cash Expenses',
                'code' => '05',
                'lvl1ID' => '980452c8-d5d9-4f5e-b319-fbcb5cfadffb',
                'created_at' => Carbon::now(),
            ],
        ];

        DB::table('chart_of_accounts_lvl2')->insert($accounts);

    }


}
