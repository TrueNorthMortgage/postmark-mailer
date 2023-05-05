<?php
namespace truenorthmortgage\postmark;

use Postmark\PostmarkClient;
use yii\base\InvalidConfigException;

/**
 * MailerInterface for Postmark
 *
 * @see https://postmarkapp.com
 */
class PostmarkMailer extends \yii\mail\BaseMailer
{
    /** @inheritDoc */
    public $messageClass = PostmarkMessage::class; // relative class name is intended here

    /** @var string The Postmark server API Token */
    public $postmark_api_key = null;

    /**
     * Send the message via Postmark
     *
     * @param \truenorthmortgage/postmark-mailer\PostmarkMessage $message Message to send
     */
    public function sendMessage($message)
    {
        if ($this->postmark_api_key === null) {
            throw new InvalidConfigException('Server Token is missing');
        }

        $client = new PostmarkClient($this->postmark_api_key);

        // Don't Try/Catch here, let the application do it, so it can handle retries etc
        $send_result = $client->sendEmail(
            $message->getFrom(),
            $message->getTo(),
            $message->getSubject(),
            $message->getHtmlBody(),
            $message->getTextBody(),
            $message->getTag(),
            true, // track opens
            $message->getReplyTo(),
            $message->getCc(),
            $message->getBcc(),
            $message->getHeaders(),
            $message->getAttachments()
        );

        return $send_result;
    }
}
