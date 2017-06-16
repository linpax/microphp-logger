<?php
/**
 * @link https://github.com/linpax/microphp-logger
 * @copyright Copyright &copy; 2017 Oleg Lunegov
 * @license https://github.com/linpax/microphp-logger/blob/master/LICENSE
 */

namespace Micro\Logger;

use Psr\Log\AbstractLogger;


class Logger extends AbstractLogger
{
    /** @var AdapterInterface[] $adapters */
    private $adapters;


    /**
     * Export loggers
     *
     * @access public
     *
     * @param array $adapters
     *
     * @result void
     */
    public function __construct(array $adapters = [])
    {
        foreach ($adapters AS $name => $log) {
            if (empty($log['class']) || !class_exists($log['class'])) {
                continue;
            }

            $this->adapters[$name] = new $log['class']($log);
        }
    }

    /**
     * Send log
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = array())
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter->isSupportedLevel($level)) {
                $adapter->log($level, $message, $context);
            }
        }
    }
}