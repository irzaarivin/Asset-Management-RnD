@extends(backpack_view('blank'))

@php
    if (config('backpack.base.show_getting_started')) {
        $widgets['before_content'][] = [
            'type'        => 'view',
            'view'        => 'backpack::inc.getting_started',
        ];
    } else {
        $widgets['before_content'][] = [
            'type'        => 'jumbotron',
            'heading'     => 'Asset Management',
            'class'       => 'bg-secondary',
            'content'     => 'Semua aset anda dapat dikelola disini, ' . backpack_user()->name . '.',
            'button_link' => backpack_url('asset'),
            'button_text' => 'Lihat Asset',
        ];

        $widgets['before_content'][] = [
            'type'    => 'div',
            'class'   => 'row mt-4',
            'content' => [
                [
                    'type'        => 'progress',
                    'class'       => 'card text-white bg-primary mb-2 w-100 w-100',
                    'value'       => $totalUser,
                    'description' => 'Registered users.',
                    // 'progress'    => 57,
                    'hint'        => 'Tambahkanlah user lain.',
                ],
                [
                    'type'        => 'progress',
                    'class'       => 'card text-white bg-primary mb-2 w-100',
                    'value'       => $totalAsset,
                    'description' => 'Registered assets.',
                    // 'progress'    => 57,
                    'hint'        => 'Jumlah asset yangg terdaftar.',
                ],
                // [
                //     'type'        => 'progress',
                //     'class'       => 'card text-white bg-primary mb-2 w-100',
                //     'value'       => '11.456',
                //     'description' => 'Registered users.',
                //     'progress'    => 57,
                //     'hint'        => '8544 more until next milestone.',
                // ],
                // [
                //     'type'        => 'progress',
                //     'class'       => 'card text-white bg-primary mb-2 w-100',
                //     'value'       => '11.456',
                //     'description' => 'Registered users.',
                //     'progress'    => 57,
                //     'hint'        => '8544 more until next milestone.',
                // ]
            ]
        ];

        $widgets['before_content'][] = [
            'type'    => 'div',
            'class'   => 'row mt-4',
            'content' => [
                [
                    'type'       => 'chart',
                    'controller' => \App\Http\Controllers\Admin\Charts\AssetStatusChartController::class,

                    // OPTIONALS

                    'class'   => 'card mb-2',
                    'wrapper' => ['class'=> 'col-md-6'] ,
                    // 'content' => [
                        // 'header' => 'New Users',
                        // 'body'   => 'This chart should make it obvious how many new users have signed up in the past 7 days.<br><br>',
                    // ],
                ],
                [
                    'type'       => 'chart',
                    'controller' => \App\Http\Controllers\Admin\Charts\AssetCategoryChartController::class,

                    // OPTIONALS

                    'class'   => 'card mb-2',
                    'wrapper' => ['class'=> 'col-md-6'] ,
                    // 'content' => [
                        // 'header' => 'New Users',
                        // 'body'   => 'This chart should make it obvious how many new users have signed up in the past 7 days.<br><br>',
                    // ],
                ]
            ]
        ];
    }
@endphp

@section('content')

    {{-- <h1>Halo</h1> --}}

@endsection
