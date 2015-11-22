<?php

    # Side panel
    echo $HTML->side_panel_start();

    echo $HTML->para('This page lists the blogs you can place posts into.');

    echo $HTML->side_panel_end();


    # Main panel
    echo $HTML->main_panel_start();

	include ('_subnav.php');


    echo '<a class="add button" href="'.$HTML->encode($API->app_path().'/blogs/edit/').'">'.$Lang->get('Add Blog').'</a>';


	# Title panel
    echo $HTML->heading1('Listing Blogs');



    if (PerchUtil::count($blogs)) {
?>
    <table class="d">
        <thead>
            <tr>
                <th class="first"><?php echo $Lang->get('Blog'); ?></th>
                <th><?php echo $Lang->get('Slug'); ?></th>
                <th><?php echo $Lang->get('Posts'); ?></th>
                <th class="action last"></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($blogs as $Blog) {
?>
            <tr>
                <td class="primary"><a href="<?php echo $HTML->encode($API->app_path()); ?>/blogs/edit/?id=<?php echo $HTML->encode(urlencode($Blog->id())); ?>"><?php echo $HTML->encode($Blog->blogTitle())?></a></td>
                <td><?php echo $HTML->encode($Blog->blogSlug())?></td>
                <td><?php echo $HTML->encode($Blog->blogPostCount())?></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/blogs/delete/?id=<?php echo $HTML->encode(urlencode($Blog->id())); ?>" class="delete inline-delete" data-msg="<?php echo $Lang->get('Delete this blog?'); ?>"><?php echo $Lang->get('Delete'); ?></a></td>
            </tr>
<?php
    }
?>
        </tbody>
    </table>



<?php
    } // if pages


    echo $HTML->main_panel_end();
