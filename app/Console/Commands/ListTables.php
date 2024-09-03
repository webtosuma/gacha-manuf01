<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
/*
| =============================================
|  DBテーブル一覧　閲覧コマンド
| =============================================
*/
class ListTables extends Command
{
    /**　コマンド名を定義　*/
    protected $signature = 'db:list-tables';


    /**　コマンドの説明を定義　*/
    protected $description = '接続されたデータベース内のすべてのテーブルを一覧表示します';


    /** 新しいコマンドインスタンスを作成します。 */
    public function __construct(){ parent::__construct(); }


    /**
     * コンソールコマンドを実行します。
     *
     * @return int
     */
    public function handle()
    {
        # テーブル一覧を取得
        $tables = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_' . env('DB_DATABASE');  // テーブル名のキーを取得

        // if (empty($tables)) {
        //     $this->info('No tables found in the database.');
        //     return;
        // }

        // $this->info('Tables in the database:');
        // foreach ($tables as $table) {
        //     $this->line($table->$tableKey);
        // }


        if (empty($tables)) {
            $this->info('データベース内にテーブルが見つかりません。');
            return;
        }

        $this->info('データベース内のテーブルとレコード数:');

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            $count = DB::table($tableName)->count();  // 各テーブルのレコード数を取得

            $this->line("{$tableName} : {$count}");
        }

    }
}
