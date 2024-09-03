<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
/*
| =============================================
|  DBテーブル　閲覧コマンド
| =============================================
*/
class ShowTableData extends Command
{
    /**　コマンド名を定義　*/
    protected $signature = 'db:show-table {table}';  // コマンド名と引数を定義


    /**　コマンドの説明を定義　*/
    protected $description = '指定されたテーブルの列情報と最大 5 行のデータを表示します。';


    /** 新しいコマンドインスタンスを作成します。 */
    public function __construct(){ parent::__construct(); }


    /**
     * コンソールコマンドを実行します。
     *
     * @return int
     */
    public function handle()
    {
        $tableName = $this->argument('table');  // 引数としてテーブル名を受け取る

        # テーブルが存在するか確認
        if (!Schema::hasTable($tableName)) {
            $this->error("Table '{$tableName}' does not exist.");
            return;
        }


        # カラム情報の取得
        $columns = Schema::getColumnListing($tableName);
        $this->info("Columns in table '{$tableName}':");
        $this->line(implode(', ', $columns));


        # テーブルの最初の5件のデータを取得
        $rows = DB::table($tableName)->limit(5)->get();

        if ($rows->isEmpty()) {
            $this->line('No data found in this table.');
        } else {
            $this->info("Data in table '{$tableName}':");
            foreach ($rows as $row) {
                $this->line(json_encode($row, JSON_PRETTY_PRINT));
            }
        }


    }
}
