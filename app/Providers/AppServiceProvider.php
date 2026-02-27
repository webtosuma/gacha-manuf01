<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }



    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* [クライアント別Method] configをクライアント別に自動ロード */
        $this->loadAllClientConfigs();

        /* [クライアント別Method] Viewをクライアント別に自動ロード */
        $this->loadAllClientViews();

        /* [クライアント別Method] ストレージをクライアント別に自動ロード */
        // $this->loadAllClientStorage();
    }



    /**
     * [クライアント別Method] configをクライアント別に自動ロード
     */
    private function loadAllClientConfigs(): void
    {
        # クライアント識別
        $client = config('app.client', 'default');

        $defaultDir = config_path('clients/default');

        foreach (glob($defaultDir . '/*.php') as $filePath) {

            $file = basename($filePath, '.php');

            # default config を読み込む
            $baseConfig = require $filePath;

            # client config があれば読み込む
            $clientPath = config_path("clients/{$client}/{$file}.php");

            $clientConfig = file_exists($clientPath)
                ? require $clientPath
                : [];

            # default + client をマージ（clientが優先）
            $merged = array_replace_recursive($baseConfig, $clientConfig);
            foreach ($baseConfig as $key => $baseConfigValue) {
                $merged[$key] = isset($clientConfig[$key]) ? $clientConfig[$key] : $baseConfigValue;
            }

            // dd([$baseConfig, $clientConfig,$merged]);


            # Laravelのconfigに上書き
            config()->set($file, $merged);
        }
    }



    /**
     * [クライアント別Method] Viewをクライアント別に自動ロード
     */
    private function loadAllClientViews(): void
    {
        $client = config('app.client');

        # defaultの場合は何もしない
        if (!$client || $client === 'default') { return; }

        # client 未指定の場合は何もしない
        $clientBasePath = resource_path("views_clients/{$client}");
        if (!is_dir($clientBasePath)) { return; }

        # 差し替え対象ディレクトリ
        $targets = [
            'gacha',
            'includes',

            'store'
        ];


        foreach ($targets as $dir) {

            $path = $clientBasePath . '/' . $dir;

            # クライアント側を優先的に探索させる
            if (is_dir($path)) {
                app('view')->getFinder()->prependLocation($clientBasePath);
            }
        }

        # 読み込みパスの確認
        // dd(view()->getFinder()->find('includes.header'));
    }



    /**
     * [クライアント別Method] ストレージをクライアント別に自動ロード
     */
    private function loadAllClientStorage(): void
    {
        $client = config('app.client');

        # defaultの場合は何もしない
        if (!$client || $client === 'default') { return; }

        # client 用 root
        $root = storage_path("app/public/clients/{$client}");

        # client_public disk の root を変更
        Config::set('filesystems.disks.client_public.root', $root);

        # default disk を client_public に変更
        Config::set('filesystems.default', 'client_public');


        // dd(config('filesystems.default'));
    }

}
