@php
# 変数
$app_name          = config('app.name');//サイト名
$mail_from_address = env('MAIL_FROM_ADDRESS',false);//送信メールアドレス
$mail_domain       = str_replace('cs@','@',$mail_from_address);//ドメイン名
$mail_support      = 'support'.$mail_domain;

$body = <<<__EOT__

{$app_name}をご利用頂きありがとうございます。

お問合せの確認メールは下記のメールアドレスより送信されます。
「{$mail_from_address}」

各種お問合せは下記のメールアドレスより返信をさせて頂いております。
「{$mail_support}」

お客さまには誠にお手数をおかけしますが、下記ドメインのメールを受信できるように、 迷惑メール設定から解除、もしくは受信設定をして頂く様お願い致します。

{$mail_domain}


各メールソフトや端末によっては設定方法が異なります。
各種、お使いのメールソフト及び携帯会社へお問い合わせください。

【docomo指定受信方法】
　https://x.gd/gFKOy

【au指定受信リスト】
　https://x.gd/Uykln

【softbank受信許可設定】
　https://x.gd/vmOBH

【Yahooメールをご利用のお客様】

1.Yahooメールにログインし、画面右上の「設定・その他」もしくは「メールオプション」をクリックします。
2.次に「フィルターと受信通知設定」をクリックし、「追加」をクリックします。
3.From：[ {$mail_domain} ] [ を含む ] 、移動先フォルダ：[ 受信箱 ]と設定してください。
4.最後に保存をクリックしてください。


【Gmailをご利用のお客様】

1.Gmailにログインし、画面右上の歯車アイコンをクリックして「設定」に進んでください。
2.次に「フィルタ」をクリックし、「新しいフィルタを作成」をクリックします。
3．Fromに[ {$mail_domain} ]と入力し、「この検索条件でフィルタを作成」をクリックしてください。
4.「迷惑メールにしない」にチェックを入れ、最後に「フィルタを作成」をクリックしてください。


__EOT__;
@endphp
{!! nl2br(preg_replace('/\b(https?:\/\/\S+)/i', '<a href="$1">$1</a>', $body) )!!}
