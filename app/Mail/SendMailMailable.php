<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
/*
|--------------------------------------------------------------------------
| メール
|--------------------------------------------------------------------------
*/
class SendMailMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->inputs = $data['inputs'];
        $this->view = $data['view'];
        $this->subject = $data['subject'];
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->view) //テンプレートファイルの読み込み
        ->with(['inputs' => $this->inputs])
        ->subject($this->subject);// 件名
    }

}
/*
    [　使い方　]
    use Illuminate\Support\Facades\Mail;


    Mail::to( $request->email ) //宛先
    ->send(new \App\Mail\SendMailMailable([
        'inputs' => $request , //入力変数
        'view' => 'emails.worker_register01_verification' , //テンプレート
        'subject' => '【'.env('APP_NAME').'】会員登録認証コード' , //件名
    ]) );

*/
