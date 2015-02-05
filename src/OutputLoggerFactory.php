<?php

namespace Logger;

/**
 * Factory for OutputLogger
 *
 * @version    0.7
 * @package    Logger
 *
 * @author     Matěj Humpál <finwe@finwe.info>
 * @copyright  Copyright (c) 2011 Matěj Humpál
 */
class OutputLoggerFactory implements \Logger\ILoggerFactory
{
	private $options;

	public function __construct(array $options = array())
	{
		$this->options = $options;
	}

	/**
	 * @param array $options
	 * @return Logger\OutputLogger
	 */
	public function factory(array $options = array())
	{
		return new OutputLogger(array_merge($this->options, $options));
	}
}
