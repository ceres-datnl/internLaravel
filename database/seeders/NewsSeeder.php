<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i<=1000;$i++ ){
            \DB::table('news')->insert([
                'category_id' => rand(1,10),
                'title' => "Bài viết thứ ".$i,
                'content' => Str::random(300),
                'file_id'     => null,
                'status'  => rand(0,2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
