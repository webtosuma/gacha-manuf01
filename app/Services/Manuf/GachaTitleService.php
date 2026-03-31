<?php

namespace App\Services\Manuf;

use App\Models\ManufGachaTitle;
use App\Services\StorageService;//ストレージ保存サービス
use Illuminate\Support\Facades\DB;//トランジクション対応
use App\Models\ManufGachaTitleImage;
/*
| =============================================
|  Manufacturer : ガチャタイトル サービス
| =============================================
*/
class GachaTitleService
{
    protected $storage;
    public function __construct(StorageService $storage)
    {
        # ストレージ保存サービスの登録
        $this->storage = $storage;
    }



    /**
     * 新規登録
     *
     * @param \Illuminate\Http\Request $request
     * @return ManufGachaTitle
     */
    public function store($request): ManufGachaTitle
    {
        return DB::transaction(function () use ($request) {

            # 入力加工
            $inputs = $this->processingInputs($request, null);

            # 新規登録
            $gacha_title = ManufGachaTitle::create($inputs);

            # 紹介画像 保存/更新/削除
            $this->saveImages( $request, $gacha_title );


            return $gacha_title;
        });
    }



    /**
     * 更新処理
     *
     * @param \Illuminate\Http\Request $request
     * @param ManufGachaTitle $gacha_title
     * @return ManufGachaTitle
     */
    public function update($request, ManufGachaTitle $gacha_title): ManufGachaTitle
    {
        return DB::transaction(function () use ($request, $gacha_title) {

            # 入力加工
            $inputs = $this->processingInputs($request, $gacha_title);

            # 更新
            $gacha_title->update($inputs);

            # 紹介画像 保存/更新/削除
            $this->saveImages( $request, $gacha_title );


            return $gacha_title;
        });
    }



    /**
     * 削除処理
     *
     * @param \Illuminate\Http\Request $request
     * @param ManufGachaTitle $gacha_title
     * @return Void
     */
    public function delete($request, ManufGachaTitle $gacha_title): void
    {
        DB::transaction(function () use ($request, $gacha_title) {

            $gacha_title->delete();

        });
    }



        /**
         * 入力データ加工
         */
        public function processingInputs($request, $gacha_title = null): array
        {
            $inputs = $request->only(

                /* 基本情報 */
                'category_id',
                'name',
                'image_samune',
                'price',
                'code',

                /* 詳細情報 */
                'description',
                'set_contents',
                'prize_size',
                'prize_materials',
                'age_range',
                'copy_right',
            );


            # 新規時コード生成
                if ($gacha_title === null) {
                    $inputs['code'] = ManufGachaTitle::CreateCode();
                }


            # デコード
                $decode_params = [
                    'name',
                    'description',
                    'set_contents',
                    'prize_size',
                    'prize_materials',
                    'age_range',
                    'copy_right',
                ];

                foreach ($decode_params as $param) {
                    $inputs[$param] = urldecode($inputs[$param]);
                }


            # テキストストレージ
                $text_params = [
                    'description',
                    'set_contents',
                ];
                foreach ($text_params as $param) {
                    $dir = 'upload/gacha_title/'.$param;
                    $new_text = $inputs[$param];
                    $old_text = $gacha_title ? $gacha_title[$param] : null;

                    $inputs[$param] = $this->storage->uploadText($dir, $new_text, $old_text);
                }


            # 画像処理
                $param = 'image_samune';

                $dir = 'upload/gacha_title/'.$param;
                $request_file   = $request->file($param);
                $old_image_path = $gacha_title ? $gacha_title[$param] : null;
                $image_dalete   = null;
                $copy_image_puth = $request->copy_image_puth;

                $inputs[$param] = $this->storage->uploadImage(
                    $dir,
                    $request_file,
                    $old_image_path,
                    $image_dalete,
                    $copy_image_puth
                );

            return $inputs;
        }



        /**
         * 紹介画像 保存/更新/削除
         */
        public function saveImages( $request, ManufGachaTitle $gacha_title ): void
        {
            $dir = 'upload/gacha_title/images';
            $files   = $request->file('images', []);
            $inputs  = $request->input('images', []);
            $ids     = $request->input('image_ids', []);

            foreach ( $ids as $i => $image_id )
            {
                $file   = $files[$i] ?? null;
                $delete = isset( $inputs[$i] ) && $inputs[$i]=="delete" ;

                $image = $image_id //登録済みデータ
                ? ManufGachaTitleImage::where('id', $image_id)
                    ->where('manuf_gacha_title_id', $gacha_title->id)
                    ->first()
                : null;
                $old_path = $image ? $image->path : null;


                # Storage処理
                $new_path = $this->storage->uploadImage( $dir, $file, $old_path, $delete );


                # 削除
                if ( $delete && $image ) { $image->delete(); continue; }


                if( $new_path )
                {
                    # 新規登録
                    if ( !$image )
                    {
                        ManufGachaTitleImage::create([
                            'manuf_gacha_title_id' => $gacha_title->id,
                            'path' => $new_path
                        ]);
                    }

                    # 更新
                    else
                    {
                        $image->update([ 'path' => $new_path ]);
                    }

                }


            }


        }



    /**
     * 販売・公開期間の更新
     *
     * @param \Illuminate\Http\Request $request
     * @param ManufGachaTitle $gacha_title
     * @return ManufGachaTitle
     */
    public function publishedUpdate( $request, ManufGachaTitle $gacha_title ): ManufGachaTitle
    {
        return DB::transaction(function () use ($request, $gacha_title) {

            $inputs = $request->only(
                /* 日時系 */
                'estimated_shipping_at',
                'sales_start_at',
                'sales_end_at',
                'published_start_at',
                'published_end_at',
            );

            $gacha_title->update($inputs);

            return $gacha_title;

        });
    }



}



