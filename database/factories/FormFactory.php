<?php

namespace Database\Factories;

use App\Models\AppType;
use App\Models\Block;
use App\Models\Form;
use App\Models\Phase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Form>
 */
class FormFactory extends Factory
{
    protected $model = Form::class;

    public function definition(): array
    {
        return [
            'block_id' => Block::inRandomOrder()->first()?->id ?? Block::factory()->create()->id,
            'form_no' => Str::uuid()->toString(),
            'inv_reg_no' => fake()->optional()->bothify('INV-####'),
            'client_name' => fake()->name(),
            'client_cnic' => fake()->numerify('#####-#######-#'),
            'tracking_code' => strtoupper(Str::random(10)),
            'size' => fake()->randomElement(config('form_options.app_sizes')),
            'reg_type' => 'residential',
            'box_no' => now()->format('dmy'),
            'address' => fake()->address(),
            'office_id' => fake()->numberBetween(1, 5),
            'sr_no' => fake()->numberBetween(1, 1000),
            'user_id' => 1,
            'down_payment' => fake()->numberBetween(10000, 500000),
            'society_id' => fake()->numberBetween(1, 10),
            'contact' => fake()->phoneNumber(),
            'deposite_slip_no' => strtoupper(Str::random(12)),
            'form_type' => AppType::inRandomOrder()->first()?->id ?? AppType::factory()->create()->id,
            'plot_price' => fake()->numberBetween(500000, 2000000),
            'dealer_name' => fake()->company(),
            'villa_no' => fake()->optional()->bothify('Villa ###'),
            'is_create_live' => fake()->boolean(30),
            'payment_plan_id' => fake()->optional()->numberBetween(1, 5),
            'qr_code' => 'INV-'.strtoupper(Str::random(10)),
            'system_created_at' => fake()->optional()->dateTime(),
            'is_member_transfer' => fake()->boolean(10),
            'cron_updated_at' => fake()->optional()->dateTime(),
            'dealer_id' => fake()->optional()->numberBetween(1, 20),
            'submitted_by' => fake()->name(),
            'submitter_cnic' => fake()->numerify('#####-#######-#'),
            'phase_id' => Phase::inRandomOrder()->first()?->id ?? Phase::factory()->create()->id,
            'return_issue_id' => fake()->optional()->numberBetween(1, 5),
            'submitter_contact' => fake()->phoneNumber(),
            'awami_green_form_live_id' => fake()->optional()->numberBetween(1, 10),
        ];
    }
}
