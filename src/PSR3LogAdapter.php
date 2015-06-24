<?php

use Psr\Log;
use yii\log\Logger;

namespace Orteko\Yii2PSR3LogAdapater;

/**
 * Yii2 logger adapater that can be injected into libraries expecting a PSR3
 * compatible logger.
 *
 * Maps PSR3 log levels to the closest equivalent in Yii2.
 */
class PSR3LogAdapter extends AbstractLogger
{
    protected $logger;

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
     * @var bool Interpolate log context values with message placeholders by
     * default.
     */
    protected $interpolate;

    /**
     * @param Logger $logger
     * @param bool $interpolate
     */
    public function __construct(Logger $logger, $interpolate = true)
    {
        $this->logger = $logger;
    }

    public function log($level, $message, array $context = array())
    {
        if ($this->interpolate) {
            $messages = $this->interpolate($message, $context);
        }

        $this->logger->log($message, $this->logLevelMap($level));
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param string $message
     * @param array $context
     */
    protected function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();

        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}
