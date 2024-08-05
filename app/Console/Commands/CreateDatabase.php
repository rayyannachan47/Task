<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the employee_management database if it does not exist';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dbName = 'employee_management';
        $connection = Config::get('database.default');
        $config = Config::get("database.connections.$connection");
        
        Config::set("database.connections.$connection.database", null);
        
        $query = "CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        try {
            DB::statement($query);
            $this->info("Database '$dbName' created or already exists.");
        } catch (\Exception $e) {
            $this->error("Error creating database: " . $e->getMessage());
            return 1;
        }
        
        Config::set("database.connections.$connection.database", $dbName);
        return 0;
    }
}
