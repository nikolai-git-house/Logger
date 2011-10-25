<?php

namespace Logger;

/**
 * Factory for FileLogger
 *
 * @version    0.6
 * @package    Logger
 *
 * @author Daniel Milde <daniel@milde.cz>
 */
interface ILoggerFactory
{
	/**
	 * @param array $options
	 * @return Logger\ILogger
	 */
	public function factory($options = array());
}
