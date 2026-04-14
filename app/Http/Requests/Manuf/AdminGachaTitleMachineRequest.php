<?php

namespace App\Http\Requests\Manuf;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\ManufGachaTitleMachine;
/*
| =============================================
|  Manufacturer/Admin : ガチャタイトル 商品 リクエスト
| =============================================
*/
class AdminGachaTitleMachineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // dd([$this->min_time, $this->max_time]);
        $rules = [

            'default_name'  => [ 'required', 'string', 'max:140',],
            'type'          => [ 'required', 'string', 'max:140',],
            'min_time'      => [ 'required', 'date_format:H:i'],
            'max_time'      => [ 'required', 'date_format:H:i', 'after:min_time'],
            'is_published'  => [ 'required', 'in:0,1',],
        ];

        # 24:00の許可
        if( $this->max_time=='24:00' ){
            $rules['max_time'] = 'required'; //入力必須のみ
        }

        return $rules;
    }



    /**
     * 項目名（attributes）
     */
    public function attributes(): array
    {
        return [
            'default_name' => '筐体名',
            'type'         => 'ガチャの種類',
            'min_time'     => '表示開始時間',
            'max_time'     => '表示終了時間',
            'is_published' => '公開設定',
        ];
    }


}
