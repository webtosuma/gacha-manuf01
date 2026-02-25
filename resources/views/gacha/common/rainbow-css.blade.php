@php

    $rainbows = \App\Models\Text::getRainbow();
    $rainbow_published_status = isset($rainbows['rainbow_published_status']) ? $rainbows['rainbow_published_status'] : null;


@endphp
@if( isset($rainbow_published_status) && $rainbow_published_status===1 )


    <style>
        .bg-rainbow-index {
            background: linear-gradient(to right,
                #e60000,
                #f39800,
                #fff100,
                #009944,
                #0068b7,
                #1d2088,
                #920783,
                #e60000
            ) 0 / 200% ;
            animation: rainbow 2s linear infinite ;

            color: #fff;
        }
    </style>


@endif
