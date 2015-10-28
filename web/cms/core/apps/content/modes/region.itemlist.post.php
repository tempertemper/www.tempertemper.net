<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
    <h3 class="em"><span><?php echo PerchLang::get('About this region'); ?></span></h3>

    <p><?php
            echo PerchLang::get("This region may contain one or more items.");
            echo ' ';
            echo PerchLang::get("Select an item to edit its content.");
    ?></p>

    <?php
        if ($Region->regionTemplate() != '') {

            if ($CurrentUser->has_priv('content.regions.options')) {
                echo '<h4>'.PerchLang::get('Options').'</h4>';
            }else{
                echo '<h4>' . PerchLang::get('Page assignment') . '</h4>';
            }

            if ($Region->regionPage() == '*') {
                echo '<p>' . PerchLang::get('This region is shared across all pages.') . '</p>';
            }else{
                echo '<p>' . PerchLang::get('This region is only available within') . ':</p><p><code><a href="' . PerchUtil::html($Region->regionPage()) . '">' . PerchUtil::html($Region->regionPage()) . '</a></code></p>';
            }

            if ($CurrentUser->has_priv('content.regions.options')) {
                echo '<p>';
                echo ' <a href="'.PERCH_LOGINPATH . '/core/apps/content/options/?id='.PerchUtil::html($Region->id()).'">' . PerchLang::get('Set your options for this region.') . '</a></p>';
            }

        }
    ?>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>

    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>">
        <div><?php echo $Form->submit('add_another', 'Add another item', 'add button topadd'); ?></div>
    </form>

    <h1><?php
            printf(PerchLang::get('Editing %s Region'),' &#8216;' . PerchUtil::html($Region->regionKey()) . '&#8217; ');
        ?></h1>

    <?php echo $Alert->output(); ?>



	<ul class="smartbar">
        <li class="selected">
			<span class="set">
			<a class="sub" href="<?php
                    if ($Region->regionPage()=='*') {
                        echo PERCH_LOGINPATH . '/core/apps/content/page/?id=-1';
                    }else{
                        echo PERCH_LOGINPATH . '/core/apps/content/page/?id='.PerchUtil::html($Region->pageID());
                    }
                ?>"><?php echo PerchLang::get('Regions'); ?></a>
			<span class="sep icon"></span>
			<a href="<?php echo PERCH_LOGINPATH . '/core/apps/content/edit/?id='.PerchUtil::html($Region->id());?>"><?php echo PerchUtil::html($Region->regionKey()); ?></a>
			</span>
		</li>
		<?php
			if ($CurrentUser->has_priv('content.regions.options')) {
	            echo '<li><a href="'.PERCH_LOGINPATH . '/core/apps/content/options/?id='.PerchUtil::html($Region->id()).'">' . PerchLang::get('Region Options') . '</a></li>';
	        }

		?>
		<li class="fin"><a class="icon reorder" href="<?php echo PERCH_LOGINPATH . '/core/apps/content/reorder/region/?id='.PerchUtil::html($Region->id());?>"><?php echo PerchLang::get('Reorder'); ?></a></li>
        <?php

            if (PERCH_RUNWAY) {
                echo '<li class="fin"><a class="icon undo" href="'.PERCH_LOGINPATH . '/core/apps/content/revisions/?id='.PerchUtil::html($Region->id()).'">' . PerchLang::get('Revision History') . '</a></li>';
            }

            if ($Page->pagePath() != '*') {
                $view_page_url = rtrim($Settings->get('siteURL')->val(), '/').$Page->pagePath();

                echo '<li class="fin">';
                echo '<a href="'.PerchUtil::html($view_page_url).'" class="icon page assist">' . PerchLang::get('View Page') . '</a>';
                echo '</li>';
            }

        ?>
    </ul>






    <?php
        if (PerchUtil::count($items)) {

            echo '<table class="d itemlist">';
                echo '<thead>';
                    echo '<tr>';
                        foreach($cols as $col) {
                            echo '<th>'.PerchUtil::html($col['title']).'</th>';
                        }
                        echo '<th class="last action"></th>';
                    echo '</tr>';
                echo '</thead>';

                echo '<tbody>';
                $Template = new PerchTemplate;
                $i = 1;
                foreach($items as $item) {
                    echo '<tr>';
                        $first = true;
                        foreach($cols as $col) {

                            if ($first) {
                                echo '<td class="primary">';
                                echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/edit/?id=' . PerchUtil::html($Region->id()) . '&amp;itm='.PerchUtil::html($item['itemID']).'">';
                            }else{
                                echo '<td>';
                            }

                            if ($col['id']=='_title') {
                                if (isset($item['_title'])) {
                                    $title = $item['_title'];
                                }else{
                                    $title = PerchLang::get('Item').' '.$i;
                                }
                            }else{
                                if (isset($item[$col['id']])) {
                                    $title = $item[$col['id']];
                                }else{
                                    if ($first) {
                                        if (isset($item['_title'])) {
                                            $title = $item['_title'];
                                        }else{
                                            $title = PerchLang::get('Item').' '.$i;
                                        }
                                    }else{
                                        $title = '-';
                                    }
                                }

                            }

                            if ($col['Tag']) {

                                $FieldType = PerchFieldTypes::get($col['Tag']->type(), false, $col['Tag']);

                                $title = $FieldType->render_admin_listing($title);

                                if ($col['Tag']->format()) {
                                    $title = $Template->format_value($col['Tag'], $title);
                                }
                            }

                            if ($first && trim($title)=='') $title = '#'.$item['_id'];

                            echo $title;

                            if ($first) echo '</a>';

                            echo '</td>';

                            $first = false;
                        }
                        echo '<td>';
                            echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/delete/item/?id=' . PerchUtil::html($Region->id()) . '&amp;itm='.PerchUtil::html($item['itemID']).'" class="delete inline-delete">'.PerchLang::get('Delete').'</a>';
                        echo '</td>';
                    echo '</tr>';
                    $i++;
                }
                echo '</tbody>';


            echo '</table>';


        }

    ?>



<?php include (PERCH_PATH.'/core/inc/main_end.php');
