<?php
  $posts_per_page     = 10;
  $template           = 'post_in_list.html';
  $sort_order         = 'DESC';
  $sort_by            = 'postDateTime';
  $paginate           = true;
  $include_categories = function($item) {
                          $item['categories'] = perch_blog_post_categories($item['postID'], [
                            'template'=>'category_only.html'
                          ], true);
                          return $item;
                        };
  $posts_displayed = false;

  /* --------------------------- POSTS BY CATEGORY --------------------------- */
  if (perch_get('cat')) {
    echo '<h1>Category: &lsquo;'.perch_blog_category(perch_get('cat'), true).'&rsquo;</h1></header><main role="main">';

    perch_blog_custom([
      'category'   => perch_get('cat'),
      'template'   => $template,
      'count'      => $posts_per_page,
      'sort'       => $sort_by,
      'sort-order' => $sort_order,
      'paginate'   => $paginate,
      'each'       => $include_categories
    ]);

    $posts_displayed = true;
  }

  /* --------------------------- POSTS BY TAG --------------------------- */
  if (perch_get('tag')) {
    echo '<h1>Tag: &lsquo;'.perch_blog_tag(perch_get('tag'), true).'&rsquo;</h1></header><main role="main">';

    perch_blog_custom([
      'tag'      => perch_get('tag'),
      'template'   => $template,
      'count'      => $posts_per_page,
      'sort'       => $sort_by,
      'sort-order' => $sort_order,
      'paginate'   => $paginate,
      'each'       => $include_categories
    ]);

    $posts_displayed = true;
  }

  /* --------------------------- POSTS BY DATE RANGE --------------------------- */
  if (perch_get('year')) {

    $year              = intval(perch_get('year'));
    $date_from         = $year.'-01-01 00:00:00';
    $date_to           = $year.'-12-31 23:59:59';
    $title_date_format = '%Y';


    // Month and Year?
    if (perch_get('month')) {
      $month             = intval(perch_get('month'));
      $date_from         = $year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-01 00:00:00';
      $date_to           = $year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-31 23:59:59';
      $title_date_format = '%B %Y';
    }

    echo '<h1>Year: '.strftime($title_date_format, strtotime($date_from)).'</h1></header><main role="main">';

    perch_blog_custom([
      'filter'     => 'postDateTime',
      'match'      => 'eqbetween',
      'value'      => $date_from.','.$date_to,
      'template'   => $template,
      'count'      => $posts_per_page,
      'sort'       => $sort_by,
      'sort-order' => $sort_order,
      'paginate'   => $paginate,
      'each'       => $include_categories
    ]);

    $posts_displayed = true;
  }

  /* --------------------------- POSTS BY AUTHOR --------------------------- */
  if (perch_get('author')) {

    echo '<h1>Author: '.perch_blog_author(perch_get('author'), [
                        'template' => 'author_name.html',
                        ], true).'</h1></header><main role="main">';

    perch_blog_custom([
      'author'     => perch_get('author'),
      'template'   => $template,
      'count'      => $posts_per_page,
      'sort'       => $sort_by,
      'sort-order' => $sort_order,
      'paginate'   => $paginate,
      'each'       => $include_categories
    ]);

    $posts_displayed = true;
  }

  /* --------------------------- DEFAULT: ALL POSTS --------------------------- */

  if ($posts_displayed == false) {

    // No other options have been used; no posts have been displayed yet.
    // So display all posts.

    echo '<h1>Archive</h1></header><main role="main">';

    perch_blog_custom([
      'template'   => $template,
      'count'      => $posts_per_page,
      'sort'       => $sort_by,
      'sort-order' => $sort_order,
      'paginate'   => $paginate,
      'each'       => $include_categories
    ]);
  }
?>
