<?php

namespace HudhaifaS\Inbox;

use HudhaifaS\Inbox\InboxPage;
use SilverStripe\ORM\DataExtension;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Mar 3, 2018 - 2:14:32 PM
 */
class MemberInboxExtension
        extends DataExtension {

    private static $has_many = [
        'Received' => 'InboxMessage.Receiver',
    ];

    public function getUnreadMessages() {
        return $this->owner->Received()->filter([
                    'IsRead' => 0
        ]);
    }

    function getInboxLink($action = null) {
        $page = InboxPage::get()->first();

        return $page ? $page->Link($action) : null;
    }

}
