<section class="mb-3">
    @php
    $active_class = ' active disabled text-dark';
    @endphp
    <ul class="nav nav-tabs">
        <li class="nav-item">
            @php
            $active = isset($tab) &&$tab=='admin.gacha.edit'? $active_class : '';
            @endphp
            <a class="nav-link text-warning {{$active}}"
            href="{{ route('admin.gacha.edit',$gacha) }}">基本情報</a>
        </li>
        <li class="nav-item">
            @php
            $active = isset($tab) &&$tab=='admin.gacha.prize'? $active_class : '';
            @endphp
            <a class="nav-link text-warning {{$active}}"
            href="{{ route('admin.gacha.prize.edit',$gacha) }}">商品</a>
        </li>
        <li class="nav-item">
            @php
            $active = isset($tab) &&$tab=='admin.gacha.movie.edit'? $active_class : '';
            @endphp
            <a class="nav-link text-warning {{$active}}"
            href="{{ route('admin.gacha.movie.edit',$gacha) }}">演出動画</a>
        </li>
        <li class="nav-item">
            @php
            $active = isset($tab) &&$tab=='admin.gacha.discription.edit'? $active_class : '';
            @endphp
            <a class="nav-link text-warning {{$active}}"
            href="{{ route('admin.gacha.discription.edit',$gacha) }}">詳細説明</a>
        </li>
        <li class="nav-item">
            @php
            $active = isset($tab) &&$tab=='admin.gacha.published'? $active_class : '';
            @endphp
            <a class="nav-link text-warning {{$active}}"
            href="{{ route('admin.gacha.published',$gacha) }}">公開設定</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"
            href="{{ route('admin.gacha.show',$gacha) }}">詳細ページに戻る</a>
          </li>
      </ul>
</section>
