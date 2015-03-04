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
<?php include ($app_path.'/modes/_subnav.php'); ?>


    <h1><?php 
            printf(PerchLang::get('Editing %s Region'),' &#8216;' . PerchUtil::html($Region->regionKey()) . '&#8217; '); 
        ?></h1>
    
    <?php echo $Alert->output(); ?>



    <ul class="smartbar">
        <li>
            <span class="set">
            <a class="sub" href="<?php 
                    if ($Region->regionPage()=='*') {
                        echo PERCH_LOGINPATH . '/core/apps/content/page/?id=-1';
                    }else{
                        echo PERCH_LOGINPATH . '/core/apps/content/page/?id='.PerchUtil::html($Region->pageID());
                    }
                ?>">Regions</a> 
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
                echo '<li class="fin selected"><a class="icon undo" href="'.PERCH_LOGINPATH . '/core/apps/content/revisions/?id='.PerchUtil::html($Region->id()).'">' . PerchLang::get('Revision History') . '</a></li>';
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
        if (PerchUtil::count($revisions)) {

            $Users = new PerchUsers;
            
            echo '<table class="d itemlist">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>'.PerchLang::get('Revision').'</th>';
                        echo '<th>'.PerchLang::get('Date').'</th>';
                        echo '<th>'.PerchLang::get('Created by').'</th>';
                        if ($preview_url) echo '<th class="action"></th>';
                        echo '<th class="last action"></th>';
                    echo '</tr>';
                echo '</thead>';
            
                echo '<tbody>';
                foreach($revisions as $revision) {
                    echo '<tr>';
                        echo '<td class="primary">';
                        if ($revision['itemRev']==$Region->regionRev()) {
                            echo '<span class="revision-published" title="'.PerchLang::get('Published revision').'">'.$revision['itemRev'].'</span>';
                        }elseif ($revision['itemRev']==$Region->regionLatestRev() && $Region->regionLatestRev()>$Region->regionRev()) {
                            echo '<span class="revision-draft" title="'.PerchLang::get('Unpublished draft').'">'.$revision['itemRev'].'</span>';
                        }else{
                            echo $revision['itemRev'];
                        }
                        echo '</td>';
                        echo '<td>';
                            if ($revision['itemUpdated']!='0000-00-00 00:00:00') {
                                echo strftime(PERCH_DATE_LONG.' '.PERCH_TIME_SHORT, strtotime($revision['itemUpdated']));
                            }else{
                                echo '<span class="minor-note">'.PerchLang::get('Not logged').'</span>';
                            }
                        echo '</td>';
                        echo '<td>'.$Users->get_user_display_name($revision['itemUpdatedBy']).'</td>';
                        if ($preview_url) {
                            echo '<td>';
                                echo '<a href="'.PerchUtil::html($preview_url.$revision['itemRev']).'" class="preview">'.PerchLang::get('Preview').'</a>';
                            echo '</td>';
                        }
                        echo '<td>';
                            if ($revision['itemRev']<$Region->regionRev()) {
                                echo '<a href="'.PerchUtil::html(PERCH_LOGINPATH).'/core/apps/content/revisions/revert/?id=' . PerchUtil::html($Region->id()) . '&amp;rev='.PerchUtil::html($revision['itemRev']).'" class="positive inline-confirm">'.PerchLang::get('Roll back').'</a>';
                            }
                        echo ' </td>';
                    echo '</tr>';
                }
                echo '</tbody>';
            
            
            echo '</table>';
            
            
        }
    
    ?>



<?php include (PERCH_PATH.'/core/inc/main_end.php'); ?>
