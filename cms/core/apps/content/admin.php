<?php
    $this->register_app('content', 'Pages', 1, 'Default app for managing content', $this->version);
    $this->add_setting('content_collapseList', 'Collapse content list', 'checkbox', false);
    $this->add_setting('content_singlePageEdit', 'Default to single-page edit mode', 'checkbox', false);
    $this->add_setting('content_hideNonEditableRegions', 'Hide regions you can\'t edit', 'checkbox', false);
    $this->add_setting('content_frontend_edit', 'Enable Ctrl-E to edit', 'checkbox', false);
?>