<?php
/**
*
* @package phpBB Extension - Unique Visits Counter
* @copyright (c) 2021 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\counter;

use phpbb\extension\base;

class ext extends base
{
	public function is_enableable()
	{
		$config = $this->container->get('config');

		return phpbb_version_compare($config['version'], '3.2.0', '>=');
	}
}
