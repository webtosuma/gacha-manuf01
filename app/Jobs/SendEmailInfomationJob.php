<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Infomation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
/*
| =============================================
|  サイト管理者　お知らせメール一括送信　JOB
| =============================================
*/
class SendEmailInfomationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $user;
    protected $infomation;

    public function __construct(User $user, Infomation $infomation)
    {
        $this->user       = $user;
        $this->infomation = $infomation;
    }


    /**
    * Execute the job. *
    * @return void
    */
    public function handle()
    {
        $user       = $this->user;
        $infomation = $this->infomation;


        Mail::to( $user->email ) //宛先
        ->send(new \App\Mail\SendHtmlMailMailable([
            'inputs'  => compact('infomation'), //入力変数
            'view'    => 'emails.infomation.index' , //テンプレート
            'subject' => $infomation->title, //件名
        ]) );

    }

}
