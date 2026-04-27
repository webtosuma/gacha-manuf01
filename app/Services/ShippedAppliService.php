<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Models\UserPrize;
use App\Models\PointHistory;
use App\Models\UserShipped;
use App\Models\Prize;
use App\Models\Admin;
/*
| =============================================
|  発送申請 サービス
| =============================================
*/
class ShippedAppliService
{
    /** 発送ポイント */
    public function calcShippedPoint(int $item_count): int
    {
        /*.設定は。config.gachaに記述 */
        $basic_point = config('gacha.shipped.point', 0);
        $item_count_unit = config('gacha.shipped.item_count_unit', null);

        return $item_count_unit
        ? ceil( $item_count / $item_count_unit ) * $basic_point
        : $basic_point;
    }

    
    
    /** ユーザー商品取得 */
    public function findUserPrizes(array $id_array)
    {
        $user = Auth::user();

        return UserPrize::where('user_id', $user->id)
        ->whereNull('point_history_id')//ポイント収支履歴（未交換のみ）
        ->whereNull('shipped_id')//発送履歴（未交換のみ）
        ->orderByDesc('created_at')
        ->find($id_array);
    }

    
    
    /** 期限チェック */
    public function checkDeadline(array $id_array): array
    {
        # ポイント交換するユーザー商品を取得
        $user_prizes = $this->findUserPrizes($id_array);

        # 期限切れIDのみを取得
        $expired = [];
        foreach ($user_prizes as $p) {
            if ($p->is_deadline) { $expired[] = $p->id; }
        }

        return $expired;
    }

    
    
    /** 商品まとめ */
    public function buildShippedPrizes($user_prizes)
    {
        $ids = $user_prizes->pluck('prize_id')->toArray();
        $prizes = Prize::find($ids);

        $counts = array_count_values($ids);

        foreach ($prizes as $p) {
            $p->count = $counts[$p->id] ?? 0;
        }

        return $prizes;
    }

    
    
    /** 発送処理（トランザクション） */
    public function executeShipment(array $id_array, int $address_id)
    {
        $user = Auth::user();
        $user_prizes = $this->findUserPrizes($id_array);

        $point = $this->calcShippedPoint(count($id_array));

        return DB::transaction(function () use ($user, $user_prizes, $address_id, $point) {

            # ポイント履歴
            $point_history = PointHistory::create([
                'user_id'   => $user->id,
                'value'     => -$point,//ポイント数
                'reason_id' => 22,//商品発送
            ]);

            # 発送履歴
            $user_shipped = UserShipped::create([
                'user_id' => $user->id,
                'user_address_id' => $address_id,
                'point_history_id' => $point_history->id,
                'state_id' => 11,
            ]);

            # 商品更新
            foreach ($user_prizes as $p) {
                $p->update([ 'shipped_id' => $user_shipped->id ]);
            }

            return $user_shipped;
        });
    }

    
    
    /** メール送信 */
    public function sendMail($user_shipped)
    {
        # ユーザー情報
        $user = $user_shipped->user;

        # 発送する商品:種類別($shipped_prizes)
        $user_prizes = $user_shipped->user_prizes;
        $shipped_prizes = $this->buildShippedPrizes($user_prizes);

        # 変数の保存
        $inputs = compact('user', 'user_shipped', 'shipped_prizes');

        # ユーザー宛
        Mail::to($user->email)->send(
            new \App\Mail\SendHtmlMailMailable([
                'inputs' => $inputs,
                'view' => 'emails.user_shipped.appli',
                'subject' => '商品の発送申請を受け付けました',
            ])
        );

        # サイト管理者へメール送信
        $admins = Admin::where('get_mail', 1)->get();

        foreach ($admins as $admin) 
        {
            Mail::to($admin->email)->send(
                new \App\Mail\SendHtmlMailMailable([
                    'inputs' => $inputs,
                    'view' => 'emails.user_shipped.admin_appli',
                    'subject' => '商品の発送申請を受け付けました',
                ])
            );
        }

        
    }



}