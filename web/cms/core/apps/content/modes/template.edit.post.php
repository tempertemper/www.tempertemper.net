<?php include (PERCH_PATH.'/core/inc/sidebar_start.php'); ?>
<p><?php echo PerchLang::get('The title you give to this page will display when the user is selecting a master page.'); ?></p>

<p><?php echo PerchLang::get('You can select a page to copy regions from. This means that when a user creates a new page the regions will not show up as NEW but instead will take on the types set for the page you are copying. No content is copied.'); ?></p>

<p><?php echo PerchLang::get('If you reference this master page then changes made to the template will reflect on all pages that use the master page. This is usually what you want. If new pages copy this master page then if you make a change to your site design you will need to update all of the created pages individually.'); ?></p>
<?php include (PERCH_PATH.'/core/inc/sidebar_end.php'); ?>
<?php include (PERCH_PATH.'/core/inc/main_start.php'); ?>
<?php include ('_subnav.php'); ?>


    <h1><?php 
            printf(PerchLang::get('Editing %s Master Page'), PerchUtil::html($Template->templateTitle())); 
        ?></h1>

    
    <?php echo $Alert->output(); ?>

    <h2><?php echo PerchLang::get('Details'); ?></h2>
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="sectioned">

        <div class="field">
            <?php echo $Form->label('templateTitle', 'Title'); ?>
            <?php echo $Form->text('templateTitle', $Form->get($details, 'templateTitle')); ?>
        </div>
        
        <div class="field">
            <?php echo $Form->label('optionsPageID', 'Copy region options from'); ?>
            <?php 
                $opts = array();
                $opts[] = array('label'=>PerchLang::get('Do not copy'), 'value'=>'0');
  
                $pages = $Pages->get_page_tree();
                if (PerchUtil::count($pages)) {
                    foreach($pages as $Item) {
                        $opts[] = array('label'=>str_repeat('-', ($Item->pageDepth()-1)).' '.$Item->pageNavText(), 'value'=>$Item->id());
                    }
                }
                
                
                echo $Form->select('optionsPageID', $opts, $Form->get($details, 'optionsPageID')); 
            ?>
        </div>
        <?php if (!PERCH_RUNWAY) { ?>
        <div class="field">
            <?php echo $Form->label('templateReference', 'New pages should'); ?>
            <?php 
                $opts = array();
        		$opts[] = array('label'=>PerchLang::get('Reference this master page'), 'value'=>1);
        		$opts[] = array('label'=>PerchLang::get('Copy this master page'), 'value'=>0);
                echo $Form->select('templateReference', $opts, $Form->get($details, 'templateReference')); 
            ?>
        </div>
        <?php }// Runway ?>

<?php
    if (PerchUtil::count($navgroups)) {
        echo '<h2>'.PerchLang::get('Navigation groups').'</h2>';

        echo '<div class="field last">';

            $opts = array();
            
            $vals = explode(',', $Template->templateNavGroups());

            if (!$vals) $vals = array();

            foreach($navgroups as $Group) {
                $opts[] = array('label'=>$Group->groupTitle(), 'value'=>$Group->id());
            }
        
            
            echo $Form->checkbox_set('navgroups', 'Add new pages to', $opts, $vals, $class='', $limit=false);
        
        echo '</div>';

    }

?>



        <p class="submit">
            <?php echo $Form->submit('btnsubmit', 'Submit', 'button'); ?>
        </p>
        
    </form>


<?php include (PERCH_PATH.'/core/inc/main_end.php'); 



