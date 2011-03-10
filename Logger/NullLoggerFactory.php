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
class NullLoggerFactory extends \Nette\Object
{
	/**
	 * @param array $options
	 * @return Logger\NullLogger
	 */
	public static function factory($options = array())
	{
		return new NullLogger($options);
	}
}