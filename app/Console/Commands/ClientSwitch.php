<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
/*
| =============================================
|  クライアント　クライアント切り替え　コマンド
| =============================================
*/
class ClientSwitch extends Command
{
    protected $signature = 'client:switch {name}';
    protected $description = 'Switch current .env to specified client environment';

    public function handle()
    {
        $client = $this->argument('name');

        # クライアントが存在するか確認
        if (!$this->clientExists($client)) {
            return Command::FAILURE;
        }

        $sourcePath = base_path("xx_env/{$client}/.env");
        $targetPath = base_path('.env');

        # バックアップ
        // if (File::exists($targetPath)) {
        //     File::copy($targetPath, base_path('.env.backup'));
        //     $this->info("✔ Current .env backed up to .env.backup");
        // }

        # 上書き
        File::copy($sourcePath, $targetPath);

        # ストレージリンクのセット(＊ClientStorageLinkコマンドのメソッドを利用)
        ClientStorageLink::setStorageLink($this);

        # キャッシュのクリア
        // $this->clearCaches();

        $this->info("✔ クライアントを切り替えました。: {$client}");
        return Command::SUCCESS;
    }



    /**
     * クライアントが存在するか確認
     */
    private function clientExists(string $client): bool
    {
        $path = base_path("xx_env/{$client}/.env");

        if (File::exists($path)) {
            return true;
        }

        # クライアント未存在時のエラー表示
        $this->showClientNotFoundError($client);
        return false;
    }

    /**
     * クライアント未存在時のエラー表示
     */
    private function showClientNotFoundError(string $client): void
    {
        $this->error("'{$client}'のenvファイルは存在しません。");

        $availableClients = $this->getAvailableClients();

        if (!empty($availableClients)) {
            $this->info("\nAvailable clients:");
            foreach ($availableClients as $c) {
                $this->line(" - {$c}");
            }
        }

        $this->line("\nUse: php artisan client:list");
    }

    /**
     * 利用可能なクライアント一覧取得
     */
    private function getAvailableClients(): array
    {
        $envBasePath = base_path('xx_env');

        if (!File::exists($envBasePath)) {
            return [];
        }

        $directories = File::directories($envBasePath);
        $clients = [];

        foreach ($directories as $dir) {
            if (File::exists($dir . '/.env')) {
                $clients[] = basename($dir);
            }
        }

        return $clients;
    }

    /**
     * キャッシュクリア処理
     */
    private function clearCaches(): void
    {
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('config:cache');

        $this->info("✔ Config and cache cleared.");
        $this->info("🎉 Client switch completed successfully.");
    }



    /**
     * ストレージリンクのセット
     */
    // private function setStorageLink(): void
    // {
    //     $disk = config('filesystems.default');

    //     if (!$disk) {
    //         $this->error('No default filesystem disk configured.');
    //     }

    //     $diskConfig = config("filesystems.disks.{$disk}");

    //     if (!$diskConfig) {
    //         $this->error("Disk '{$disk}' not found in filesystems config.");
    //         return;
    //     }

    //     // local driver のみ対応
    //     if (($diskConfig['driver'] ?? null) !== 'local') {
    //         $this->warn("Disk '{$disk}' is not a local driver. No symlink required.");
    //         return;
    //     }

    //     $root = $diskConfig['root'] ?? null;

    //     if (!$root || !File::exists($root)) {
    //         $this->error("Storage root does not exist: {$root}");
    //         return;
    //     }

    //     $publicPath = public_path('storage');

    //     // 既存リンクまたはディレクトリ削除
    //     if (is_link($publicPath) || File::exists($publicPath)) {
    //         $this->info("Removing existing public/storage...");
    //         File::delete($publicPath);
    //     }

    //     // シンボリックリンク作成
    //     symlink($root, $publicPath);

    //     $this->info("✔ public/storage linked to:");
    //     $this->line("   {$root}");

    //     $this->info("クライアント用のストレージリンクを作成しました！");
    // }

}
