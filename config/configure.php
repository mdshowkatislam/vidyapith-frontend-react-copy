<?php

$url = env('API_GATEWAY_URL', 'http://api2.project-ca.com/api/');
// $url = env('API_GATEWAY_URL', 'https://api.project-ca.com/api/');
// $url = 'https://api-gateway.project-ca.com/api/v1';

return [
    'class_api' => $url . 'v1/classes',
    'class_wise_subject_api' => $url . 'v1/class-wise-subjects',
    'subject_wise_chapter_api' => $url . 'v1/subject-wise-chapters',
    'competences_by_subject_api' => $url . 'v1/competences-by-subject',
    'oviggotas_by_subject_api' => $url . 'v1/oviggota-by-subject',
    'dimensions_by_subject_api' => $url . 'v1/dimension-by-subject',
    'bi_dimension_api' => $url . 'v2/bi-dimension',
    'competences_by_chapter_api' => $url . 'v1/competences-by-chapter',
    'bis_api' => $url . 'v1/bis',
    'assessment_api' => $url . 'v1/assessments',
    'pi_weight_api' => $url . 'v1/pi-weight',
    'pi_selection_by_subject' => $url . 'v1/pi-selection-list-by-subject',
    'single_pi' => $url . 'v2/single-pi',
];
