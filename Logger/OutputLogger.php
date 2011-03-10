<?php

namespace Logger;

/**
 * Logger writing messages to standard output
 *
 * @version    0.6
 * @package    Logger
 *
 * @author     Matěj Humpál <finwe@finwe.info>
 * @copyright  Copyright (c) 2011 Matěj Humpál
 */
class OutputLogger extends \Logger\AbstractLogger
{

	/**
	 * @see Logger\ILogger::logMessage()
	 */
	public function logMessage($level, $message = NULL)
	{

		if ($this->minimumLogLevel === FALSE)
			return;

		$args = func_get_args();

		if (is_string($level)) {
			$message = $level;
			$level = $this->defaultLogLevel;
			array_shift($args);
		} else {
			if ($message === NULL)
				throw new \InvalidArgumentException('The message has to be specified.');
			array_shift($args); array_shift($args);
		}

		if ($level > self::DEBUG || $level < self::EMERGENCY)
			throw new \InvalidArgumentException('Log level must be one of the priority constants.');

		if ($level <= $this->minimumLogLevel) {

			if (!empty($args)) {
				$message = vsprintf($message, $args);
			}

			printf("%s %s [mem(real/peak):%0.2fMB/%0.2fMB] ===> %s\r\n", date($this->dateFormat), $this->logLevelToString($level), (memory_get_usage(TRUE) / 1000000), (memory_get_peak_usage() / 1000000), $message);
			
		}
	}
}
