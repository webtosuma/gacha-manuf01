<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
/*
| =============================================
|  演出動画　シーダー
| =============================================
*/
class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datalist = self::dataList();

        foreach ($datalist as $data)
        {
            $movie = new Movie( $data );
            $movie->save();
        }
    }




    /**
     * データリスト
     *
     * @return Array
     */
    public function dataList()
    {
        $path = 'sample/movie/';
        return   [
            //RankA
            [
                'name'           => 'RankA-01',//動画名
                'pc_storage'     => $path.'pc/300/01.mp4',//PC用動画・保存先
                'mobile_storage' => $path .'mobile/300/01.mp4',//mobile用動画・保存先
            ],
            [
                'name'           => 'RankA-02',//動画名
                'pc_storage'     => $path.'pc/300/02.mp4',//PC用動画・保存先
                'mobile_storage' => $path .'mobile/300/02.mp4',//mobile用動画・保存先
            ],
            [
                'name'           => 'RankA-03',//動画名
                'pc_storage'     => $path.'pc/300/03.mp4',//PC用動画・保存先
                'mobile_storage' => $path .'mobile/300/03.mp4',//mobile用動画・保存先
            ],
            //RankB
            [
                'name'           => 'RankB-01',//動画名
                'pc_storage'     => $path.'pc/400/01.mp4',//PC用動画・保存先
                'mobile_storage' => $path .'mobile/400/01.mp4',//mobile用動画・保存先
            ],
            [
                'name'           => 'RankB-02',//動画名
                'pc_storage'     => $path.'pc/400/02.mp4',//PC用動画・保存先
                'mobile_storage' => $path .'mobile/400/02.mp4',//mobile用動画・保存先
            ],
            [
                'name'           => 'RankB-03',//動画名
                'pc_storage'     => $path.'pc/400/03.mp4',//PC用動画・保存先
                'mobile_storage' => $path .'mobile/400/03.mp4',//mobile用動画・保存先
            ],
            //RankC
            [
                'name'           => 'RankC-01',//動画名
                'pc_storage'     => $path.'pc/500/01.mp4',//PC用動画・保存先
                'mobile_storage' => $path .'mobile/500/01.mp4',//mobile用動画・保存先
            ],
            [
                'name'           => 'RankC-02',//動画名
                'pc_storage'     => $path.'pc/500/02.mp4',//PC用動画・保存先
                'mobile_storage' => $path .'mobile/500/02.mp4',//mobile用動画・保存先
            ],
            [
                'name'           => 'RankC-03',//動画名
                'pc_storage'     => $path.'pc/500/03.mp4',//PC用動画・保存先
                'mobile_storage' => $path .'mobile/500/03.mp4',//mobile用動画・保存先
            ],
            //RankD
            [
                'name'           => 'RankD-01',//動画名
                'pc_storage'     => $path.'pc/600/01.mp4',//PC用動画・保存先
                'mobile_storage' => $path .'mobile/600/01.mp4',//mobile用動画・保存先
            ],
            [
                'name'           => 'RankD-02',//動画名
                'pc_storage'     => $path.'pc/600/02.mp4',//PC用動画・保存先
                'mobile_storage' => $path .'mobile/600/02.mp4',//mobile用動画・保存先
            ],
            [
                'name'           => 'RankD-03',//動画名
                'pc_storage'     => $path.'pc/600/03.mp4',//PC用動画・保存先
                'mobile_storage' => $path .'mobile/600/03.mp4',//mobile用動画・保存先
            ],

        ];

    }
}
