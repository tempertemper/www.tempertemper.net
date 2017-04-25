<?php

	if (defined('PERCH_LICENSE_KEY')) {
        if (strpos(PERCH_LICENSE_KEY, '-LOCAL-')==2) {
        	// This is to remind you that you need to pay for a license to use the software publicly.
        	// If you don't pay for licenses we can't develop or support this CMS. We'll have to go and find other jobs and Perch will go away.
        	echo '<div class="notification notification-info">';
            echo '<a href="https://grabaperch.com/buy" class="notification-link">Licensed for local testing</a>';
            echo '</div>';
        }
    }

?>
</div>
</div>
</div>