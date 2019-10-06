<?php

namespace Yjtec\AmqpLink;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Produce
{
    private $connection;

    public function __construct($config = null)
    {
        if (empty($config)) {
            $config = config('amqp.produce_host');
        }
        $this->connection = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password']
        );
    }

    /**
     * 生产消息到消息队列
     * @param $key
     * @param $content
     * @param string $exchange_name
     * @param string $type
     * @throws \Exception
     */
    public function produceExchanges($key, $content, $queue_config = 'default')
    {
        $channel = $this->connection->channel();

        list($exchange_name, $type) = config("amqp.{$queue_config}");

        $channel->exchange_declare(
            $exchange_name,
            $type,
            config("amqp.{$queue_config}.passive", false),
            config("amqp.{$queue_config}.durable", true),
            config("amqp.{$queue_config}.auto_delete", false)
        );

        $msg = new AMQPMessage($content);

        $channel->basic_publish(
            $msg,
            $exchange_name,
            $key
        );
        $channel->close();
        $this->connection->close();
    }
}