<?php
namespace truenorthmortgage\postmark;

/**
 * Email Utilities
 */
class Utilities
{
    /**
     * Validates an email message object
     * @param  mixed  $email_package    Object implementing [[\yii\mail\MessageInterface]]
     * @return boolean                  Whether object is a valid email message
     */
    public static function isValid($email_package)
    {
        extract($email_package);

        if (empty($to)) {
            return '$to address not supplied. Other Data: ' .  print_r(['from' => $from], true);
        }

        if (empty($from)) {
            return '$from address not supplied. Other Data: ' .  print_r(['to' => $to], true);
        }

        if (empty($html_body) && empty($text_body)) {
            return 'Both $html_body and $text_body where empty. Other Data: ' .  print_r(['to' => $to, 'from' => $from], true);
        }

        return true;
    }

    /**
     * Prepare a string of recipients to be used as `$to`, `$cc`, and `$bcc` array properties
     * @param  array $recipients   array formated list of `to`, `cc`, and `bcc` addresses. Example
     * ```
     * [
     *     'to' => [
     *         'toaddress1@email.com',
     *         'toaddress2@email.com'
     *     ],
     *     'cc' => [
     *         'ccaddress1@email.com'
     *     ],
     *     'bcc' =>[
     *         'bccaddress1@email.com'
     *     ]
     * ]
     * ```
     * @return array                Array containing to, cc, and bcc results in the format
     * ```
     * [
     *     [
     *         'toaddress1@email.com, toaddress2@email.com'
     *     ],
     *     [
     *         'ccaddress1@email.com'
     *     ],
     *     [
     *         'bccaddress1@email.com'
     *     ]
     * ]
     * ```
     */
    public static function prepareRecipients($recipients)
    {
        extract($recipients);

        if (is_array($to)) {
            $to = implode(', ', $to);
        }

        if (is_array($cc)) {
            $cc = implode(', ', $cc);
        }

        if (is_array($bcc)) {
            $bcc = implode(', ', $bcc);
        }

        return [$to, $cc, $bcc];
    }
}
