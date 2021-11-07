<?php

namespace Database\Seeders;

use App\Models\RefusePlaceMessage;
use Illuminate\Database\Seeder;

class RefusePlaceMessages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => "Place Exists",
            ],
            [
                'name' => "Irrelevant Pictures",
            ],
            [
                'name' => "Incorrect Localization",
            ],
        ];
        RefusePlaceMessage::insert($data);
    }
}
