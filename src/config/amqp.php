<?php
return [
    'produce_host' => [
        'host' => '192.168.1.66',
        'port' => 5672,
        'user' => 'test',
        'password' => 'test',
    ],
    'default' => [
        'exchange_name' => 'test_topic_logs',//exchange_name
        'type' => 'topic',//type
        'queue_name' => 'test_topic_logs',//queue_name
        'vhost' => '/',
        'passive' => 'false',
        'durable' => 'false',
        'auto_delete' => 'false',
        'internal' => 'false',
    ]
];