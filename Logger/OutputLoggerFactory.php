<?php

namespace Logger;

/**
 * Factory for OutputLogger
 *
 * @version    0.6
 * @package    Logger
 *
 * @author     Matěj Humpál <finwe@finwe.info>
 * @copyright  Copyright (c) 2011 Matěj Humpál
 */
class OutputLoggerFactory implements \Logger\ILoggerFactory
{
	/**
	 * @param array $options
	 * @return Logger\OutputLogger
	 */
	public function factory($options = array())
	{
		return new OutputLogger($options);
	}
}
