<?php
/**
*
* @package phpBB Extension - Unique Visits Counter
* @copyright (c) 2016 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\counter\event;

use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb\template\template;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var config */
	protected $config;

	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var driver_interface */
	protected $db;

	/**
	* The database table
	*
	* @var string
	*/
	protected $visits_counter_table;

	/**
	* Constructor
	* @param config				$config
	* @param template			$template
	* @param user				$user
	* @param driver_interface	$db
	* @param string				$visits_counter_table
	*
	*/
	public function __construct(
		config $config,
		template $template,
		user $user,
		driver_interface $db,
		$visits_counter_table
	)
	{
		$this->config 				= $config;
		$this->template				= $template;
		$this->user 				= $user;
		$this->db 					= $db;
		$this->visits_counter_table = $visits_counter_table;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.index_modify_page_title'		=> 'index_modify_page_title',
			'core.page_header'					=> 'add_page_header_links',
		];
	}

	public function index_modify_page_title($event)
	{
		if (!empty($this->config['allow_visits_counter']))
		{
			$this->template->assign_vars([
				'S_VISITS_COUNTER'		=> true,
			]);
		}
	}

	public function add_page_header_links($event)
	{
		if (!empty($this->config['allow_visits_counter']))
		{
			$this->user->add_lang_ext('dmzx/counter', 'common');

			$sql = 'SELECT COUNT(uvc_ip) AS visits_counter
				FROM ' . $this->visits_counter_table . '
				WHERE ' . $this->db->sql_in_set('uvc_ip', $this->user->ip);
			$result = $this->db->sql_query($sql);
			$visits_counter = (int) $this->db->sql_fetchfield('visits_counter');
			$this->db->sql_freeresult($result);

			if ($visits_counter == 0)
			{
				$sql_ary = [
					'uvc_ip'		=> $this->user->ip,
					'uvc_timestamp'	=> time()
				];
				$sql = 'INSERT INTO ' . $this->visits_counter_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
				$this->db->sql_query($sql);

				$this->config->increment('visits_counter', 1, true);
			}
			else
			{
				$sql_ary = [
					'uvc_timestamp'	=> time()
				];
				$sql = 'UPDATE ' . $this->visits_counter_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
					WHERE ' . $this->db->sql_in_set('uvc_ip', $this->user->ip);
				$this->db->sql_query($sql);
			}

			$sql = 'SELECT COUNT(uvc_ip) AS counter
				FROM ' . $this->visits_counter_table;
			$result = $this->db->sql_query($sql, 60);
			$counter = (int) $this->db->sql_fetchfield('counter');
			$this->db->sql_freeresult($result);

			$this->template->assign_vars([
				'UNIQUE_VISITS_COUNTER'					=> $this->user->lang('UNIQUE_VISITS_COUNTER', $counter),
				'UNIQUE_VISITS_COUNTER_HOUR'			=> $this->user->lang('UNIQUE_VISITS_COUNTER_HOUR', $this->config['visits_counter_gc'] / 3600),
			]);
		}
	}
}
