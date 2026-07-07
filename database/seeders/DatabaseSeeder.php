<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'editor', 'viewer'] as $role) {
            Role::findOrCreate($role);
        }

        $user = User::updateOrCreate([
            'email' => 'admin@bestiarioborealis.test',
        ], [
            'name' => 'Bestiario Admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('admin');

        $this->call([
            DossierThemeSeeder::class,
            BestiaryEntrySeeder::class,
        ]);
    }
}
