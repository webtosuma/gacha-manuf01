<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
/*
|--------------------------------------------------------------------------
| ストレージ保存 サービス
|--------------------------------------------------------------------------
*/
class StorageService
{

    /**
     * ストレージテキストファイルの更新
     *
     * @param String $dir         //保存先ディレクトリ
     * @param String $new_text    //新しい入力テキスト
     * @param String $old_text    //更新前のファイルパステキスト
     * @param String $st_count    //最大文字数
     *
     * @return String $file_path  //ファイルパス、または$st_count文字以下のテキスト
     */
    public function uploadText($dir, $new_text, $old_text = '', $st_count = 1)
    {
        $file_path = $this->normalizePath($old_text);

        // ① 長文 & 既存ファイルあり
        if (mb_strlen($new_text) > $st_count && Storage::exists($file_path)) {

            Storage::put($file_path, $new_text);
            return $file_path;
        }

        // ② 長文 & 新規
        if (mb_strlen($new_text) > $st_count && !Storage::exists($file_path)) {

            $num = $this->getNextFileNumber($dir);

            $file_path = $dir . sprintf('%06d.txt', $num);
            Storage::put($file_path, $new_text);

            return $file_path;
        }

        // ③ 短文 & 既存あり → 削除
        if (mb_strlen($new_text) <= $st_count && Storage::exists($file_path)) {
            Storage::delete($file_path);
        }

        return $new_text;
    }



    /**
     * ストレージファイルの削除
     *
     * @param String $text    //ファイルパステキスト
     */
    public function deleteText($text)
    {
        $file_path = $this->normalizePath($text);

        if (Storage::exists($file_path)) {
            Storage::delete($file_path);
        }
    }



    /**
     * ストレージ画像ファイルの更新
     *
     * @param  String $dir             //保存先ディレクトリ
     * @param  String $request_file    //画像のリクエスト
     * @param  String $old_image_path  //更新前の画像パス
     * @param  String $image_dalete    //画像を削除するか否か
     * @param  String $copy_image_puth //コピー用画像パス
     * @return String $file_path       //ファイルパス
     */
    public function uploadImage($dir, $request_file, $old_path = null, $delete = null, $copy_path = null)
    {
        $image_path = !$delete ? $old_path : null;

        if ($request_file) {

            $image_path = $request_file->store($dir);

            if ($old_path && Storage::exists($old_path)) {
                Storage::delete($old_path);
            }

        } elseif ($copy_path && !$old_path) {

            $image_path = $this->copyFile($dir, $copy_path);
        }

        return $image_path;
    }



    /**
     * ストレージファイルのコピー
     *
     * @param String $dir         //保存先ディレクトリ
     * @param String $path        //コピー元のファイルパス
     * @return String $file_path  //ファイルパス、または$st_count文字以下のテキスト
     */
    public function copyFile($dir, $path)
    {
        $old_path = $this->normalizePath($path);

        if (!Storage::exists($old_path)) {
            return $path;
        }

        $num = $this->getNextFileNumber($dir);

        $ext = substr($old_path, strrpos($old_path, "."));
        $new_path = $dir . sprintf('%06d', $num) . $ext;

        Storage::copy($old_path, $new_path);

        return $new_path;
    }



    /**
     * JSON保存
     */
    public function putJson($path, $data)
    {
        Storage::put($path, json_encode($data, JSON_PRETTY_PRINT));
        return true;
    }



    /**
     * JSON取得
     */
    public function getJson($path)
    {
        if (!Storage::exists($path)) {
            return null;
        }

        return json_decode(Storage::get($path), true);
    }



    /**
     * 採番取得
     */
    private function getNextFileNumber($dir)
    {
        $num_file = $dir . 'file_num.txt';

        $num = Storage::exists($num_file)
            ? (int) Storage::get($num_file)
            : 0;

        $num++;

        Storage::put($num_file, $num);

        return $num;
    }



    /**
     * パス正規化
     */
    private function normalizePath($path)
    {
        return str_replace(["\r\n", "\r", "\n", "\t", "\v"], '', $path);
    }


}
