<?php
$page = 'project';
perch_layout('head', [
    'page' => $page,
]);
perch_layout('header', [
    'page' => $page,
]);
echo '<main role="main" id="main">';
echo '<aside>';
perch_collection('Projects', [
    'template' => 'project_details.html',
    'filter'   => [
        [
            'filter'   => 'slug',
            'match'    => 'eq',
            'value'    => perch_get('s'),
            'count'    => 1,
        ],
        [
            'filter'   => 'published',
            'match'    => 'eq',
            'value'    => 'true',
        ],
    ],
]);
echo '</aside>';
perch_collection('Projects', [
    'template' => 'project_detail.html',
    'filter'   => [
        [
            'filter'   => 'slug',
            'match'    => 'eq',
            'value'    => perch_get('s'),
            'count'    => 1,
        ],
        [
            'filter'   => 'published',
            'match'    => 'eq',
            'value'    => 'true',
        ],
    ],
]);
perch_content('Call to action');
echo '<p><a href="/projects/" class="back">Back to full list of work</a></p>';
echo '</main>';
perch_layout('footer');
perch_layout('end');
