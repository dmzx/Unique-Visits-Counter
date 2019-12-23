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

class counter_1_0_4 extends migration
{
	static public function depends_on()
	{
		return array(
			'\dmzx\counter\migrations\counter_1_0_3',
		);
	}

	public function update_data()
	{
		return array(
			array('config.update', array('counter_version', '1.0.4')),
		);
	}

	public function update_schema()
	{
		return array(
			'change_columns'	=> array(
				$this->table_prefix . 'visits_counter'	=> array(
					'uvc_ip'		=> array('VCHAR:40', ''),
				),
			),
		);
	}
}
