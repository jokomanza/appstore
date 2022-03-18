<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\App;
use Illuminate\Pagination\PaginationServiceProvider;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::count() <= 500) {
            factory(App::class , 100)->create();
        }

        if (!DB::table('users')->where('registration_number', 'F2373')->first()) {
            DB::table('users')->insert([
                'registration_number' => 'F2373',
                'name' => 'Joko Supriyanto',
                'email' => 'joko_supriyanto@quick.com',
                'password' => bcrypt('123456'),
            ]);
        }

        if (!DB::table('admins')->where('registration_number', 'F2373')->first()) {
            DB::table('admins')->insert([
                'registration_number' => 'F2373',
                'name' => 'Joko Supriyanto',
                'email' => 'joko_supriyanto@quick.com',
                'password' => bcrypt('123456'),
            ]);
        }

        if (!DB::table('apps')->where('package_name', 'com.quick.quickappstore')->first()) {
            $app = new App();
            $app->name = 'Quick App Store';
            $app->package_name = 'com.quick.quickappstore';
            $app->description = 'Apliaksi mobile untuk sistem quick app store';
            $app->type = 'Tool App';
            $app->icon_url = 'com.quick.quickappstore.icon.default.png';
            $app->repository_url = 'http:git.quick.com/production/quick-appstore-mobile.git';
            $app->api_token = str_random(128);

            $app->save();
        }
    }
}
