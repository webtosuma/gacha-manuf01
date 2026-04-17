<div >
    <div class="form-text mb-4">
        再生する演出動画を選択してください。
    </div>



    @if( count( $title_movies ) )
        <div class="border rounded overflow-hidden mb-4">
            <table class="table border text-dark m-0 rounded overflow-hidden" style="font-size:16px">
                <tbody>
                    @foreach ($title_movies as $title_movie)
                    <tr>
                        <th class="fw-bold">{{$title_movie->rank_label}}</th>

                        <td>
                            <!-- 'gacha_rank_id_'.XXX -->
                            <select class="form-select" 
                            name="{{'gacha_rank_id_'.$title_movie->gacha_rank_id}}">
                                <option value="" class="text-danger">選択してください</option>

                                @foreach ($movies as $movie)
                                    <option value="{{ $movie->id }}"
                                    @if(old('movie_id',$title_movie->movie_id)  == $movie->id) selected @endif
                                    >{{ $movie->name }}</option>
                                @endforeach

                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
