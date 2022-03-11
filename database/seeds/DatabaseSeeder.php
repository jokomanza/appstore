<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        factory(App\Models\App::class, 100)->create();

        DB::table('users')->insert([
            'name' => 'Joko Supriyanto',
            'email' => 'joko_supriyanto@quick.com',
            'access_level' => 3,
            'password' => bcrypt('123456'),
        ]);

        DB::table('apps')->insert([
            'name' => 'Quick App Store',
            'package_name' => 'com.quick.quickappstore',
            'description' => 'Aplikasi untuk mengelola semua aplikasi',
            'type' => 'Tool App',
            'icon_url' => 'https://google.com',
            'repository_url' => 'http:git.quick.com/production/quick-appstore-mobile.git',
        ]);
    }
}
