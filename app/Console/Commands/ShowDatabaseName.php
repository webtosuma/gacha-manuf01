<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
/*
| =============================================
|  DBテーブルの名前　参照コマンド
| =============================================
*/
class ShowDatabaseName extends Command
{
    /**　コマンド名を定義　*/
    protected $signature = 'db:show-name';


    /**　コマンドの説明を定義　*/
    protected $description = '接続されたデータベースの名前を表示します';


    /** 新しいコマンドインスタンスを作成します。 */
    public function __construct(){ parent::__construct(); }



    public function handle()
    {
        // 接続中のデータベース名を取得
        $databaseName = DB::connection()->getDatabaseName();

        // データベース名を出力
        $this->info("接続中データベースの名称: {$databaseName}");
    }
}
