<?php

use Rah\Danpu\Dump;
use Rah\Danpu\Config;
use Rah\Danpu\Export;
use Rah\Danpu\Import;


class PerchBackupPlan extends PerchBase
{
    protected $table        = 'backup_plans';
    protected $pk           = 'planID';
    protected $event_prefix = 'backup_plan';

    public $message = false;
    public $db_file = false;

    private $TargetBucket = false;

    public function last_run_date_for_display()
    {
    	$Runs = new PerchBackupRuns();
    	$Run = $Runs->get_last_run($this->id());

    	if ($Run) {
    		return strftime(PERCH_DATE_SHORT.' '.PERCH_TIME_SHORT, strtotime($Run->runDateTime()));
    	} else {
            return ' - ';
        }

    	return false;
    }


    /**
     * Run the backup. Prioritises db backups. If not, does some resources.
     * @return [type] [description]
     */
    public function run($force_db_backup=false)
    {

        if ($force_db_backup || $this->db_backup_due()) {
        
            // Run a database backup
            $this->run_database_backup();

            if ($this->message) {

                $this->log_run('FAILED', 'db', $this->message);             

                return [
                    'result' => 'FAILED',
                    'message' => $this->message,
                ];
            }

            $this->log_run('OK', 'db', 'Completed', $this->db_file);    

            return [
                    'result' => 'OK',
                    'message' => $this->planTitle().' completed a database backup.',
                ];

        }else{

            // Back up any resources

            // check it's not a db-only plan
            if ($this->planRole()=='all') {

                $item_count = 10;

                $number_backed_up = $this->run_resource_backup($item_count);  

                if ($this->message) {
                    return [
                        'result' => 'FAILED',
                        'message' => $this->message,
                    ];
                }

                if ($number_backed_up > 0) {
                    return [
                        'result' => 'OK',
                        'message' => $this->planTitle().' backed up '.$number_backed_up.' assets.',
                    ];
                }

            }

        }


    }



    public function run_database_backup()
    {
        $Bucket  = PerchResourceBuckets::get($this->planBucket());

        $conf = PerchConfig::get('env');

        $prefix = PerchUtil::urlify($this->planTitle());
        $ext    = function_exists('gzopen') ?'.sql.gz' : '.sql';
        
        $filename = 'db_'.$prefix.'_'.date('YmdHi').$ext;
        $tmp      = 'tmp_'.$prefix.'_'.date('YmdHi').$ext;

        $this->db_file = $filename;

        $this->dump_db( $conf['temp_folder'],
                        $tmp, 
                        $Bucket->get_file_path().'/'.$filename
                    );
    }


    public function run_resource_backup($item_count=5)
    {

        $this->TargetBucket = PerchResourceBuckets::get($this->planBucket());

        // get remote buckets to exlude them from the backup.
        // don't want to vanish up our own backside
        $remote_buckets = PerchResourceBuckets::get_all_remote();
        if (PerchUtil::count($remote_buckets)) {
            $buckets = [];
            foreach($remote_buckets as $Bucket) {
                $buckets[] = $Bucket->get_name();
            }
        }

        $sql = 'SELECT * FROM '.PERCH_DB_PREFIX.'resources r 
                WHERE resourceID NOT IN (
                    SELECT resourceID FROM '.PERCH_DB_PREFIX.'backup_resources WHERE resourceID=r.resourceID and planID='.$this->db->pdb((int)$this->id()).'
                ) 
                AND r.resourceAWOL=0 ';
        if (PerchUtil::count($buckets)) {
            $sql .= ' AND r.resourceBucket NOT IN ('.$this->db->implode_for_sql_in($buckets).') ';
        }

        $sql .= ' LIMIT '.$item_count;
        
        $rows = $this->db->get_rows($sql);

        if (PerchUtil::count($rows)) {

            $Run = $this->log_run('IN PROGRESS', 'resources');

            $backed_up = 0;
            foreach($rows as $row) {
                $result = $this->copy_resource($row);

                if ($result) {
                    $backed_up++;

                    $this->db->insert(PERCH_DB_PREFIX.'backup_resources', [
                            'resourceID' => $row['resourceID'],
                            'planID'     => $this->id(),
                            'runID'      => $Run->id(),
                        ]);
                }
            }

            if ($backed_up > 0) {
                $Run->update([
                    'runResult' => 'OK',
                    'runMessage'=> $this->planTitle().' backup up '.$backed_up.' assets.',
                    ]);
            }

            return $backed_up;
        }


    }

    private function copy_resource($res)
    {
        if ($res['resourceBucket']!='' && $res['resourceFile']!='') {
            $Bucket = PerchResourceBuckets::get($res['resourceBucket']);
            $from   = PerchUtil::file_path($Bucket->get_file_path().'/'.$res['resourceFile']);

            if (file_exists($from) && is_readable($from)) {

                $to_bucket = $this->TargetBucket->get_file_path().'/'.$res['resourceBucket'];

                $to     = $to_bucket.'/'.$res['resourceFile'];

                return copy($from, $to);

            }else{
                // File doesn't exist, so mark as AWOL
                $Assets = new PerchAssets_Assets($this->api);
                $Asset = $Assets->return_instance($res);
                if ($Asset) {
                    $Asset->mark_as_awol();
                }
                return false;
            }
        }

        return true;
    }


    /**
     * Do the DB dump
     * Because the stream wrappers can be a bit flakey for cloud storage services, 
     * we do the dump to a temp file locally first, then copy it up.
     * 
     * @param  [type] $tmp_folder [description]
     * @param  [type] $tmp_file   [description]
     * @param  [type] $target     [description]
     * @return [type]             [description]
     */
    private function dump_db($tmp_folder, $tmp_file, $target)
    {
        $tmp_path = PerchUtil::file_path($tmp_folder.'/'.$tmp_file);

        try {
            $DB = PerchDB::fetch();
            
            $dump = new Dump;
            $dump
                ->file($tmp_path)
                ->dsn($DB->dsn)
                ->user(PERCH_DB_USERNAME)
                ->pass(PERCH_DB_PASSWORD)
                ->tmp($tmp_folder);

            new Export($dump);

            copy($tmp_path, $target);
            unlink($tmp_path);

            return true;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->db_file = false;
            return false;
        }
    }

    private function db_backup_due()
    {
        $Runs = new PerchBackupRuns;
        $Run  = $Runs->get_last_database_run($this->id());

        if (!$Run) return true;
        
        $freq     = (int)$this->planFrequency();
        $last_run = strtotime($Run->runDateTime());

        $threshold = strtotime('-'.$freq.' HOURS');

        if ($last_run < $threshold) return true;

        return false;
    }

    private function log_run($result, $type, $message='', $db_file='')
    {
        $BackupRuns = new PerchBackupRuns();
        $Run = $BackupRuns->create([
                'planID'      => $this->id(),
                'runDateTime' => date('Y-m-d H:i:s'),
                'runType'     => $type,
                'runResult'   => $result,
                'runMessage'  => $message,
                'runDbFile'   => $db_file,
            ]);
        return $Run;
    }

}