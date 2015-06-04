<?php
/**
*
* @package phpBB Extension - Unique Visits Counter
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\counter\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{

	protected $config;

	protected $template;

	protected $user;

	protected $db;

	protected $visits_counter_table;

	public function __construct( \phpbb\config\config $config,	\phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, $visits_counter_table)
	{
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
		$this->visits_counter_table = $visits_counter_table;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.index_modify_page_title'	=> 'index_modify_page_title',
			'core.page_header'	=> 'add_page_header_links',
		);
	}

	public function index_modify_page_title($event)
	{
		if (!empty($this->config['allow_visits_counter']))
		{
			$this->template->assign_vars(array(
			'S_VISITS_COUNTER'			=> true,
		));
		}
	}

	public function add_page_header_links($event)
	{

	 if (!empty($this->config['allow_visits_counter']))
	 {
		$this->user->add_lang_ext('dmzx/counter', 'common');

		$sql = 'SELECT COUNT(*) AS visits_counter
			FROM ' . $this->visits_counter_table . '
			WHERE ' . $this->db->sql_in_set('uvc_ip', $this->user->ip);
		$result = $this->db->sql_query($sql);
		$visits_counter = (int) $this->db->sql_fetchfield('visits_counter');
		$this->db->sql_freeresult($result);

		$visits = $this->config['visits_counter'];

		if($visits_counter == 0)
		{
			$sql_ary = array(
				'uvc_ip'		=> $this->user->ip,
				'uvc_timestamp'	=> time()
			);
			$sql = 'INSERT INTO ' . $this->visits_counter_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
			$this->db->sql_query($sql);

			$this->config->increment('visits_counter', 1, true);
		}
		else
		{
			$sql_ary = array(
				'uvc_timestamp'	=> time()
			);
			$sql = 'UPDATE ' . $this->visits_counter_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . ' WHERE ' . $this->db->sql_in_set('uvc_ip', $this->user->ip);
			$this->db->sql_query($sql);
		}

		$timestamp = time() - (3600 * 24);
		$sql_ary = array($timestamp);
		$sql = 'DELETE FROM ' . $this->visits_counter_table . ' WHERE uvc_timestamp < ' . $timestamp;
		$this->db->sql_query($sql);

		$sql =	'SELECT COUNT(*) AS num_del FROM ' . $this->visits_counter_table . ' ' ;
		$result = $this->db->sql_query($sql);
		$visitsok = (int) $this->db->sql_fetchfield('num_del');

		$this->template->assign_vars(array(
			'UNIQUE_VISITS_COUNTER'			=> sprintf($this->user->lang['UNIQUE_VISITS_COUNTER'], $visitsok),
		));
	 }

	}
}
