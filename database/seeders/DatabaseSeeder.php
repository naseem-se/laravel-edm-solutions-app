<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('users')->insert([
            [
                'full_name' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'phone_number' => '03001234567',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'is_google' => false,
                'is_apple' => false,
                'code' => 1234,
                'id_card' => 'alice_id.pdf',
                'certificate' => 'alice_cert.pdf',
                'is_verified' => true,
                'role' => 'facility_mode',
            ],
            [
                'full_name' => 'Bob Williams',
                'email' => 'bob@example.com',
                'phone_number' => '03007654321',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'is_google' => false,
                'is_apple' => false,
                'code' => 5678,
                'id_card' => 'bob_id.pdf',
                'certificate' => 'bob_cert.pdf',
                'is_verified' => true,
                'role' => 'facility_mode',
            ],
            [
                'full_name' => 'Charlie Davis',
                'email' => 'charlie@example.com',
                'phone_number' => '03001112233',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'is_google' => false,
                'is_apple' => false,
                'code' => 4321,
                'id_card' => 'charlie_id.pdf',
                'certificate' => 'charlie_cert.pdf',
                'is_verified' => true,
                'role' => 'worker_mode',
            ],
        ]);

        DB::table('shifts')->insert([
            [
                'user_id' => rand(1, 2),
                'date' => Carbon::now()->subDays(1)->toDateString(),
                'pay_per_hour' => 25.50,
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'license_type' => 'Driver License',
                'special_instruction' => 'Bring safety gear and ID card.',
                'location' => 'New York, NY',
            ],
            [
                'user_id' => rand(1, 2),
                'date' => Carbon::now()->subDays(2)->toDateString(),
                'pay_per_hour' => 22.75,
                'start_time' => '08:30:00',
                'end_time' => '16:30:00',
                'license_type' => 'Forklift Operator',
                'special_instruction' => 'Report to warehouse B entrance.',
                'location' => 'Chicago, IL',
            ],
            [
                'user_id' => rand(1, 2),
                'date' => Carbon::now()->subDays(3)->toDateString(),
                'pay_per_hour' => 28.00,
                'start_time' => '07:00:00',
                'end_time' => '15:00:00',
                'license_type' => 'Commercial License',
                'special_instruction' => 'Check-in at gate 3.',
                'location' => 'Dallas, TX',
            ],
            [
                'user_id' => rand(1, 2),
                'date' => Carbon::now()->subDays(4)->toDateString(),
                'pay_per_hour' => 24.00,
                'start_time' => '10:00:00',
                'end_time' => '18:00:00',
                'license_type' => 'Driver License',
                'special_instruction' => 'Uniform required.',
                'location' => 'Los Angeles, CA',
            ],
            [
                'user_id' => rand(1, 2),
                'date' => Carbon::now()->subDays(5)->toDateString(),
                'pay_per_hour' => 30.00,
                'start_time' => '06:00:00',
                'end_time' => '14:00:00',
                'license_type' => 'Heavy Machinery Operator',
                'special_instruction' => 'Safety briefing at 5:45 AM.',
                'location' => 'Houston, TX',
            ],
            [
                'user_id' => rand(1, 2),
                'date' => Carbon::now()->subDays(6)->toDateString(),
                'pay_per_hour' => 20.00,
                'start_time' => '11:00:00',
                'end_time' => '19:00:00',
                'license_type' => 'None',
                'special_instruction' => 'Lunch break at 2 PM.',
                'location' => 'Miami, FL',
            ],
            [
                'user_id' => rand(1, 2),
                'date' => Carbon::now()->subDays(7)->toDateString(),
                'pay_per_hour' => 26.50,
                'start_time' => '09:30:00',
                'end_time' => '17:30:00',
                'license_type' => 'Driver License',
                'special_instruction' => 'Check route sheet before departure.',
                'location' => 'Seattle, WA',
            ],
            [
                'user_id' => rand(1, 2),
                'date' => Carbon::now()->subDays(8)->toDateString(),
                'pay_per_hour' => 27.00,
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'license_type' => 'Forklift Operator',
                'special_instruction' => 'Wear reflective vest.',
                'location' => 'Denver, CO',
            ],
            [
                'user_id' => rand(1, 2),
                'date' => Carbon::now()->subDays(9)->toDateString(),
                'pay_per_hour' => 23.75,
                'start_time' => '10:00:00',
                'end_time' => '18:00:00',
                'license_type' => 'Driver License',
                'special_instruction' => 'Pickup documents before leaving.',
                'location' => 'Boston, MA',
            ],
            [
                'user_id' => rand(1, 2),
                'date' => Carbon::now()->subDays(10)->toDateString(),
                'pay_per_hour' => 29.50,
                'start_time' => '07:30:00',
                'end_time' => '15:30:00',
                'license_type' => 'Commercial License',
                'special_instruction' => 'End-of-day vehicle inspection required.',
                'location' => 'San Francisco, CA',
            ],
        ]);
    }
}
