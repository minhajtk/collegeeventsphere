<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('db:import-dump', function () {
    $path = base_path('eventsphere_database_backup.sql');
    if (!file_exists($path)) {
        $this->error("Backup file not found at: {$path}");
        return 1;
    }
    
    $this->info("Importing eventsphere_database_backup.sql into database...");
    
    $sql = file_get_contents($path);
    \Illuminate\Support\Facades\DB::unprepared($sql);
    
    $this->info("Database imported successfully with all data!");
    return 0;
})->purpose('Import the backup SQL dump file into the database');

Artisan::command('admin:create {email=admin@eventsphere.edu} {password=admin123}', function ($email = 'admin@eventsphere.edu', $password = 'admin123') {
    $user = \App\Models\User::updateOrCreate(
        ['email' => $email],
        [
            'name' => 'System Administrator',
            'username' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'phone' => '+1234567890',
            'role' => 'admin',
            'department' => 'Administration',
            'enrolment_number' => 'ADM-2026-01',
            'status' => 'active',
        ]
    );
    $this->info("Admin account successfully configured!");
    $this->line("Email / Username: {$email} (or username: admin)");
    $this->line("Password: {$password}");
    $this->line("Role: {$user->role}");
    return 0;
})->purpose('Create or reset an admin account with custom or default credentials');

Artisan::command('db:seed-categories', function () {
    \App\Models\Category::seedDefaults();
    $count = \App\Models\Category::count();
    $this->info("Categories successfully seeded! Total categories in database: {$count}");
    foreach (\App\Models\Category::all() as $cat) {
        $this->line(" - [{$cat->id}] {$cat->name} (icon: {$cat->icon})");
    }
    return 0;
})->purpose('Seed all standard dummy campus event categories');
