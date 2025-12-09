<section class="my-5">

    <replace-text-component text="{{ $body }}"></replace-text-component>

    <!--制定日・改訂日-->
    <div class="mt-4 mb-5">{{$enactmented_at_format}}</div>


    <!--バックナンバー-->
    @if ( $texts->count()>1 )
        @foreach ($texts as $num => $text)

        <a href="{{route('trems',$text->enactmented_at)}}"
        >{{$text->enactmented_at_format.( $num==$texts->count()-1 ? ' 制定' : ' 改訂' )}}</a><br>

        @endforeach
    @endif
</section>
