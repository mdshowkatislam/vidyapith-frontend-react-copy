

<?php

return [
	'mode'                       => 'utf-8',
    'format'                     => 'A4',
    'default_font_size'          => '12',
    'default_font'               => 'sans-serif',
    'margin_left'                => 20,
    'margin_right'               => 20,
    'margin_top'                 => 40,
    'margin_bottom'              => 10,
    'margin_header'              => 10,
    'margin_footer'              => 10,
    'orientation'                => 'P',
    'title'                      => 'Laravel mPDF',
    'author'                     => '',
    'watermark'                  => '',
    'show_watermark'             => false,
    'show_watermark_image'       => false,
    'watermark_font'             => 'sans-serif',
    'display_mode'               => 'fullpage',
    'watermark_text_alpha'       => 0.1,
    'watermark_image_path'       => '',
    'watermark_image_alpha'      => 0.2,
    'watermark_image_size'       => 'D',
    'watermark_image_position'   => 'P',
    'custom_font_dir'            => '',
    'auto_language_detection'    => false,
    'temp_dir'                   => base_path('../temp/'),
    'pdfa'                       => false,
    'pdfaauto'                   => false,
    'use_active_forms'           => false,

	'custom_font_dir' => base_path('resources/fonts/'),
	'custom_font_data' => [
		'nikosh' => [
			'R'  => 'Nikosh.ttf',    // regular font
			'B'  => 'Nikosh.ttf',       // optional: bold font
			'I'  => 'Nikosh.ttf',     // optional: italic font
			'BI' => 'Nikosh.ttf', // optional: bold-italic font
			'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
			'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
		],
		'nikoshban' => [
			'R'  => 'NikoshBAN.ttf',    // regular font
			'B'  => 'NikoshBAN.ttf',       // optional: bold font
			'I'  => 'NikoshBAN.ttf',     // optional: italic font
			'BI' => 'NikoshBAN.ttf', // optional: bold-italic font
			'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
			'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
		],
		'kalpurush' => [
			'R'  => 'Kalpurush.ttf',    // regular font
			'B'  => 'Kalpurush.ttf',       // optional: bold font
			'I'  => 'Kalpurush.ttf',     // optional: italic font
			'BI' => 'Kalpurush.ttf', // optional: bold-italic font
			'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
			'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
		],
		'SolaimanLipi' => [
			'R'  => 'SolaimanLipi.ttf',    // regular font
			'B'  => 'SolaimanLipi.ttf',       // optional: bold font
			'I'  => 'SolaimanLipi.ttf',     // optional: italic font
			'BI' => 'SolaimanLipi.ttf', // optional: bold-italic font
			'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
			'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
        ]
		// ...add as many as you want.
	]
];

