<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Customers\Customer;
use App\Models\Catalogs\ProcessStatus;
use App\Models\Catalogs\ReportCategory;
use App\Models\Catalogs\ReportPriority;
use App\Models\Catalogs\ReportSubcategory;
use App\Models\Customers\ClientBackupContact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'customer_id' => $this->faker->randomElement(Customer::all()->pluck('id')->toArray()),
            'client_backup_contacts_id' => $this->faker->randomElement(ClientBackupContact::all()->pluck('id')->toArray()),
            'user_id' => $this->faker->randomElement(User::all()->pluck('id')->toArray()),
            'tracking_folio' => $this->faker->unique()->numberBetween(1000000000, 9999999999),
            'phone' => $this->faker->phoneNumber(),
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'report_category_id' => $this->faker->randomElement(ReportCategory::all()->pluck('id')->toArray()),
            'report_subcategory_id' => $this->faker->randomElement(ReportSubcategory::all()->pluck('id')->toArray()),
            'report_priority_id' => $this->faker->randomElement(ReportPriority::all()->pluck('id')->toArray()),
            'process_status_id' => $this->faker->randomElement(ProcessStatus::all()->pluck('id')->toArray()),
            'description' => $this->faker->text(),
            'should_be_paid' => $this->faker->boolean(),
            'payment_folio' => $this->faker->unique()->numberBetween(1000000000, 9999999999),
            'images_for_pdf' => $this->faker->text(),
        ];
    }
}
