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
