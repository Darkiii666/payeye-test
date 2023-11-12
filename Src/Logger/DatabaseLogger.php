<?php

namespace Payeye\Woocommerce\Logger;

use Payeye\Woocommerce\Contracts\LoggerInterface;
use Payeye\Woocommerce\Database\LogsDatabase;
use Payeye\Woocommerce\Exceptions\InvalidLogLevelArgument;
use ReflectionClass;


class DatabaseLogger implements LoggerInterface
{
    public function emergency(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::ALERT, $message, $context);
    }

    public function critical(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::CRITICAL, $message, $context);
    }

    public function error(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::ERROR, $message, $context);
    }

    public function warning(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::WARNING, $message, $context);
    }

    public function notice(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::NOTICE, $message, $context);
    }

    public function info(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::INFO, $message, $context);
    }

    public function debug(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::DEBUG, $message, $context);
    }

    public function log(string $level, string $message, array $context = [])
    {
        $logLevel = new ReflectionClass(LogLevel::class);
        $allowedLogLevels = $logLevel->getConstants();
        if (in_array($level, $allowedLogLevels)) {
            throw new InvalidLogLevelArgument('Invalid log level: ' . $level);
        }
        $this->addRecord($level, $message, $context);
    }

    private function addRecord(string $level, string $message, array $context)
    {
        $database = new LogsDatabase();

        $defaultData = [
            'orderId' => 0,
            'status' => '',
            'message' => $message
        ];
        $data = wp_parse_args($context, $defaultData);
        $additionalInfo = $data;
        unset($additionalInfo['orderId']);
        unset($additionalInfo['status']);
        $database->insertRecord($data['orderId'], $data['status'], json_encode($additionalInfo));
    }
}