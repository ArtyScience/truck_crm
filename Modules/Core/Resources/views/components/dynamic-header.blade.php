<div>
    <header
        id="main_header"
        class="bg-white dark:bg-gray-800 shadow">
        <div class="dynamic_header max-w-7xl mx-auto relative">
            <div class="nav_top_title inline-flex ml-10 w-1/3">
                <h1 style="font-size: 11px;
                font-family:  'Noto Sans Tifinagh Agraw Imazighen'"
                    class="mt-3.5 text-amber-50 font-sans">
                    <span style="font-size: 26px; color: #e53e3e; text-shadow: 0px 1px 1px white">{{ $title[0] }}</span>
                    <span class="text-gray-700 dark:text-white">{{ __(ltrim($title, $title[0])) }}</span>
                </h1>
            </div>

            <v-my-allert :message="'DELETED'"></v-my-allert>

            <div class="inline-flex float-right absolute right-0 top-2 z-10">

                <div class="grid grid-cols-2 gap-1 mr-9 mt-2.5">
{{--                    <div class="pt-2">--}}
{{--                        <v-calculator></v-calculator>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        <div>--}}
{{--                            <v-balance></v-balance>--}}
{{--                        </div>--}}
{{--                        <div>--}}
{{--                            <v-time></v-time>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </header>
</div>

@push('styles')
    <style>

    </style>
@endpush

