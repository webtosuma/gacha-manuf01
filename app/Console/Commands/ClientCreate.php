<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
/*
| =============================================
|  クライアント ディレクトリ作成　コマンド
| =============================================
*/
class ClientCreate extends Command
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


        # 1. config copy

            $configBase   = config_path('clients/default');
            $configTarget = config_path("clients/{$name}");

            if (!File::exists($configTarget)) {
                File::copyDirectory($configBase, $configTarget);
                $this->info("✔ config created: {$configTarget}");
            } else {
                $this->warn("⚠ config already exists");
            }


        # 2. view copy

            $viewBase     = resource_path('views_clients/default');
            $viewTarget   = resource_path("views_clients/{$name}");

            if (!File::exists($viewTarget)) {
                File::copyDirectory($viewBase, $viewTarget);
                $this->info("✔ views created: {$viewTarget}");
            } else {
                $this->warn("⚠ views already exists");
            }

        # 3. storage dir create

            $storageDir = storage_path("app/clients");
            if (!File::exists($storageDir)) {
                File::makeDirectory($storageDir, 0755, true);
            }

            $storageBase  = storage_path("app/clients/{$name}/public");
            if (!File::exists($storageBase)) {
                File::makeDirectory($storageBase, 0755, true);
                $this->info("✔ storage created: {$storageBase}");
            } else {
                $this->warn("⚠ storage already exists");
            }


        # 4. css dir create

            $cssBase      = public_path('css/app.css');
            $cssTarget    = public_path("css/clients/{$name}.css");;

            if (!File::exists($cssTarget)) {
                File::copy($cssBase, $cssTarget);
                $this->info("✔ css created: {$cssTarget}");
            } else {
                $this->warn("⚠ css already exists");
            }


        # 5. xx_env にクライアント用ディレクトリと .env を作成
        $this->createClientEnvDirectory($name);


        # 6. ストレージリンクのセット(＊ClientStorageLinkコマンドのメソッドを利用)
        ClientStorageLink::setStorageLink($this);


        # 7. manifestファイル　を作成

            $manifestBase      = public_path('manifests/default.json');
            $manifestTarget    = public_path("manifests/{$name}.json");;

            if (!File::exists($manifestTarget)) {
                File::copy($manifestBase, $manifestTarget);
                $this->info("✔ manifest created: {$manifestTarget}");
            } else {
                $this->warn("⚠ manifest already exists");
            }



        $this->info("🎉 Client '{$name}'：クライアント用ディレクトリ作成が完了しました！");
    }






    /**
     * xx_env にクライアント用ディレクトリと .env を作成
     */
    private function createClientEnvDirectory(string $client): void
    {
        $basePath = base_path('xx_env');
        $clientPath = $basePath . '/' . $client;
        $envPath = $clientPath . '/.env';

        // xx_env が無ければ作成
        if (!File::exists($basePath)) {
            File::makeDirectory($basePath, 0755, true);
            $this->info("✔ Created directory: xx_env");
            return;
        }

        // クライアントディレクトリが既に存在する場合
        if (File::exists($clientPath)) {
            $this->error("Client environment directory already exists: {$client}");
            return;
        }

        // クライアントディレクトリ作成
        File::makeDirectory($clientPath, 0755, true);

        // 現在の .env をコピー
        $sourceEnv = base_path('.env');

        if (!File::exists($sourceEnv)) {
            $this->warn("Base .env not found. Empty .env created.");
            File::put($envPath, '');
        } else {
            File::copy($sourceEnv, $envPath);
        }

        $this->info("✔ Client env directory created:");
        $this->line("   {$clientPath}");
    }


}
