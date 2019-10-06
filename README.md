# Laravel 消息队列

## 安装

```php
composer require yjtec/amqp_link

php artisan vendor:publis --provider="Yjtec\AmqpLink\ProduceTopicServiceProvider"

```

## 使用

### 生产者

```php
 app('produce')->produceExchanges('key', 'content');
```

### 消费者

```php
$callback = function ($msg) {
    echo $msg->body;
};

app('consume')->consumeQueueTopic($callback, 'key', 'queue_name');
```