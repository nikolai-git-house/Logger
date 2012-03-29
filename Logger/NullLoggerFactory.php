<?php

namespace Logger;

/**
 * Factory for NullLogger
 *
 * @version    0.6
 * @package    Logger
 *
 * @author     Matěj Humpál <finwe@finwe.info>
 * @copyright  Copyright (c) 2011 Matěj Humpál
 */
class NullLoggerFactory implements \Logger\ILoggerFactory
{
	/**
	 * @param array $options
	 * @return Logger\NullLogger
	 */
	public function factory($options = array())
	{
		return new NullLogger($options);
	}
}
