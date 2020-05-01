<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('TestingDataSeeder');
        $this->call('WorkplacesTableSeeder');
        $this->call('DevicesTableSeeder');
        $this->call('TypesTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('CustomersTableSeeder');
        $this->call('SuppliersTableSeeder');
        $this->call('UnitsTableSeeder');
        $this->call('AccessoriesTableSeeder');
        $this->call('ConceptionsTableSeeder');
        $this->call('ActivityLogsTableSeeder');
        $this->call('BlocksTableSeeder');
        $this->call('ShelvesTableSeeder');
        $this->call('CasesTableSeeder');
        $this->call('CountingSessionsTableSeeder');
        $this->call('CheckingSessionsTableSeeder');
        $this->call('StoringSessionsTableSeeder');
        $this->call('ReceivingSessionsTableSeeder');
        $this->call('ReceivedGroupsTableSeeder');
        $this->call('OrdersTableSeeder');
        $this->call('OrderedItemsTableSeeder');
        $this->call('ImportsTableSeeder');
        $this->call('SendbackingSessionsTableSeeder');
        $this->call('ClassifiedItemsTableSeeder');
        $this->call('ImportedItemsTableSeeder');
        $this->call('RestorationsTableSeeder');
        $this->call('RestoredItemsTableSeeder');
        $this->call('SitesTableSeeder');
        $this->call('InDistributionsTableSeeder');
        $this->call('InDistributedItemsTableSeeder');
        $this->call('InTransfersTableSeeder');
        $this->call('InTransferredItemsTableSeeder');
        $this->call('ArrangingSessionsTableSeeder');
        $this->call('ClassifyingSessionsTableSeeder');
        $this->call('VerifyingSessionsTableSeeder');
        $this->call('AdjustingSessionTableSeeder');
        $this->call('DiscardingSessionsTableSeeder');
        $this->call('MovingSessionsTableSeeder');
        $this->call('ReplacingSessionsTableSeeder');
        $this->call('ProgressingSessionsTableSeeder');
        $this->call('ReturningSessionsTableSeeder');
        $this->call('IssuingSessionsTableSeeder');
        $this->call('IssuedItemsTableSeeder');
        $this->call('IssuedGroupsTableSeeder');
        $this->call('DemandsTableSeeder');
        $this->call('DemandItemsTableSeeder');
        $this->call('OutDistributionsTableSeeder');
        $this->call('OutDistributedItemsTableSeeder');
        $this->call('EntriesTableSeeder');
        $this->call('ReceivedGroupsArrangingSessionsTableSeeder');
        $this->call('AccessoriesConceptionsTableSeeder');
    }
}
