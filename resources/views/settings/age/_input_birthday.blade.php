<div class="d-flex gap-2">
    @php
        $minAge = config('app.min_age', 18); // 最低年齢
        $currentYear = now()->year;
        $maxYear = $currentYear - $minAge +6; // この年まで入力可能
    @endphp
    <!--年（birthday_y）-->
    <select name="birthday_y" class="form-select fs-5">
        <option value="">年</option>
        @for ($y = $maxYear; $y >= 1900; $y--)
            <option value="{{ $y }}"
                {{ old('birthday_y', Auth::user()->birthday_format_y) == $y ? 'selected' : '' }}>
                {{ $y }}
            </option>
        @endfor
    </select>

    <!--月（birthday_m）-->
    <select name="birthday_m" class="form-select fs-5">
        <option value="">月</option>
        @for ($m = 1; $m <= 12; $m++)
            <option value="{{ $m }}"
                {{ old('birthday_m', Auth::user()->birthday_format_m) == $m ? 'selected' : '' }}>
                {{ $m }}
            </option>
        @endfor
    </select>

    <!--日（birthday_d）-->
    <select name="birthday_d" class="form-select fs-5">
        <option value="">日</option>
        @for ($d = 1; $d <= 31; $d++)
            <option value="{{ $d }}"
                {{ old('birthday_d', Auth::user()->birthday_format_d) == $d ? 'selected' : '' }}>
                {{ $d }}
            </option>
        @endfor
    </select>
</div>

<!--error message-->
@if ($errors->has('birthday'))
    <div class="text-danger mt-1">{{ $errors->first('birthday') }}</div>
@endif
