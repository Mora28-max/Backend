<?php

namespace Database\Seeders;

use App\Models\catalogs\ServiceStatus;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RolePermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ClassificationTypeSeeder::class);
        $this->call(CustomerTypeSeeder::class);
        $this->call(ServiceStatusSeeder::class);
        $this->call(ServiceTypeSeeder::class);
        $this->call(UseOfTypeSeeder::class);
        $this->call(ColonieSeeder::class);
        $this->call(ZoneSeeder::class);
        $this->call(ReportCategorySeeder::class);
        $this->call(ReportSubcategorySeeder::class);
        $this->call(ReportPrioritiesSeeder::class);
        $this->call(StatusProcessSeeder::class);
        $this->call(PaymentTypesSeeder::class);
        $this->call(MonthlyPaymentStatementsSeeder::class);
        $this->call(ClassificationUseSeeder::class);
        $this->call(DrainagePercentageSeeder::class);
        $this->call(ExtraordinaryAccountsSeeder::class);
        $this->call(ClassificationMaterialsSeeder::class);
        $this->call(UnitiesSeeder::class);
        $this->call(ReportChildSubcategoriesSeeder::class);
        $this->call(CommitteesSeeder::class);
        $this->call(AdditionalObservationsSeeder::class);
        $this->call(NoticeTypeSeeder::class);
    }
}
