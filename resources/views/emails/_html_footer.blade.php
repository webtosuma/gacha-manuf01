<footer>
    <div class="mail-container">
        <p class="bg-light-text">
            <small>
                このメールは≪{{ env('APP_NAME') }}≫ご利用のお客様に自動送信しています。
                <br>
                このメールアドレスへの返信をすることはできません。
                <br>
                当社から送信されるメールのメッセージ及び添付物には、機密情報を含んでいる場合がございます。
                <br>
                当社から送信されたメールに心当たりがない場合、速やかに送信元へその旨をご連絡いただき、本メール及び本メールの添付物をすべて破棄していただけますようお願い申し上げます。
                <br>
                誤って着信したメールを、自己のために利用することや、第三者への開示は固く禁止いたします。
                <br>
                Email messages and attachments sent from us may contain confidential information.
                <br>
                If you do not recognize the e-mail sent from our company, please contact the sender as soon as possible and discard this e-mail and all attachments to this e-mail.
                <br>
                It is strictly prohibited to use emails received by mistake for your own benefit or to disclose them to third parties.
            </small>
        </p>
        <div class="">
            <a href="{{route('operating_company')}}">{{env('COMPANY_NAME')}}</a>
        </div>
        <div class="copy">&copy; TOSUMA Co.,Ltd.</div>
    </div>
</footer>
