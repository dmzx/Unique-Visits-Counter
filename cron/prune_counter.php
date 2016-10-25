<?php
/**
*
* @package phpBB Extension - Unique Visits Counter
* @copyright (c) 2016 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\counter\cron;

class prune_counter extends \phpbb\cron\task\base
{
	/** @var \phpbb\config\config */
	protected $config;

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
	 *
	 * @param \phpbb\config\config				 			$config
	 * @param \phpbb\db\driver\driver_interface				$db
	 * @param string										$visits_counter_table
	 *
	 */
	public function __construct(
		\phpbb\config\config $config, 
		\phpbb\db\driver\driver_interface $db, 
		$visits_counter_table)
	{
		$this->config				= $config;
		$this->db 					= $db;
		$this->visits_counter_table = $visits_counter_table;
	}

	/**
	 * Runs this cron task.
	 *
	 * @return null
	 */
	public function run()
	{
		$timestamp = time() - $this->config['visits_counter_gc'];
		$sql_ary = array($timestamp);
		$sql = 'DELETE FROM ' . $this->visits_counter_table . '
			WHERE uvc_timestamp < ' . $timestamp;
		$this->db->sql_query($sql);

		$this->config->set('visits_counter_last_gc', time());
	}

	/**
	 * Returns whether this cron task can run, given current board configuration.
	 *
	 * If warnings are set to never expire, this cron task will not run.
	 *
	 * @return bool
	 */
	public function is_runnable()
	{
		return $this->config['allow_visits_counter'];
	}

	/**
	 * Returns whether this cron task should run now, because enough time
	 * has passed since it was last run (24 hours).
	 *
	 * @return bool
	 */
	public function should_run()
	{
		return $this->config['visits_counter_last_gc'] < time() - $this->config['visits_counter_gc'];
	}
}
