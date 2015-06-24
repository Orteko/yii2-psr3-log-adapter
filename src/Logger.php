<?php

namespace Orteko\PSR3LogAdapter;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use yii\log\Logger as YiiLogger;

use Yii;

/**
 * Yii2 logger adapter that can be injected into libraries expecting a PSR3
 * compatible logger.
 *
 * Maps PSR3 log levels to the closest equivalent in Yii2.
 */
class Logger extends AbstractLogger
{
    /**
     * @var string Yii category for the logged messages.
     */
    protected $category;

    /**
     * @var bool Interpolate log context values with message placeholders by
     * default.
     */
    protected $interpolate;

    protected $logLevelMap = [
        LogLevel::EMERGENCY => YiiLogger::LEVEL_ERROR,
        LogLevel::ALERT => YiiLogger::LEVEL_ERROR,
        LogLevel::CRITICAL => YiiLogger::LEVEL_ERROR,
        LogLevel::ERROR => YiiLogger::LEVEL_ERROR,
        LogLevel::WARNING => YiiLogger::LEVEL_WARNING,
        LogLevel::NOTICE => YiiLogger::LEVEL_INFO,
        LogLevel::INFO => YiiLogger::LEVEL_INFO,
        LogLevel::DEBUG => YiiLogger::LEVEL_TRACE,
    ];

    /**
     * @param string $category
     * @param boolean $interpolate
     */
    public function __construct($category = 'psr3-log-adapter', $interpolate = true)
    {
        $this->category = $category;
        $this->interpolate = $interpolate;
    }

    /**
     * Log a message, transforming from PSR3 log levels to the closest Yii2
     * equivalent.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = array())
    {
        if ($this->interpolate) {
            $message = $this->interpolate($message, $context);
        }

        Yii::getLogger()->log($message, $this->logLevelMap[$level], $this->category);
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param string $message
     * @param array $context
     */
    protected function interpolate($message, array $context = array())
    {
        $replace = array();

        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        return strtr($message, $replace);
    }
}
