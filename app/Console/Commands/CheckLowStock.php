<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\OrganizationController;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check-low';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for low stock items and send SMS notifications to PBEN organization';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for low stock items...');
        
        $orgController = new OrganizationController();
        $orgController->checkLowStockNotifications();
        
        $this->info('Low stock check completed.');
        
        return 0;
    }
}
