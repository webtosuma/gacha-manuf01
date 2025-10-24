<section class="form-steps-pill mx-auto py-3">
    <div class="step-box text-primary">
        <div class="step_num mb-2  bg-primary">1</div>
        <span style="font-size: 16px;">ご注文</span>
    </div>
    <i class="bi bi-caret-right-fill mb-3 text-primary"></i>
    <div class="step-box text-primary">
        <div class="step_num mb-2  bg-primary">2</div>
        <span style="font-size: 16px;">発送待ち</span>
    </div>
    @if($user_shipped->state_id>20)
        <!--発送完了-->
        <i class="bi bi-caret-right-fill mb-3 text-primary"></i>
        <div class="step-box text-primary">
            <div class="step_num mb-2 bg-primary">3</div>
            <span style="font-size: 16px;">発送完了</span>
        </div>
    @else
        <!--未完了-->
        <i class="bi bi-caret-right-fill mb-3"></i>
        <div class="step-box">
            <div class="step_num mb-2">3</div>
            <span style="font-size: 16px;">発送完了</span>
        </div>
    @endif
</section>
