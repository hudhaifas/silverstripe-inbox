<?php

namespace HudhaifaS\Inbox\Model;

use SilverStripe\Control\Email\Email;
use SilverStripe\GraphQL\Controller;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Mar 5, 2018 - 9:50:44 PM
 */
class InboxNotification
        extends Email {

    /**
     * @var InboxMessage
     */
    protected $message = null;
    private static $email_from = '';

    /**
     * @param InboxMessage $message
     */
    public function __construct($message, $isSingleton = false) {
        parent::__construct();

        $this->message = $message;

        if (!$isSingleton) {
            $this->from = $this->config()->email_from ? $this->config()->email_from : Email::config()->admin_email;
            $this->to = $message->Receiver()->Email;
            $this->subject = _t('Inbox.EMAIL_SUBJECT', '[New Message] {title}', array(
                'title' => $message->Title
            ));
            $this->body = $this->getParsedString(_t('Inbox.EMAIL_BODY', 'Empty'));
        }
    }

    /**
     * Replaces variables inside an email template according to {@link TEMPLATE_NOTE}.
     *
     * @param string $string
     * @param Member $member
     * @return string
     */
    public function getParsedString($string) {
        $absoluteBaseURL = $this->BaseURL();
        $variables = array(
            '$Receiver' => $this->message->Receiver()->FirstName,
            '$Sender' => $this->message->CreatedBy()->FirstName,
//            '$Content' => $this->message->Content,
            '$Content' => $this->message->getMessageSummary(),
            '$MessageLink' => $this->message->getObjectLink(),
            '$LostPasswordLink' => Controller::join_links(
                    $absoluteBaseURL, singleton(Security::class)->Link('lostpassword')
            )
        );

        return str_replace(array_keys($variables), array_values($variables), $string);
    }

}
