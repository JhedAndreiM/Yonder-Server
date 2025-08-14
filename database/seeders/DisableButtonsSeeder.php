<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisableButtonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
        $data = [
            [
                'key' => 'show_student_org',
                'value' => true,
            ],
            [
                'key' => 'show_marketplace',
                'value' => true,
            ],
        ];

        foreach ($data as $row) {
            DB::table('disable_buttons')->updateOrInsert(
                ['key' => $row['key']], // condition to check if it exists
                [
                    'value' => $row['value'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
    }
}
