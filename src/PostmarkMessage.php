<?php
namespace truenorthmortgage\postmark;

use Postmark\Models\PostmarkAttachment;
use yii\base\NotSupportedException;
use yii\mail\BaseMessage;

/**
 * Class implementing \yii\mail\MessageInterface for use with Postmark
 *
 * Adds a [[$tag]] property that can be used to tag Postmark messages
 */
class PostmarkMessage extends BaseMessage
{
    /** @var string 'From' email address(es) */
    protected $from;

    /** @var string 'To' email address(es) */
    protected $to;

    /** @var string 'Reply-to' email address(es) */
    protected $reply_to;

    /** @var string 'Cc' email address(es) */
    protected $cc;

    /** @var string 'Bcc' email address(es) */
    protected $bcc;

    /** @var string Email subject */
    protected $subject;

    /** @var string The text version of the email body */
    protected $text_body;

    /** @var string The HTML version of the email body */
    protected $html_body;

    /** @var \Postmark\Models\PostmarkAttachment[] Array of attachment objects */
    protected $attachments = [];

    /** @var string Tag to attach to the email */
    protected $tag;

    /** @var array Email headers for this message */
    protected $headers = [];

    /** @var string The email's charset */
    protected $charset = 'utf-8';

    /**
     * Return the charset of this email
     * @return string [[$charset]]
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Setting of the charset is not supported
     * @param string $charset New charset
     * @throws NotSupportedException
     */
    public function setCharset($charset)
    {
        throw new NotSupportedException();
    }

    /**
     * Get the 'from' property
     * @return string 'from' email address
     */
    public function getFrom()
    {
        return self::stringifyEmails($this->from);
    }

    /**
     * Set the 'from' property
     * @param string $from  'from' email address
     * @return self
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Get the 'to' property
     * @return string 'to' email address
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set the 'to' property
     * @param string $to 'to' email address
     * @return self
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Get the 'reply_to' property
     * @return string Formatted string of 'reply-to' email address(es)
     */
    public function getReplyTo()
    {
        return self::stringifyEmails($this->reply_to);
    }

    /**
     * Set the 'reply_to' property
     * @param string $reply_to  'reply-to' email address(es)
     * @return self
     */
    public function setReplyTo($reply_to)
    {
        $this->reply_to = $reply_to;
        return $this;
    }

    /**
     * Get the 'cc' property
     * @return string Formatted string of 'cc' email address(es)
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * Set the 'cc' property
     * @param string $cc        'cc' email address(es)
     * @return self
     */
    public function setCc($cc)
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * Get the 'bcc' property
     * @return string Formatted string of 'bcc' email address(es)
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * Set the 'bcc' property
     * @param string $bcc        'bcc' email address(es)
     * @return self
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
        return $this;
    }

    /**
     * Get the 'subject' property
     * @return string   Email subject content
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set the subject of the message
     * @param string $subject   Email subject
     * @return self
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Get the body of the message in text format
     * @return string   Message body in text format
     */
    public function getTextBody()
    {
        return $this->text_body;
    }

    /**
     * Set the body of the message in text format
     * @param string $text      Message body in text format
     * @return self
     */
    public function setTextBody($text)
    {
        $this->text_body = $text;
        return $this;
    }

    /**
     * Get the body of the message in html format
     * @return string   Message body in html format
     */
    public function getHtmlBody()
    {
        return $this->html_body;
    }

    /**
     * Set the body of the message in html format
     * @param string $html      Message body in html format
     * @return self
     */
    public function setHtmlBody($html)
    {
        $this->html_body = $html;
        return $this;
    }

    /**
     * Get the tag for this message
     * @return string   The tag for this message
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set the tag for this message
     * @param string $tag   The tag for this message
     * @return self
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * Add to the header property
     * @param string $header    Email header to set
     * @return  self
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;
        return $this;
    }

    /**
     * Get Headers
     * @return null|array   Array of headers, or null if empty
     */
    public function getHeaders()
    {
        return empty($this->headers) ? null : $this->headers;
    }

    /**
     * Get Attachments
     * @return \Postmark\Models\PostmarkAttachment[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * {@inheritDoc}
     * @see PostmarkMessage::fromFile()
     */
    public function attach($file_path, array $options = [])
    {
        $file_name = $options['fileName'] ?? basename($file_path);
        $mime_type = $options['contentType'] ?? null;

        $this->attachments[] = PostmarkAttachment::fromFile($file_path, $file_name, $mime_type);
    }

    /**
     * {@inheritDoc}
     * @see PostmarkMessage::attachPostmarkAttachment()
     */
    public function attachContent($content, array $options = [])
    {
        $file_name = $options['fileName'] ?? 'no-name';
        $mime_type = $options['contentType'] ?? null;

        $this->attachments[] = PostmarkAttachment::fromRawData($content, $file_name, $mime_type);
    }

    /**
     * Unsupported method
     * {@inheritDoc}
     */
    public function embed($fileName, array $options = [])
    {
        throw new NotSupportedException();
    }

    /**
     * Unsupported method
     * {@inheritDoc}
     */
    public function embedContent($content, array $options = [])
    {
        throw new NotSupportedException();
    }

    /**
     * Unsupported method
     * {@inheritDoc}
     */
    public function toString()
    {
        throw new NotSupportedException();
    }

    /**
     * Helper to turn an array or string of email addresses into a formatted string representation
     * @param  string|array $email_data Email addresses to stringify
     * @return string                   Formatted string of email addresses that can be accepted by Postmark
     */
    public static function stringifyEmails($email_data)
    {
        $emails = null;
        if (empty($email_data) === false) {
            if (is_array($email_data) === true) {
                foreach ($email_data as $key => $email) {
                    if (is_int($key) === true) {
                        $emails[] = $email;
                    } else {
                        if (preg_match('/[.,:]/', $email) > 0) {
                            $email = '"'. $email .'"';
                        }
                        $emails[] = $email . ' ' . '<' . $key . '>';
                    }
                }
                $emails = implode(', ', $emails);
            } elseif (is_string($email_data) === true) {
                $emails = $email_data;
            }
        }
        return $emails;
    }
}
