<?php
perch_layout('head');
perch_layout('header');
echo '<main role="main" id="main">';
perch_content('Introduction');
perch_collection('Services', [
    'template'   => 'service_list.html',
    'paginate'   => 'true',
    'count'      => 5,
]);
echo '</main>';
perch_layout('footer');
perch_layout('end');
