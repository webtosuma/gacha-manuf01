<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\ManufGachaTitleResource as BaseUserResource;

class ManufGachaTitleResource extends BaseUserResource
{
    public function toArray($request)
    {
        // 👇 親（ユーザー用）のデータを取得
        $data = parent::toArray($request);

        // 👇 Admin用に拡張
        return array_merge($data, [


            /* =========================
             * リレーション上書き（Admin用）
             * ========================= */

            'category' => $this->whenLoaded('category', function () {
                return [
                    'id'   => $this->category->id,
                    'name' => $this->category->name,
                    'code' => $this->category->code_name,
                ];
            }),

            // 'prizes' => AdminManufGachaTitlePrizeResource::collection(
            //     $this->whenLoaded('title_prizes')
            // ),

            // 'machines' => AdminManufGachaTitleMachineResource::collection(
            //     $this->whenLoaded('machines')
            // ),

        ]);
    }
}
