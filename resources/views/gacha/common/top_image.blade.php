<u-gacha-image
gacha_name            ="{{$gacha->name}}"
gacha_ratio           ="{{config('app.gacha_card_ratio')}}"
gacha_image_path      ="{{$gacha->image_path}}"

initial_time          ="{{$gacha->initial_time}}"
limitted_i_time       ="{{$gacha->initial_timezone}}"
published_at_format   ="{{$gacha->published_at > now() ? $gacha->published_at->format('Y/m/d H:i:s') : null}}"
remaining_count       ="{{$gacha->remaining_count}}"
add_chance_image_path ="{{$gacha->add_chance_image_path}}"
have_user_rank        ="{{$gacha->have_user_rank}}"
user_played_count     ="{{$gacha->user_played_count}}"

img_path_one_chance   ="{{ $gacha->img_path_one_chance   }}"
img_path_one_time     ="{{ $gacha->img_path_one_time     }}"
img_path_only_oneday  ="{{ $gacha->img_path_only_oneday  }}"
img_path_only_new_user="{{ $gacha->img_path_only_new_user}}"
img_path_user_rank    ="{{ $gacha->img_path_user_rank    }}"
></u-gacha-image>


