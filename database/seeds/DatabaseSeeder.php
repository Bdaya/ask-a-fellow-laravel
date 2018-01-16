<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            'first_name' => 'Hossam',
            'last_name' => 'Ahmed',
            'email' => 'theprincehossam2008@gmail.com',
            'password' => '12345678',
            'role' => 1
        ]);

        DB::table('users')->insert([
            'first_name' => 'Mohamed',
            'last_name' => 'El Zarei',
            'email' => 'mohamedelzarei@gmail.com',
            'password' => 'helloworld',
            'role' => 1
        ]);

        // to be added by admin afterwards
        DB::table('components_categories')->insert([
            'name' => 'General Engineering'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Computer Science and Engineering'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Digital Media Engineering and Technology'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Communication Engineering'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Electronics Engineering'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Networks Engineering'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Materials Engineering'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Mechatronics Engineering'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Design and Production Engineering'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Civil Engineering'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Architecture Engineering'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Pharmacy & Biotechnology'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Biotechnology'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'General Management'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Business Informatics'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'General Applied Sciences and Arts'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Graphic Design'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Product Design'
        ]);

        DB::table('components_categories')->insert([
            'name' => 'Media Design'
        ]);

        DB::table('stores')->insert([
            'name' => 'Genedy Store',
            'location' => 'Genedy Street',
            'rate' => 'genedymohamed96@gmail.com',
            'review' => 'helloworld',
            'description' => 'this is one of the best stores',
            'phone' => '01122265156',
        ]);
        DB::table('stores')->insert([
            'name' => 'My Store',
            'location' => 'My Street',
            'rate' => 'my@gmail.com',
            'review' => 'helloworld',
            'description' => 'this is one of the best stores',
            'phone' => '01122265156',
        ]);
        DB::table('stores')->insert([
            'name' => 'i Store',
            'location' => 'i Street',
            'rate' => 'i@gmail.com',
            'review' => 'helloworld',
            'description' => 'this is one of the best stores',
            'phone' => '01122265156',
        ]);
        DB::table('stores')->insert([
            'name' => 'compume',
            'location' => 'Genedy Street',
            'rate' => 'genedymohamed96@gmail.com',
            'review' => 'helloworld',
            'description' => 'this is one of the best stores',
            'phone' => '01122265156',
        ]);
        DB::table('stores')->insert([
            'name' => 'mellome',
            'location' => 'Genedy Street',
            'rate' => 'genedymohamed96@gmail.com',
            'review' => 'helloworld',
            'description' => 'this is one of the best stores',
            'phone' => '01122265156',
        ]);
        DB::table('stores')->insert([
            'name' => 'lavastone',
            'location' => 'Genedy Street',
            'rate' => 'genedymohamed96@gmail.com',
            'review' => 'helloworld',
            'description' => 'this is one of the best stores',
            'phone' => '01122265156',
        ]);
        DB::table('stores')->insert([
            'name' => 'boss',
            'location' => 'Genedy Street',
            'rate' => 'genedymohamed96@gmail.com',
            'review' => 'helloworld',
            'description' => 'this is one of the best stores',
            'phone' => '01122265156',
        ]);
        DB::table('stores')->insert([
            'name' => 'lacoste',
            'location' => 'Genedy Street',
            'rate' => 'genedymohamed96@gmail.com',
            'review' => 'helloworld',
            'description' => 'this is one of the best stores',
            'phone' => '01122265156',
        ]);
        DB::table('stores')->insert([
            'name' => 'polo',
            'location' => 'Genedy Street',
            'rate' => 'genedymohamed96@gmail.com',
            'review' => 'helloworld',
            'description' => 'this is one of the best stores',
            'phone' => '01122265156',
        ]);
        DB::table('stores')->insert([
            'name' => 'adrian',
            'location' => 'Genedy Street',
            'rate' => 'genedymohamed96@gmail.com',
            'review' => 'helloworld',
            'description' => 'this is one of the best stores',
            'phone' => '01122265156',
        ]);
        DB::table('stores')->insert([
            'name' => 'mobileshop',
            'location' => 'Genedy Street',
            'rate' => 'genedymohamed96@gmail.com',
            'review' => 'helloworld',
            'description' => 'this is one of the best stores',
            'phone' => '01122265156',
        ]);
    }
}
