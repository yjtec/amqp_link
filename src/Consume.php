<?php


namespace Yjtec\AmqpLink;


use PhpAmqpLib\Connection\AMQPStreamConnection;

class Consume
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

    public function consumeQueueTopic($callback, $key, $queue_name = 'default',  $queue_config = 'default')
    {
        $channel = $this->connection->channel();

        $channel->queue_declare(
            $queue_name,
            'topic',
            config("amqp.{$queue_config}.passive", false),
            config("amqp.{$queue_config}.durable", true),
            config("amqp.{$queue_config}.auto_delete", false)
        );
        $channel->queue_bind($queue_name, config("amqp.{$queue_config}.exchange_name"), $key);

        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
        $channel->close();
        $this->connection->close();
    }
}