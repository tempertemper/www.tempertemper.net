<?php
        echo $HTML->title_panel([
            'heading' => $Lang->get('Editing ‘%s’ master page', PerchUtil::html($Template->templateTitle())),
            ]);

        echo $HTML->heading2('Details');
?>
    <form method="post" action="<?php echo PerchUtil::html($Form->action()); ?>" class="form-simple">

        <div class="field-wrap">
            <?php echo $Form->label('templateTitle', 'Title'); ?>
            <div class="form-entry">
            <?php echo $Form->text('templateTitle', $Form->get($details, 'templateTitle')); ?>
            </div>
        </div>
        
        <div class="field-wrap">
            <?php echo $Form->label('optionsPageID', 'Copy region options from'); ?>
            <div class="form-entry">
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
        </div>
        <?php if (!PERCH_RUNWAY) { ?>
        <div class="field-wrap">
            <?php echo $Form->label('templateReference', 'New pages should'); ?>
            <div class="form-entry">
            <?php 
                $opts = array();
        		$opts[] = array('label'=>PerchLang::get('Reference this master page'), 'value'=>1);
        		$opts[] = array('label'=>PerchLang::get('Copy this master page'), 'value'=>0);
                echo $Form->select('templateReference', $opts, $Form->get($details, 'templateReference')); 
            ?>
            </div>
        </div>
        <?php }// Runway ?>

<?php
    if (PerchUtil::count($navgroups)) {
        echo '<h2 class="divider"><div>'.PerchLang::get('Navigation groups').'</div></h2>';

        //echo '<div class="field-wrap">';

            $opts = array();
            
            $vals = explode(',', $Template->templateNavGroups());

            if (!$vals) $vals = array();

            foreach($navgroups as $Group) {
                $opts[] = array('label'=>$Group->groupTitle(), 'value'=>$Group->id());
            }
        
            
            echo $Form->checkbox_set('navgroups', 'Add new pages to', $opts, $vals, $class='', $limit=false);
            
        //echo '</div>';

    }


        if (PERCH_RUNWAY) { 

            echo '<h2 class="divider"><div>'.PerchLang::get('Routes').'</div></h2>';
     

            if (PerchUtil::count($routes)) {

                foreach($routes as $Route) {

                    echo '<div class="field-wrap routes">';
                        $id = 'routePattern_'.$Route->id();
                        echo $Form->label($id, 'URL pattern');
                        echo '<div class="form-entry">';
                        echo $Form->text($id, $Form->get($details, $id, $Route->routePattern()));
                        echo '</div>';
                    echo '</div>';
                }
            }

            echo '<div class="field-wrap routes-spare">';
                echo $Form->label('new_pattern', 'URL pattern');
                echo '<div class="form-entry">';
                echo $Form->text('new_pattern', $Form->get($details, 'new_pattern'));
                echo '</div>';
            echo '</div>';

        }




?>



        <p class="submit-bar">
            <?php echo $Form->submit('btnsubmit', 'Submit', 'button'); ?>
        </p>
        
    </form>
