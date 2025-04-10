<?php
namespace Ulula\Tracking\Logger;

use Monolog\Logger as MonologLogger;

class Logger extends \Magento\Framework\Logger\Monolog
{
    /**
     * @param string $name
     * @param array $handlers
     * @param array $processors
     */
    public function __construct(
        $name = 'Ulula_Tracking',
        array $handlers = [],
        array $processors = []
    ) {
        parent::__construct($name, $handlers, $processors);
    }
} 