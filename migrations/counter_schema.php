<?php
/**
*
* @package phpBB Extension - Unique Visits Counter
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\counter\migrations;

class counter_schema extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('allow_visits_counter', true)),
			array('config.add', array('visits_counter', 0, true)),
		);
	}

	public function update_schema()
	{
		return array(
			'add_tables'	=> array(
				$this->table_prefix . 'visits_counter'	=> array(
					'COLUMNS'	=> array(
						'uvc_ip'		=> array('VCHAR:40', ''),
						'uvc_timestamp'	=> array('INT:30', 0),
					),
					'KEYS'			=> array(
					'uvc_ip'	=> array('INDEX', 'uvc_ip'),
				),
			),
		));
	}

	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'visits_counter',
			),
		);
	}
}