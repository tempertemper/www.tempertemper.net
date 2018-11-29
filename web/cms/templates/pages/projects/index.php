<?php
perch_layout('head');
perch_layout('header');
echo '<main role="main" id="main">';
perch_content('Introduction');
perch_collection('Projects', [
    'template' => 'project_list.html',
    'filter'   => 'published',
    'match'    => 'eq',
    'value'    => 'true',
]);
echo '</main>';
perch_layout('footer');
perch_layout('end');
