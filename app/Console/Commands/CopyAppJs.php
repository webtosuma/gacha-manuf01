<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
/*
| =============================================
|  app.js コピー　コマンド
| =============================================
*/
class CopyAppJs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy:appjs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy public/js/app.js with timestamped filename';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sourcePath = public_path('js/app.js');

        if (!File::exists($sourcePath)) {
            $this->error("File not found: {$sourcePath}");
            return 1;
        }

        $timestamp = Carbon::now()->format('YmdHis');
        $destinationPath = public_path("js/{$timestamp}app.js");

        File::copy($sourcePath, $destinationPath);

        $this->info("File copied to: {$destinationPath}");

        return 0;
    }
}
