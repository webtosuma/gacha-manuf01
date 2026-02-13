<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
/*
| =============================================
|  クライアント ディレクトリ作成　コマンド client:create
| =============================================
*/
class CreateClient extends Command
{
    protected $signature   = 'client:create';
    protected $description = 'Create client directories using APP_CLIENT';

    public function handle()
    {
        # クライアント名
        $name = config('app.client');

        if (!$name) {
            $this->error('クライアント名が指定されていません。');
            return;
        }

        if ($name === 'default') {
            $this->warn('クライアント名が指定されていません。');
            return;
        }

        $this->info("クライアント用ディレクトリを作成します。: {$name}");

        # paths
        $configBase   = config_path('clients/default');
        $configTarget = config_path("clients/{$name}");

        $viewBase     = resource_path('views_clients/default');
        $viewTarget   = resource_path("views_clients/{$name}");

        $storageBase  = storage_path("app/public/clients/{$name}/site/image");

        $cssBase      = public_path('css/app.css');
        $cssTarget    = public_path("css/clients/{$name}.css");;

        # 4. css dir create
        if (!File::exists($cssTarget)) {
            File::copy($cssBase, $cssTarget);
            $this->info("✔ css created: {$cssTarget}");
        } else {
            $this->warn("⚠ css already exists");
        }

        # 1. config copy
        if (!File::exists($configTarget)) {
            File::copyDirectory($configBase, $configTarget);
            $this->info("✔ config created: {$configTarget}");
        } else {
            $this->warn("⚠ config already exists");
        }

        # 2. view copy
        if (!File::exists($viewTarget)) {
            File::copyDirectory($viewBase, $viewTarget);
            $this->info("✔ views created: {$viewTarget}");
        } else {
            $this->warn("⚠ views already exists");
        }

        # 3. storage dir create
        if (!File::exists($storageBase)) {
            File::makeDirectory($storageBase, 0755, true);
            $this->info("✔ storage created: {$storageBase}");
        } else {
            $this->warn("⚠ storage already exists");
        }

        $this->info("🎉 Client '{$name}'：クライアント用ディレクトリ作成が完了しました！");
    }
}
