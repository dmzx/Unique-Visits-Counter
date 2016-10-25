<?php
/**
*
* @package phpBB Extension - Unique Visits Counter
* @copyright (c) 2016 dmzx - http://www.dmzx-web.net
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
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* The database table
	*
	* @var string
	*/
	protected $visits_counter_table;

	/**
	* Constructor
	* @param \phpbb\config\config				$config
	* @param \phpbb\template\template			$template
	* @param \phpbb\user						$user
	* @param \phpbb\db\driver\driver_interface	$db
	* @param string								$visits_counter_table
	*
	*/
	public function __construct(
		\phpbb\config\config $config, 
		\phpbb\template\template $template, 
		\phpbb\user $user, 
		\phpbb\db\driver\driver_interface $db, 
		$visits_counter_table)
	{
		$this->config 				= $config;
		$this->template				= $template;
		$this->user 				= $user;
		$this->db 					= $db;
		$this->visits_counter_table = $visits_counter_table;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'					=> 'load_language_on_setup',
			'core.index_modify_page_title'		=> 'index_modify_page_title',
			'core.page_header'					=> 'add_page_header_links',
		);
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'dmzx/counter',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function index_modify_page_title($event)
	{
		if (!empty($this->config['allow_visits_counter']))
		{
			$this->template->assign_vars(array(
				'S_VISITS_COUNTER'		=> true,
			));
		}
	}

	public function add_page_header_links($event)
	{
		if (!empty($this->config['allow_visits_counter']))
		{
			$sql = 'SELECT COUNT(uvc_ip) AS visits_counter
				FROM ' . $this->visits_counter_table . '
				WHERE ' . $this->db->sql_in_set('uvc_ip', $this->user->ip);
			$result = $this->db->sql_query($sql);
			$visits_counter = (int) $this->db->sql_fetchfield('visits_counter');
			$this->db->sql_freeresult($result);

			if ($visits_counter == 0)
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
				$sql = 'UPDATE ' . $this->visits_counter_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
					WHERE ' . $this->db->sql_in_set('uvc_ip', $this->user->ip);
				$this->db->sql_query($sql);
			}

			$sql = 'SELECT COUNT(uvc_ip) AS counter
				FROM ' . $this->visits_counter_table;
			$result = $this->db->sql_query($sql, 60);
			$counter = (int) $this->db->sql_fetchfield('counter');
			$this->db->sql_freeresult($result);

			$this->template->assign_vars(array(
				'UNIQUE_VISITS_COUNTER'					=> $this->user->lang('UNIQUE_VISITS_COUNTER', $counter),
				'UNIQUE_VISITS_COUNTER_HOUR'			=> $this->user->lang('UNIQUE_VISITS_COUNTER_HOUR', $this->config['visits_counter_gc'] / 3600),
			));
		}
	}
}
