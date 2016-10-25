<?php
/**
*
* @package phpBB Extension - Unique Visits Counter
* @copyright (c) 2016 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\counter\migrations;

use phpbb\db\migration\migration;

class counter_1_0_2 extends migration
{
	static public function depends_on()
	{
		return array(
			'\dmzx\counter\migrations\counter_schema',
		);
	}

	public function update_data()
	{
		return array(
			// Add config
			array('config.add', array('counter_version', '1.0.2')),
			array('config.add',	array('visits_counter_gc', 86400)),
			array('config.add',	array('visits_counter_last_gc', 0, 1)),

			// ACP module
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_VISITS_COUNTER_TITLE'
			)),
			array('module.add', array(
				'acp',
				'ACP_VISITS_COUNTER_TITLE',
				array(
					'module_basename'	=> '\dmzx\counter\acp\acp_counter_module',
				),
			)),
		);
	}
}
