<?php

namespace Yjtec\AmqpLink;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Produce
{

    public function setConfig($config = null)
    {
        if (empty($config)) {
            $config = config('amqp.produce_host');
        }
        $connection = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password']
        );
        return $connection;
    }

    /**
     * 生产消息到消息队列
     * @param $key
     * @param $content
     * @param string $exchange_name
     * @param string $type
     * @throws \Exception
     */
    public function produceExchanges($key, $content, $queue_config = 'default', $config = null)
    {
        $connection = $this->setConfig($config);
        $channel = $connection->channel();

        $channel->exchange_declare(
            config("amqp.{$queue_config}.exchange_name"),
            config("amqp.{$queue_config}.type"),
            config("amqp.{$queue_config}.passive", false),
            config("amqp.{$queue_config}.durable", true),
            config("amqp.{$queue_config}.auto_delete", false)
        );

        $msg = new AMQPMessage($content);

        $channel->basic_publish(
            $msg,
            config("amqp.{$queue_config}.exchange_name"),
            $key
        );
        $channel->close();
        $connection->close();
    }
}