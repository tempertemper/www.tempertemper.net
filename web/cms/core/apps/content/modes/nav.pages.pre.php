<?php
    
    $NavGroups  = new PerchContent_NavGroups;
    $Pages      = new PerchContent_Pages;

        
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $groupID = (int) $_GET['id'];    
        $NavGroup = $NavGroups->find($groupID);
    }else{
        $groupID = false;
        $NavGroup = false;
    }
    
    

?>