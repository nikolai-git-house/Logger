<?php

namespace Logger;

/**
 * Factory for NullLogger
 *
 * @version    0.7
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
	public function factory(array $options = array())
	{
		return new NullLogger($options);
	}
}
