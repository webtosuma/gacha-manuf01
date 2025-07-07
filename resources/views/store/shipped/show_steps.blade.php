<section class="form-steps-pill mx-auto py-3">
    <div class="step-box text-success">
        <div class="step_num mb-2  bg-success">1</div>
        <span style="font-size: 16px;">ご注文</span>
    </div>
    <i class="bi bi-caret-right-fill mb-3 text-success"></i>
    <div class="step-box text-success">
        <div class="step_num mb-2  bg-success">2</div>
        <span style="font-size: 16px;">発送待ち</span>
    </div>
    @if($store_history->state_id>20)
        <!--発送完了-->
        <i class="bi bi-caret-right-fill mb-3 text-success"></i>
        <div class="step-box text-success">
            <div class="step_num mb-2 bg-success">3</div>
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

