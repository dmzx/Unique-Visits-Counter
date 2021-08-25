<?php
/**
*
* @package phpBB Extension - Unique Visits Counter
* @copyright (c) 2016 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\counter\migrations;

use phpbb\db\migration\migration;

class counter_schema extends migration
{
	public function update_data()
	{
		return [
			// Add configs
			['config.add', ['allow_visits_counter', true]],
			['config.add', ['visits_counter', 0, true]],
		];
	}

	public function update_schema()
	{
		return [
			'add_tables'	=> [
				$this->table_prefix . 'visits_counter'	=> [
					'COLUMNS'	=> [
						'uvc_ip'		=> ['VCHAR:15', ''],
						'uvc_timestamp'	=> ['INT:30', 0],
					],
					'KEYS'			=> [
						'uvc_ip'	=> ['INDEX', 'uvc_ip'],
					],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_tables'	=> [
				$this->table_prefix . 'visits_counter',
			],
		];
	}
}
