<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'root',
            'email' => 'root@your.email',
            'password' => bcrypt('secret'),
        ]);
        Admin::create([
            'name' => 'admin',
            'email' => 'admin@your.email',
            'password' => bcrypt('secret'),
        ]);

    }
}
