<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
/*
|--------------------------------------------------------------------------
| 会員登録・メル認証用メール
|--------------------------------------------------------------------------
*/
class RegisterAuthMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->inputs  = $data['inputs'];
        $this->view    = $data['view'];
        $this->subject = $data['subject'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from_address = config('mail.mailers.register_auth.from_address');
        $from_name    = config('mail.mailers.register_auth.from_name');

        return $this->from( $from_address, $from_name )
        ->view($this->view) //テンプレートファイルの読み込み
        ->with( $this->inputs )
        ->subject($this->subject);// 件名
    }
}
