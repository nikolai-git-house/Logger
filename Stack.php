<?php

namespace Logger;

use Nette\Config\Config;
use Nette\Object;

/**
 * Nette logger stack. Logs message for each inserted logger
 *
 * @version    0.6
 * @package    Logger
 *
 * @author     Matěj Humpál <finwe@finwe.info>
 * @copyright  Copyright (c) 2011 Matěj Humpál
 */
class Stack extends \Nette\Object implements \Logger\ILogger
{

	/**
	 * @var array
	 */
	protected $loggers = array();

	/**
	 *
	 * @var integer
	 */
	protected $defaultLogLevel = self::INFO;

	/**
	 * @param array $loggers
	 */
	public function __construct(array $options)
	{
		if (isset($options['loggers'])) {
			$this->setLoggers($options['loggers']);
		}
	}

	/**
	 * @param \Logger\ILogger $logger
	 */
	public function addLogger(ILogger $logger)
	{
		$this->checkLoggers(array($logger));
		$this->loggers[] = $logger;
	}

	/**
	 * @param array $loggers
	 */
	public function setLoggers($loggers)
	{
		if ($loggers instanceof Config) {
			$loggers = $loggers->toArray();
		}

		$this->checkLoggers($loggers);
		$this->loggers = $loggers;
	}

	/**
	 * Returns the logger verbosity.
	 * @return int
	 */
	public function getDefaultLogLevel()
	{
		return $this->minimumLogLevel;
	}

	/**
	 * Sets the defalut level of logged messages.
	 * @param int $level one of the priority constants
	 */
	public function setDefaultLogLevel($level)
	{
		if ($level > self::DEBUG || $level < self::EMERGENCY)
			throw new InvalidArgumentException('Log level must be one of the priority constants.');
		$this->defaultLogLevel = $level;
	}

	/**
	 * @see Logger\ILogger::logMessage()
	 *
	 * Logs message for each registered logger.
	 */
	public function logMessage($level, $message = NULL)
	{
		foreach ($this->loggers as $logger) {
			call_user_func_array(array($logger, 'logMessage'), func_get_args());
		}
	}

	/**
	 * @param array of Logger\ILogger $loggers
	 */
	private function checkLoggers($loggers)
	{
		array_walk(
			$loggers,
			function($logger, $key) {
				if (false === $logger instanceof ILogger) {
					throw new \InvalidArgumentException('Stack accepts only objects implementing \Logger\ILogger interface');
				}
			}
		);
	}

}
