<?php

use IndieWeb\MentionClient;

class PerchBlog_WebmentionProvider
{
	private $queue_table;
	private $db;
	private $api;

	public function __construct()
	{
		$this->db          = PerchDB::fetch();
		$this->api         = new PerchAPI(1.0, 'perch_blog');
		$this->queue_table = PERCH_DB_PREFIX.'blog_webmention_queue';
	}


	public function send_for_post($Post)
	{
		$url  = $Post->postURL(true);
		$html = perch_blog_post($Post->postSlug(), true);

		$client = new MentionClient();
		$sent = $client->sendMentions($url, $html);
	}

	public function receive_notification($source_url, $target_url, $type = "post", $id)
	{
		// source is the url of the external post
		// target is the url of my local post

		$s = parse_url($source_url);
		$t = parse_url($target_url);

		if ($s && $t && $id) {
			$this->queue_item($source_url, $target_url, $type, $id);
		}
	}

	public function process_mention_queue($count = 10)
	{
		$result = 0;
		$i = 0;

		while($i<$count) {

			$process_result = $this->process_next_queued_mention();

			if ($process_result === 0) {
				return $result;
			}

			$result =+ $process_result;

			$i++;
		}

		return $result;
	}

	public function process_next_queued_mention()
	{
		// get the item off the queue
		$entry = $this->db->get_row('SELECT * FROM '.$this->queue_table.' ORDER BY entryCreated ASC LIMIT 1');
		if (PerchUtil::count($entry)) {
			// de-queue
			$this->db->execute('DELETE FROM '.$this->queue_table.' WHERE entryID='.$this->db->pdb((int)$entry['entryID']));

			if ($this->process_mention($entry['entrySource'], $entry['entryTarget'], $entry['entryType'], $entry['entryFK'])) {
				return 1;
			}

			// re-queue
			$this->queue_item($entry['entrySource'], $entry['entryTarget'], $entry['entryType'], $entry['entryFK']);
			return 0;
		}

		return 0;
	}

	public function receive_ping_from_form($SubmittedForm)
	{
		$source = $SubmittedForm->data['source'];
		$target = $SubmittedForm->data['target'];
		$postID = $SubmittedForm->data['pid'];

		if (($source && $target) && ($source != $target) && $postID) {
			$this->receive_notification($source, $target, 'post', $postID);
		}
	}

	private function process_mention($source_url, $target_url, $type, $id)
	{
		PerchUtil::debug('Processing webmention for '.$source_url);
		// only handles posts currently, discard anything else
		if ($type != 'post') return true;

		$source_html = $this->fetch_url($source_url);
		$content_url = $source_html;

		if ($source_html && stristr($source_html, $target_url)) {

			$Posts = new PerchBlog_Posts($this->api);
			$Post = $Posts->find($id);

			$doc = Mf2\parse($source_html, $source_url);

			$content_url = $this->_mf_find_url($doc, $source_url);

			$comment = [
				'webmention' => '1',
				'postID' => (int)$id,
			];

			$comment['commentName']          = $this->_mf_find_name($doc);
			$comment['commentEmail']         = $this->_mf_find_email($doc);
			$comment['commentDateTime']      = $this->_mf_find_date($doc);
			$comment['commentHTML']          = $this->_mf_find_html($doc);
			$comment['commentURL']           = $content_url;
			$comment['commentStatus']        = 'PENDING';
			$comment['commentDynamicFields'] = '{}';

			$comment['commentSpamData'] = PerchUtil::json_safe_encode([
				'fields'=>[
					'name'  => $comment['commentName'],
					'url'   => $comment['commentURL'],
					'email' => $comment['commentEmail'],
					'body'  => $comment['commentHTML'],
				],
				'environment'=>$_SERVER
			]);

			$type = $this->_mf_detect_type($doc);

			$comment['webmentionType'] = $type;

			
			$Comments = new PerchBlog_Comments($this->api);

			$current_status = $this->_get_status_for_mention($content_url, $target_url, $type, $id);
			
			if ($current_status) {
				$this->_remove_mention($content_url, $target_url, $type, $id);

				if ($current_status == 'LIVE') {
					$comment['commentStatus'] = 'LIVE'; // don't make us remoderate	
				}
			}
			

			$Comment = $Comments->create($comment);

			if (is_object($Comment) && $Comment->commentStatus()=='PENDING') {
				$Comments->notify_author($Post, $Comment);
			}

			return true;
		} else {
			PerchUtil::debug('Target URL does not exist in source', 'error');
			$this->_remove_mention($content_url, $target_url, $type, $id);
			return true;
		}

		return false;
	}

	private function _remove_mention($source_url, $target_url, $type, $id)
	{
		if ($type == 'post') {

			$sql = 'UPDATE '.PERCH_DB_PREFIX.'blog_comments SET commentStatus='.$this->db->pdb('REJECTED').' 
						WHERE postID='.$this->db->pdb((int)$id).'
							AND commentURL='.$this->db->pdb($source_url).' AND webmention=1';
			$this->db->execute($sql);

		}
	}

	private function _get_status_for_mention($source_url, $target_url, $type, $id)
	{
		if ($type == 'post') {

			$sql = 'SELECT commentStatus FROM '.PERCH_DB_PREFIX.'blog_comments  
						WHERE postID='.$this->db->pdb((int)$id).' AND commentURL='.$this->db->pdb($source_url).' AND webmention=1 
						ORDER BY FIELD(commentStatus, \'LIVE\',\'PENDING\',\'SPAM\',\'REJECTED\') LIMIT 1';
			return $this->db->get_value($sql);
		}

		return false;
	}

	private function queue_item($source_url, $target_url, $type, $id) 
	{

		$sql = 'SELECT COUNT(*) 
				FROM '.$this->queue_table.' 
				WHERE entrySource='.$this->db->pdb($source_url).'
					AND entryTarget='.$this->db->pdb($target_url).'
					AND entryType='.$this->db->pdb($type).'
					AND entryFK='.$this->db->pdb($id);
		$count = $this->db->get_count($sql);

		if ($count == 0) {
			$this->db->insert($this->queue_table, [
				'entryCreated' => date('Y-m-d H:i:s'),
				'entrySource'  => $source_url,
				'entryTarget'  => $target_url,
				'entryType'    => $type,
				'entryFK'	   => $id,
			]);	
		}

		
	}

	private function _mf_detect_type($doc)
	{
		$doc = $this->_mf_reduce_to_first('h-entry', $doc);
		if (isset($doc['properties'])) {
			if (isset($doc['properties']['repost-of'])) {
				return 'repost';
			}

			if (isset($doc['properties']['like-of'])) {
				return 'like';
			}

			if (isset($doc['properties']['in-reply-to'])) {
				return 'comment';
			}

		}
		return 'comment';
	}

	private function _mf_find_name($doc) 
	{
		$author = $this->_mf_reduce_to_author($doc);

		if (isset($author['properties']) && isset($author['properties']['name'])) {
			return implode(' ', $author['properties']['name']);
		}

		return 'Unknown';
	}

	private function _mf_find_email($doc) 
	{
		$author = $this->_mf_reduce_to_author($doc);

		if (isset($author['properties']) && isset($author['properties']['email'])) {
			return str_replace('mailto:', '', implode(' ', $author['properties']['email']));
		}

		return 'webmentions@localhost';
	}

	private function _mf_find_date($doc) 
	{
		$doc = $this->_mf_reduce_to_first('h-entry', $doc);

		if (isset($doc['properties']) && isset($doc['properties']['published'])) {
			return date('Y-m-d H:i:s', strtotime(implode(' ', $doc['properties']['published'])));
		}

		return date('Y-m-d H:i:s');
	}

	private function _mf_find_url($doc, $default) 
	{
		$doc = $this->_mf_reduce_to_first('h-entry', $doc);

		if (isset($doc['properties']) && isset($doc['properties']['url'])) {
			if (is_array($doc['properties']['url'])) {
				return $doc['properties']['url'][0];
			}

			return $doc['properties']['url'];
		}

		return $default;
	}

	private function _mf_find_html($doc) 
	{
		$doc = $this->_mf_reduce_to_first('h-entry', $doc);

		if (isset($doc['properties']) && isset($doc['properties']['content'])) {

			if (PerchUtil::count($doc['properties']['content'])) {
				$html = '';
				foreach($doc['properties']['content'] as $content) {
					if (isset($content['html'])) {
						$html .= $content['html'].' ';
					}
				}

				return trim($html);
			}
		}

		return '';
	}

	private function _mf_reduce_to_first($type = 'h-entry', $doc)
	{
		if (PerchUtil::count($doc) && isset($doc['items']) && PerchUtil::count($doc['items'])) {
	        foreach($doc['items'] as $item) {

	            if ($item['type'] && $item['properties']) {
	                if (in_array($type, $item['type'])) {
	                    unset($item['properties']['comment']);
	                    return $item;
	                }
	            }
	        }
	    }

	    return $doc;
	}

	private function _mf_reduce_to_author($doc)
	{
		$doc1 = $this->_mf_reduce_to_first('h-entry', $doc);
		if (isset($doc1['properties']) && isset($doc1['properties']['author'])) {
			$author = $doc1['properties']['author'][0];
		} else {
			$author = $this->_mf_reduce_to_first('h-card', $doc);
		}

		return $author;
	}


	private function fetch_url($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Perch CMS (webmention.org)');
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}

}