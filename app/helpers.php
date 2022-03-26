<?php

if (! function_exists('home_route')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function home_route()
    {
        if (Gate::allows('access backend')) {
            return route('home');
        }

        return route('login');
    }
}

if (! function_exists('is_admin_route')) {
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    function is_admin_route(Illuminate\Http\Request $request)
    {
        $action = $request->route()->getAction();

        return 'App\Http\Controllers\Backend' === $action['namespace'];
    }
}

if (! function_exists('image_template_url')) {
    /**
     * @param $template
     * @param $imagePath
     *
     * @return string
     */
    function image_template_url($template, $imagePath)
    {
        $imagePath = str_replace('/storage', '', $imagePath);

        return url(config('imagecache.route')."/{$template}/{$imagePath}");
    }
}

if (! function_exists('html_asset')) {
    /**
     * @param $template
     * @param $imagePath
     *
     * @return string
     */
    function html_asset($manifestName, $path)
    {
        static $manifest;

        $basePath = app()->environment('production') ? 'dist' : 'build';

        if (! $manifest
            && file_exists($manifestPath = public_path("{$basePath}/manifest-{$manifestName}.json"))
        ) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
        }

        if ($manifest && isset($manifest[$path])) {
            return $manifest[$path];
        }
    }
}

if (! function_exists('days_count')) {
    /**
     * @param $template
     * @param $imagePath
     *
     * @return string
     */
    function days_count($start_date, $end_date)
    {
        $holidays = App\Models\Holiday::whereYear('start_date', '=', date('Y'))->select(['start_date', 'end_date'])->get();

        return Illuminate\Support\Carbon::createFromFormat('Y-m-d', $end_date)->addDay(1)->diffInDaysFiltered(function (Illuminate\Support\Carbon $date) use ($holidays) {
            $d = $date->format('Y-m-d');
//            return !($date->isWeekend() || Holiday::whereRaw('? between start_date and end_date', [$d])->first());
            return ! ($date->isWeekend() || $holidays->where('start_date', '<=', $d)->where('end_date', '>=', $d)->count());
        }, Illuminate\Support\Carbon::createFromFormat('Y-m-d', $start_date));
    }
}

if (! function_exists('ksortRecursive')) {
    function ksortRecursive(&$array, $sort_flags = SORT_REGULAR) {
        if (!is_array($array)) return false;
        ksort($array, $sort_flags);
        foreach ($array as &$arr) {
            ksortRecursive($arr, $sort_flags);
        }
        return true;
    }
}
