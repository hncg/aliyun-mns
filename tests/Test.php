<?php
namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use AliyunMNS\Client;
use AliyunMNS\Model\BatchSmsAttributes;
use AliyunMNS\Model\MessageAttributes;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\PublishMessageRequest;

class PublishBatchSMSMessageDemo
{
    protected $endPoint;
    protected $accessId;
    protected $accessKey;
    protected $client;

    public function run()
    {
        /**
         * Step 1. 初始化Client
         */
        $this->endPoint = "";
        $this->accessId = "";
        $this->accessKey = "";
        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
        /**
         * Step 2. 获取主题引用
         */
        $topicName = "sms.topic-cn-shanghai";

        /** @var \AliyunMNS\Topic $topic */
        $topic = $this->client->getTopicRef($topicName);
        /**
         * Step 3. 生成SMS消息属性
         */
        // 3.1 设置发送短信的签名（SMSSignName）和模板（SMSTemplateCode）
        $batchSmsAttributes = new BatchSmsAttributes("", "");
        // 3.2 （如果在短信模板中定义了参数）指定短信模板中对应参数的值
        $batchSmsAttributes->addReceiver("", array("code" => "1233", "product" => "cgg"));
        $messageAttributes = new MessageAttributes(array($batchSmsAttributes));
        /**
         * Step 4. 设置SMS消息体（必须）
         *
         * 注：目前暂时不支持消息内容为空，需要指定消息内容，不为空即可。
         */
        $messageBody = "smsmessage";
        /**
         * Step 5. 发布SMS消息
         */
        $request = new PublishMessageRequest($messageBody, $messageAttributes);
        try {
            /** @var \AliyunMNS\Responses\SendMessageResponse $res */
            $res = $topic->publishMessage($request);
            echo $res->isSucceed();
            echo "\n";
            echo $res->getMessageId();
            echo "\n";
        } catch (MnsException $e) {
            echo $e;
            echo "\n";
        }
    }
}
$instance = new PublishBatchSMSMessageDemo();
$instance->run();
