<template>
    <div class="modal fade" id="exchangeModal" tabindex="-1" aria-labelledby="exchangeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h5 class="modal-title" id="exchangeModalLabel">
                        <p>ポイント交換しますか？</p>
                        <p>商品を<strong class="fs-3">{{ totalPoint }}pt</strong>と交換する</p>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <button type="button"
                            class="btn p-md-33 btn-light border rounded-pill w-100"
                            data-bs-dismiss="modal"
                            >キャンセル</button>
                        </div>
                        <div class="col-6">

                            <!--選択した商品をポイント交換 r_exchange_points -->
                            <form :action="r_exchange_points" method="post">
                                <input type="hidden" name="_token" :value="token">
                                <input type="hidden" name="_method" value="patch">

                                <input v-for="(id, key) in ids" :key="key"
                                type="hidden" name="user_prize_ids[]" :value="id">

                                <button type="submit"
                                class="btn p-md-33 btn-warning text-white rounded-pill w-100"
                                >交換する</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
