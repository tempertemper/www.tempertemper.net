<?php
perch_layout('/project/head');
perch_layout('/project/header');
echo '<main role="main">';
echo '<aside>';
perch_collection('Projects', [
    'template' => 'project_details.html',
    'filter'   => 'slug',
    'match'    => 'eq',
    'value'    => perch_get('s'),
    'count'    => 1,
]);
echo '</aside>';
perch_collection('Projects', [
    'template' => 'project_detail.html',
    'filter'   => 'slug',
    'match'    => 'eq',
    'value'    => perch_get('s'),
    'count'    => 1,
]);
perch_layout('call_to_action');
echo '<p><a href="/projects/" class="back">Back to full list of work</a></p>';
echo '</main>';
perch_layout('footer');
perch_layout('end');
