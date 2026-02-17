<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
/*
| =============================================
|  クライアント　クライアント一覧表示　コマンド
| =============================================
*/
class ClientList extends Command
{
    protected $signature = 'client:list';
    protected $description = 'List all client directories inside xx_env';

    public function handle()
    {
        $envBasePath = base_path('xx_env');

        if (!File::exists($envBasePath)) {
            $this->error("xx_env directory does not exist.");
            return Command::FAILURE;
        }

        $directories = File::directories($envBasePath);

        if (empty($directories)) {
            $this->warn("No client directories found.");
            return Command::SUCCESS;
        }

        $this->info("Available Clients:\n");

        foreach ($directories as $dir) {
            $clientName = basename($dir);

            // .env が存在するディレクトリのみ表示
            if (File::exists($dir . '/.env')) {
                $this->line("- {$clientName}");
            }
        }

        return Command::SUCCESS;
    }
}
