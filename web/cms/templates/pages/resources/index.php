<?php
perch_layout('head');
perch_layout('header');
echo '<main role="main" id="main">';
perch_content('Introduction');
perch_collection('Resources', [
    'template'   => 'resource_list.html',
    'paginate'   => 'true',
    'count'      => 10,
    'sort'       => 'date',
    'sort-order' => 'DESC',
    'filter'     => 'status',
    'match'      => 'eq',
    'value'      => 'published',
]);
echo '</main>';
perch_categories([
    'set'      => 'resources',
    'template' => 'category_in_list.html',
]);
perch_layout('footer');
perch_layout('end');
