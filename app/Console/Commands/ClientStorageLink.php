<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
/*
| =============================================
|  クライアント ストレージ用リンク作成　
| =============================================
*/
class ClientStorageLink extends Command
{
    protected $signature = 'client:storage-link';
    protected $description = 'Link public/storage to the disk defined in FILESYSTEM_DRIVER';

    public function handle()
    {
        # ストレージリンクのセット
        $this->setStorageLink($this);

        return Command::SUCCESS;
    }



    /**
     * ストレージリンクのセット
     */
    public static function setStorageLink($obj): void
    {
        $disk = config('filesystems.default');

        if (!$disk) {
            $obj->error('No default filesystem disk configured.');
        }

        $diskConfig = config("filesystems.disks.{$disk}");

        if (!$diskConfig) {
            $obj->error("Disk '{$disk}' not found in filesystems config.");
            return;
        }

        // local driver のみ対応
        if (($diskConfig['driver'] ?? null) !== 'local') {
            $obj->warn("Disk '{$disk}' is not a local driver. No symlink required.");
            return;
        }

        $root = $diskConfig['root'] ?? null;

        if (!$root || !File::exists($root)) {
            $obj->error("Storage root does not exist: {$root}");
            return;
        }

        $publicPath = public_path('storage');

        // 既存リンクまたはディレクトリ削除
        if (is_link($publicPath) || File::exists($publicPath)) {
            $obj->info("Removing existing public/storage...");
            File::delete($publicPath);
        }

        // シンボリックリンク作成
        symlink($root, $publicPath);

        $obj->info("✔ public/storage linked to:");
        $obj->line("   {$root}");

        $obj->info("クライアント用のストレージリンクを作成しました！");
    }


}
