<u-manuf-gacha-image
gacha_name            ="{{$machine->name}}"
gacha_ratio           ="{{config('app.gacha_card_ratio')}}"
gacha_image_path      ="{{$machine->image_path}}"

initial_time          ="{{$machine->initial_time}}"
limitted_i_time       ="{{$machine->initial_timezone}}"
published_at_format   ="{{$machine->published_at > now() ? $machine->published_at->format('Y/m/d H:i:s') : null}}"
remaining_count       ="{{$machine->remaining_count}}"
add_chance_image_path ="{{$machine->add_chance_image_path}}"
have_user_rank        ="{{$machine->have_user_rank}}"
user_played_count     ="{{$machine->user_played_count}}"

img_path_one_chance   ="{{ $machine->img_path_one_chance   }}"
img_path_one_time     ="{{ $machine->img_path_one_time     }}"
img_path_only_oneday  ="{{ $machine->img_path_only_oneday  }}"
img_path_only_new_user="{{ $machine->img_path_only_new_user}}"
img_path_user_rank    ="{{ $machine->img_path_user_rank    }}"
></u-manuf-gacha-image>

