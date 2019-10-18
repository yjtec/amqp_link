<?php


namespace Yjtec\AmqpLink;


use PhpAmqpLib\Connection\AMQPStreamConnection;

class Consume
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
    public function consumeQueueTopic(
        $callback, $key, $queue_name = 'default',  $queue_config = 'default', $config = null
    )
    {
        $connection = $this->setConfig($config);
        $channel = $connection->channel();

        $channel->queue_declare(
            $queue_name,
            'topic',
            config("amqp.{$queue_config}.passive", false),
            config("amqp.{$queue_config}.durable", true),
            config("amqp.{$queue_config}.auto_delete", false)
        );
        if(is_array($key)){
            foreach ($key as $value){
                $channel->queue_bind($queue_name, config("amqp.{$queue_config}.exchange_name"), $value);
            }
        }else{
            $channel->queue_bind($queue_name, config("amqp.{$queue_config}.exchange_name"), $key);
        }

        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
    }
}