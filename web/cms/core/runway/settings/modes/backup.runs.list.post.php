<?php 

    $form_button = [
                'action' => $Form->action(),
                'button' => $Form->submit('backup', 'Backup now', 'button button-icon icon-left', true, true, PerchUI::icon('ext/umbrella', 14))
            ];


    echo $HTML->title_panel([
            'heading' => $Lang->get('Viewing ‘%s’ backup plan', $HTML->encode($Plan->planTitle())),
            'form'  => $form_button,
        ], $CurrentUser);


    $Smartbar = new PerchSmartbar($CurrentUser, $HTML, $Lang);

    $Smartbar->add_item([
            'active' => true,
            'type' => 'breadcrumb',
            'links' => [
                [   
                    'title' => 'Plans',
                    'link'  => '/core/settings/backup/',
                ],
                [
                    'title' => $Plan->planTitle(),
                    'translate' => false,
                    'link '=> '/core/settings/backup/?id='.$Plan->id(),
                ]
            ],
            
        ]);

    $Smartbar->add_item([
            'title' => 'Plan Options',
            'link'  => '/core/settings/backup/edit/?id='.$Plan->id(),
            'icon' => 'core/o-toggles'
        ]);

    echo $Smartbar->render();


    $Listing = new PerchAdminListing($CurrentUser, $HTML, $Lang, $Paging);
    $Listing->add_col([
            'title'     => 'Status',
            'value'     => 'runResult',
            'icon'      => function($Run) {
                switch($Run->runResult()) {
                    case 'OK':
                        return PerchUI::icon('core/circle-check', 16, null, 'icon-status-success');
                        break;

                    case 'FAILED':
                        return PerchUI::icon('core/circle-delete', 16, null, 'icon-status-alert');
                        break;

                    case 'WARNING':
                        return PerchUI::icon('core/alert', 16, null, 'icon-status-warning');
                        break;

                    default:
                        return PerchUI::icon('core/info-alt', 16, null, 'icon-status-info');
                        break;
                }
                
            }
        ]);
    $Listing->add_col([
            'title'     => 'Date',
            'value'     => function($Run){
                return strftime(PERCH_DATE_SHORT.' '.PERCH_TIME_SHORT, strtotime($Run->runDateTime()));
            },
        ]);
    $Listing->add_col([
            'title'     => 'Message',
            'value'     => function($Run){
                if ($Run->runType()=='db') {
                    if ($Run->runDbFile()!='') {
                        return PerchLang::get('Database backed up.');
                    }else{
                        return PerchUtil::html($Run->runMessage());
                    }
                }else{
                    return PerchUtil::html($Run->runMessage());
                }
            },
        ]);
    $Listing->add_misc_action([
            'title'   => 'Restore',
            'path'    => function($Run) {
                return PERCH_LOGINPATH.'/core/settings/backup/restore/?id='.$Run->id();
            },
            'class'   => 'warning',
            'priv'    => 'runway.backups.restore',
            'display' => function($Run) {
                return $Run->runDbFile()!='';
            }
        ]);
    


    echo $Listing->render($runs);
