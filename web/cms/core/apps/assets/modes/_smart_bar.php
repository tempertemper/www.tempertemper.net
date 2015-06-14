	<ul class="smartbar">
        <li><a href="?<?php
            $opts = $_GET;
            $opts['view'] = ($view=='grid' ? 'list' : 'grid');
            $current_view = ($view=='grid' ? 'grid' : 'list');
            echo PerchUtil::html(http_build_query($opts), true);
        ?>" class="set asset-view-mode <?php echo $current_view; ?>"><?php echo PerchLang::get('View'); ?></a></li>
        <?php
            // Type filter
            $types = $Assets->get_available_types();
            if (PerchUtil::count($types)) {              
                $items = array();

                $group_types = PerchAssets_Asset::get_type_map();

                foreach ($group_types as $type=>$val) {
                    $items[] = array(
                        'arg'   => 'type',
                        'val'   => $type,
                        'label' => $val['label'],
                        'path'  => $base_path,
                    );
                }


                foreach ($types as $type) {
                    $items[] = array(
                        'arg'   => 'type',
                        'val'   => $type,
                        'label' => strtoupper($type),
                        'path'  => $base_path,
                    );
                }
                echo PerchUtil::smartbar_filter('type', 'By Asset Type', 'Filtered by type ‘%s’', $items, 'asset-icon asset-type', $Alert, "You are viewing assets filtered by type ‘%s’", $base_path);
            }

            // Bucket filter
            $buckets = $Assets->get_available_buckets();
            if (PerchUtil::count($buckets)) {              
                $items = array();
                foreach ($buckets as $bucket) {
                    $items[] = array(
                        'arg'   => 'bucket',
                        'val'   => $bucket,
                        'label' => ucfirst($bucket),
                        'path'  => $base_path,
                    );
                }
                echo PerchUtil::smartbar_filter('bucket', 'By Bucket', 'Filtered by bucket ‘%s’', $items, 'asset', $Alert, "You are viewing assets filtered by bucket ‘%s’", $base_path);
            }
        ?>
        <li class="fin">
            <form method="get" action="?" class="search">
                <input name="q" type="text" placeholder="<?php echo PerchLang::get('Search'); ?>" type="required" class="search" value="<?php
                    if ($term) {
                        echo PerchUtil::html($term, true);
                    }
                ?>" />
                <button type="submit"><img src="<?php echo PERCH_LOGINPATH.'/core/assets/img/search.svg'; ?>" /></button>
                <?php
                    $opts = $_GET;
                    if (isset($opts['q'])) unset($opts['q']);
                    if (PerchUtil::count($opts)) {
                        foreach($opts as $key=>$val) {
                            echo '<input type="hidden" name="'.PerchUtil::html($key, true).'" value="'.PerchUtil::html($val, true).'" />';
                        }
                    }
                ?>
            </form>
        </li>
    </ul>
    <?php echo $Alert->output(); ?>
    <?php
        if (isset($filters) && isset($filters['bucket'])) {
            echo '<script>head.ready(function(){Perch.UI.Assets.setTargetBucket("'.PerchUtil::html($filters['bucket'], true).'");});</script>';
        }
    ?>