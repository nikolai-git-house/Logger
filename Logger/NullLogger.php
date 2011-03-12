<?php

namespace Logger;

/**
 * Logger ignoring all messages
 *
 * @version    0.6
 * @package    Logger
 *
 * @author     Matěj Humpál <finwe@finwe.info>
 * @copyright  Copyright (c) 2011 Matěj Humpál
 */
class NullLogger extends \Logger\AbstractLogger
{
	/**
	 * Dummy implementation of abstract method of parent
	 *
	 * @param mixed $level
	 * @param string $message
	 */
	public function writeMessage($level, $message) {}
}