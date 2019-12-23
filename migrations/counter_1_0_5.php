<?php
/**
*
* @package phpBB Extension - Unique Visits Counter
* @copyright (c) 2019 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\counter\migrations;

use phpbb\db\migration\migration;

class counter_1_0_5 extends migration
{
	static public function depends_on()
	{
		return [
			'\dmzx\counter\migrations\counter_1_0_4',
		];
	}

	public function update_data()
	{
		return [
			['config.update', ['counter_version', '1.0.5']],
		];
	}
}
