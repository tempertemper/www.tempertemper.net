<?php
perch_layout('head');
perch_layout('header');
echo '<main role="main" id="main">';
perch_content('Primary content');
echo '<p>Now what? Head <a href="/">back to the homepage</a> or have a read of one of these articles:</p>';
perch_collection('Resources', [
    'template'   => 'resource_list.html',
    'count'      => 3,
    'sort'       => 'date',
    'sort-order' => 'RAND',
    'filter'     => 'status',
    'match'      => 'eq',
    'value'      => 'published',
]);
echo '</main>';
perch_layout('footer');
perch_layout('end');
