<?php

namespace App\Services\Admin;

use App\Models\Prize;
/*
| =============================================
|  Admin : API商品 サービス
| =============================================
*/
class ApiPrizeService
{
    /**
     * 商品一覧取得
     */
    public function getPrizes($request)
    {
        $query = Prize::query();

            // カテゴリリレーション必須
            $query->has('category');

            // キーワード検索
            $this->keywordSearch($request, $query);

            // カテゴリ
            if ($request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            // 並び替え
            if ($request->order_code) {
                $query->orderBy('code', $request->order_code);
            }

            if ($request->order_name) {
                $query->orderBy('name', $request->order_name);
            }

            if ($request->order_rank_id) {
                $query->orderBy('rank_id', $request->order_rank_id);
            }

            if ($request->order_point) {
                $query->orderBy('point', $request->order_point);
            }

            if ($request->order_ticket) {
                $query->orderBy('ticket', $request->order_ticket);
            }

            if ($request->updated_at) {
                $query->orderBy('updated_at', $request->updated_at);
            } else {
                $query->orderByDesc('created_at');
            }

            // 絞り込み
            if ($request->where_rank_id) {
                $query->where('rank_id', $request->where_rank_id);
            }

            if ($request->max_point) {
                $query->where('point', '<=', $request->max_point);
            }

            if ($request->min_point) {
                $query->where('point', '>=', $request->min_point);
            }

            if ($request->max_ticket) {
                $query->where('ticket', '<=', $request->max_ticket);
            }

            if ($request->min_ticket) {
                $query->where('ticket', '>=', $request->min_ticket);
            }

            if ($request->ids) {
                $query->whereIn('id', $request->ids);
            }

            if ($request->not_ids) {
                $query->whereNotIn('id', $request->not_ids);
            }

            # リレーション
            $query->with('rank');
            
            // ページネーション（←コントローラーから渡す）
            // $prizes = $query->with('rank')->paginate($perPage);

            // 追加処理
            // foreach ($prizes as $prize) {
            //     $prize->image_path = $prize->image_path;
            //     $prize->is_used = $prize->is_used;
            // }

        return $query;
    }



    /**
     * キーワード検索
     */
    private function keywordSearch($req, $query)
    {
        if (!$req->has('key_words')) return;

        $keywords = $this->arrayConvertString($req->key_words);

        foreach ($keywords as $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('code', 'like', "%{$keyword}%")
                  ->orWhere('name', 'like', "%{$keyword}%");
            });
        }
    }

    /**
     * 文字列 → 配列
     */
    private function arrayConvertString($string)
    {
        $string = str_replace('　', ' ', $string); // 全角スペース対応
        return explode(' ', $string);
    }
}