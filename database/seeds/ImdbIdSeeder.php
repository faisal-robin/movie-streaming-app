<?php

use Illuminate\Database\Seeder;
Use Illuminate\Support\Facades\DB;

class ImdbIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('imdb_ids')->insert([
            [
                'imdb_id' => 'tt0944947',
                'is_active' => 1,
            ],
            [
                'imdb_id' => 'tt2084970',
                'is_active' => 1,
            ],
            [
                'imdb_id' => 'tt1515091',
                'is_active' => 1,
            ],
            [
                'imdb_id' => 'tt10919420',
                'is_active' => 1,
            ],
            [
                'imdb_id' => 'tt0119174',
                'is_active' => 1,
            ],
            [
                'imdb_id' => 'tt0259711',
                'is_active' => 1,
            ],
            [
                'imdb_id' => 'tt1034314',
                'is_active' => 1,
            ],
            [
                'imdb_id' => 'tt2057392',
                'is_active' => 1,
            ],
            [
                'imdb_id' => 'tt6402468',
                'is_active' => 1,
            ],
            [
                'imdb_id' => 'tt0106912',
                'is_active' => 1,
            ],
        ]);
    }
}
