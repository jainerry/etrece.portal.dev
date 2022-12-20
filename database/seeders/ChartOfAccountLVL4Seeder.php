<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ChartOfAccountLVL4Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $accounts = [
            //98058149-ca3f-4595-98af-2b9bdb85d9b4
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((0), 4, "0", STR_PAD_LEFT),
                'name' => 'Cash Local Treasury',
                'code' => '010',
                'lvl3ID' => '98058149-ca3f-4595-98af-2b9bdb85d9b4',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((1), 4, "0", STR_PAD_LEFT),
                'name' => 'Petty Cash',
                'code' => '020',
                'lvl3ID' => '98058149-ca3f-4595-98af-2b9bdb85d9b4',
                'created_at' => Carbon::now(),
            ],
            //980585ea-28b5-4c65-a9ce-9e7497625a32
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((2), 4, "0", STR_PAD_LEFT),
                'name' => 'Cash in Bank - Local Currency, Current Account',
                'code' => '010',
                'lvl3ID' => '980585ea-28b5-4c65-a9ce-9e7497625a32',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((3), 4, "0", STR_PAD_LEFT),
                'name' => 'Cash in Bank - Local Currency, Savings Account',
                'code' => '020',
                'lvl3ID' => '980585ea-28b5-4c65-a9ce-9e7497625a32',
                'created_at' => Carbon::now(),
            ],
            //98058609-fabb-461f-861e-f0d26b126ce7
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((4), 4, "0", STR_PAD_LEFT),
                'name' => 'Cash in Bank - Foreign Currency, Current Account',
                'code' => '010',
                'lvl3ID' => '98058609-fabb-461f-861e-f0d26b126ce7',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((5), 4, "0", STR_PAD_LEFT),
                'name' => 'Cash in Bank - Foreign Currency, Savings Account',
                'code' => '020',
                'lvl3ID' => '98058609-fabb-461f-861e-f0d26b126ce7',
                'created_at' => Carbon::now(),
            ],
            //98058622-4a08-4f64-aa58-ccd3d3d09f2f
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((6), 4, "0", STR_PAD_LEFT),
                'name' => 'Cash in Bank -Local Currency, Time Deposits',
                'code' => '010',
                'lvl3ID' => '98058622-4a08-4f64-aa58-ccd3d3d09f2f',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((7), 4, "0", STR_PAD_LEFT),
                'name' => 'Cash in Bank - Foreign Currency, Time Deposits',
                'code' => '020',
                'lvl3ID' => '98058622-4a08-4f64-aa58-ccd3d3d09f2f',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((8), 4, "0", STR_PAD_LEFT),
                'name' => 'Treasury Bills',
                'code' => '030',
                'lvl3ID' => '98058622-4a08-4f64-aa58-ccd3d3d09f2f',
                'created_at' => Carbon::now(),
            ],
            //98058641-760d-4629-ac6e-00b05ceab11b
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((9), 4, "0", STR_PAD_LEFT),
                'name' => 'Financial Assets Held for Trading',
                'code' => '010',
                'lvl3ID' => '98058641-760d-4629-ac6e-00b05ceab11b',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((10), 4, "0", STR_PAD_LEFT),
                'name' => 'Financial  Assets Designated at Fair Value Through Surplus or Deficit',
                'code' => '020',
                'lvl3ID' => '98058641-760d-4629-ac6e-00b05ceab11b',
                'created_at' => Carbon::now(),
            ],
            //98058652-f879-4417-bfcd-1ab26b99e70d
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((11), 4, "0", STR_PAD_LEFT),
                'name' => 'Investments in Treasury Bills - Local',
                'code' => '010',
                'lvl3ID' => '98058652-f879-4417-bfcd-1ab26b99e70d',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((12), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment - Investments in Treasury Bills - Local',
                'code' => '011',
                'lvl3ID' => '98058652-f879-4417-bfcd-1ab26b99e70d',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((13), 4, "0", STR_PAD_LEFT),
                'name' => 'Investments in  Bonds-Local',
                'code' => '020',
                'lvl3ID' => '98058652-f879-4417-bfcd-1ab26b99e70d',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((14), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment - Investments in Bonds - Local',
                'code' => '021',
                'lvl3ID' => '98058652-f879-4417-bfcd-1ab26b99e70d',
                'created_at' => Carbon::now(),
            ],
            //98058665-bc7f-4fe6-a374-046392feb6eb
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((15), 4, "0", STR_PAD_LEFT),
                'name' => 'Investments in Stocks',
                'code' => '010',
                'lvl3ID' => '98058665-bc7f-4fe6-a374-046392feb6eb',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((16), 4, "0", STR_PAD_LEFT),
                'name' => 'Investments in Bonds',
                'code' => '020',
                'lvl3ID' => '98058665-bc7f-4fe6-a374-046392feb6eb',
                'created_at' => Carbon::now(),
            ],
            //98058677-92e2-44f7-84d7-c25f32760208
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((17), 4, "0", STR_PAD_LEFT),
                'name' => 'Deposits on Letters of Credit',
                'code' => '010',
                'lvl3ID' => '98058677-92e2-44f7-84d7-c25f32760208',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((18), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment - Deposits in Letters of Credit',
                'code' => '011',
                'lvl3ID' => '98058677-92e2-44f7-84d7-c25f32760208',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((19), 4, "0", STR_PAD_LEFT),
                'name' => 'Guaranty Deposits',
                'code' => '020',
                'lvl3ID' => '98058677-92e2-44f7-84d7-c25f32760208',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((20), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment - Guaranty Deposits',
                'code' => '021',
                'lvl3ID' => '98058677-92e2-44f7-84d7-c25f32760208',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((21), 4, "0", STR_PAD_LEFT),
                'name' => 'Other Investments',
                'code' => '990',
                'lvl3ID' => '98058677-92e2-44f7-84d7-c25f32760208',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((22), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment - Other Investments',
                'code' => '991',
                'lvl3ID' => '98058677-92e2-44f7-84d7-c25f32760208',
                'created_at' => Carbon::now(),
            ],
            //98058689-0316-4701-afac-d956d6cacc0e
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((23), 4, "0", STR_PAD_LEFT),
                'name' => 'Investments in Joint Venture',
                'code' => '010',
                'lvl3ID' => '98058689-0316-4701-afac-d956d6cacc0e',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((24), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment - Investments in Joint Venture',
                'code' => '011',
                'lvl3ID' => '98058689-0316-4701-afac-d956d6cacc0e',
                'created_at' => Carbon::now(),
            ],
            //98058699-5301-4ebe-ae33-68204fc46c6e
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((25), 4, "0", STR_PAD_LEFT),
                'name' => 'Sinking Fund',
                'code' => '010',
                'lvl3ID' => '98058699-5301-4ebe-ae33-68204fc46c6e',
                'created_at' => Carbon::now(),
            ],
            //980586c6-3172-40b9-af2f-ea42df33de60
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((26), 4, "0", STR_PAD_LEFT),
                'name' => 'Accounts Receivable',
                'code' => '010',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((27), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment - Accounts Receivable',
                'code' => '011',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((28), 4, "0", STR_PAD_LEFT),
                'name' => 'Real Property Tax Receivable',
                'code' => '020',
                'lvl1ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((29), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment - RPT Receivable',
                'code' => '021',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((30), 4, "0", STR_PAD_LEFT),
                'name' => 'Special Education Tax Receivable',
                'code' => '030',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((31), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment - SET Receivable',
                'code' => '031',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((32), 4, "0", STR_PAD_LEFT),
                'name' => 'Notes Receivable',
                'code' => '040',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((33), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment - Notes Receivable',
                'code' => '041',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((34), 4, "0", STR_PAD_LEFT),
                'name' => 'Loans Receivable - Government-Owned and/or Controlled Corporations',
                'code' => '050',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((35), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment - Loans Receivable - Government-Owned and/or Controlled Corporations',
                'code' => '051',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((36), 4, "0", STR_PAD_LEFT),
                'name' => 'Loans Receivable - Local Government Units',
                'code' => '060',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((37), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment  - Loans Receivable - Local Government Units',
                'code' => '061',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((38), 4, "0", STR_PAD_LEFT),
                'name' => 'Interests Receivable',
                'code' => '070',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((39), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment - Interests Receivable',
                'code' => '071',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((40), 4, "0", STR_PAD_LEFT),
                'name' => 'Dividends Receivable',
                'code' => '080',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((41), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment - Dividends Receivable',
                'code' => '081',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((42), 4, "0", STR_PAD_LEFT),
                'name' => 'Loans Receivable - Others',
                'code' => '990',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => STR::uuid(),
                'refID' => 'CHART-ACC-LVL4'.'-'.str_pad((43), 4, "0", STR_PAD_LEFT),
                'name' => 'Allowance for Impairment - Loans Receivable - Others',
                'code' => '991',
                'lvl3ID' => '980586c6-3172-40b9-af2f-ea42df33de60',
                'created_at' => Carbon::now(),
            ],
        ];
        
        DB::table('chart_of_accounts_LVL4')->insert($accounts);

    }


}
