<?php

namespace Logger;

/**
 * Factory for FileLogger
 *
 * @version    0.6
 * @package    Logger
 *
 * @author     Matěj Humpál <finwe@finwe.info>
 * @copyright  Copyright (c) 2011 Matěj Humpál
 */
class FileLoggerFactory extends \Nette\Object implements \Logger\ILoggerFactory
{
	/**
	 * @param array $options
	 * @return Logger\FileLogger
	 */
	public function factory($options = array())
	{
		return new FileLogger($options);
	}
}
