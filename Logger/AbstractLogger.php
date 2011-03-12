<?php

namespace Logger;

use Nette\Reflection\ClassReflection;
use Nette\Environment;

use InvalidArgumentException;

/**
 * Abstract Logger class offering base logging functionality
 *
 * @version    0.6
 * @package    Logger
 *
 * @author     Jan Smitka <jan@smitka.org>
 * @author     Martin Pecka <martin.pecka@clevis.cz>
 * @author     Matěj Humpál <finwe@finwe.info>
 * @copyright  Copyright (c) 2009-2010 Jan Smitka
 * @copyright  Copyright (c) 2009-2010 Martin Pecka
 * @copyright  Copyright (c) 2011 Matěj Humpál
 */
abstract class AbstractLogger extends \Nette\Object implements \Logger\ILogger
{

	/**
	 * @var int|bool
	 */
	protected $minimumLogLevel;

	/**
	 * @var int
	 */
	protected $defaultLogLevel = self::INFO;

	/**
	 * @var string
	 */
	protected $dateFormat = 'c';

	/**
	 * @var string
	 */
	protected $messageTemplate = "%date% %level% %memory% ===> %message%\n";

	/**
	 * @var array
	 */
	protected $messagePlaceholders = array(
		'%date%' => '%s',
		'%level%' => '%s',
		'%memory%' => 'mem(real/peak):%0.2fMB/%0.2fMB',
		'%message%' => '%s',
	);

	/**
	 *
	 * @param <type> $options 
	 */
	public function __construct($options = array())
	{
		if (isset($options['minimumLogLevel'])) {
			$this->setMinimumLogLevel($this->parseLevel($options['minimumLogLevel']));
		} else {
			$this->setMinimumLogLevel(Environment::isProduction() ? self::INFO : self::DEBUG);
		}

		if (isset($options['defaultLogLevel'])) {
			$this->setDefaultLogLevel($this->parseLevel($options['defaultLogLevel']));
		}

		if (isset($options['dateFormat'])) {
			$this->setDateFormat($options['dateFormat']);
		}
	}

	/**
	 * @see Logger\ILogger::logMessage()
	 */
	public function logMessage($level, $message = null)
	{
		$params = call_user_func_array(array($this, 'prepareMessage'), func_get_args());
		$this->writeMessage($params['level'], $params['message']);
	}

	/**
	 * Prepares message from parameters.
	 * 
	 * If first parameter is not an integer, it is used as a message.
	 * All following parameters are used as replacements of sprintf placeholders in the message.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @return array
	 */
	protected function prepareMessage($level, $message = null)
	{
		$args = func_get_args();

		if (is_string($level)) {
			$message = $level;
			$level = $this->defaultLogLevel;
			array_shift($args);
		} else {
			if ($message === NULL) {
				throw new InvalidArgumentException('The message has to be specified.');
			}
			array_shift($args); // Remove level
			array_shift($args); // Remove message
		}

		if (!empty($args)) {
			$message = vsprintf($message, $args);
		}

		$sprintf = str_replace(array_keys($this->messagePlaceholders), $this->messagePlaceholders, $this->messageTemplate);
		$message = sprintf(
			$sprintf,
			date($this->dateFormat),
			$this->logLevelToString($level),
			(memory_get_usage(TRUE) / 1000000),
			(memory_get_peak_usage() / 1000000),
			$message
		);

		return array('level' => $level, 'message' => $message);
	}

	/**
	 * Abstract method for further implementation. Does actual outputing prepared log message
	 *
	 * @param integer $level
	 * @param string $message
	 */
	abstract protected function writeMessage($level, $message);

	/**
	 * Returns the logger verbosity.
	 * @return int
	 */
	public function getMinimumLogLevel()
	{
		return $this->minimumLogLevel;
	}


	/**
	 * Sets the logger verbosity. FALSE disables the logger.
	 * @param int $level one of the priority constants
	 * @throws InvalidArgumentException if the given level is not one of the priority constants
	 */
	public function setMinimumLogLevel($level)
	{
		if ($level !== FALSE && ($level > self::DEBUG || $level < self::EMERGENCY)) {
			throw new InvalidArgumentException('Log level must be one of the priority constants.');
		}
		$this->minimumLogLevel = $level;
	}


	/**
	 * Gets the current default level of logged messages.
	 * @return int currently set default level
	 */
	public function getDefaultLogLevel()
	{
		return $this->defaultLogLevel;
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
	 * Returns the date format used inside log files.
	 * @return string
	 */
	public function getDateFormat()
	{
		return $this->dateFormat;
	}


	/**
	 * Sets the date format used inside log files.
	 * Format is the same as used by date() function.
	 *
	 * @param string $dateFormat
	 * @see date()
	 */
	public function setDateFormat($dateFormat)
	{
		$this->dateFormat = $dateFormat;
	}

	/**
	 *
	 * @param string|integer $level
	 * @return integer
	 */
	protected function parseLevel($level)
	{
		if (is_numeric($level))
			return (int) $level;
		else {
			$loggerInterface = 'Logger\ILogger';
			$reflection = new ClassReflection($loggerInterface);
			if ($reflection->hasConstant((string) $level))
				return $reflection->getConstant((string) $level);
			else
				throw new InvalidArgumentException('Unknown priority level: ' . $level);
		}
	}
	
	/**
	 * Translate log severity level into a human-readable string.
	 * @param int $level one of priority constants
	 * @throws InvalidArgumentException if the level is unknown
	 */
	protected function logLevelToString($level)
	{
		switch ($level) {
			case self::EMERGENCY:
				return 'EMERGENCY';

			case self::ALERT:
				return 'ALERT';

			case self::CRITICAL:
				return 'CRITICAL';

			case self::ERROR:
				return 'ERROR';

			case self::WARNING:
				return 'WARNING';

			case self::NOTICE:
				return 'NOTICE';

			case self::INFO:
				return 'INFO';

			case self::DEBUG:
				return 'DEBUG';

			default:
				throw new InvalidArgumentException('Unknown priority level');
		}
	}
}