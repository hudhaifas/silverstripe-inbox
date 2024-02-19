<?php

namespace HudhaifaS\Inbox\View;

use DataObjectPage;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Mar 3, 2018 - 5:12:54 PM
 */
class InboxPage
        extends DataObjectPage {

    private static $table_name = 'InboxPage';

    public function canCreate($member = null, $context = array()) {
        if (!$member || !(is_a($member, Member::class)) || is_numeric($member)) {
            $member = Security::getCurrentUser()?->ID;
        }

        return (DataObject::get($this->ClassName)->count() > 0) ? false : true;
    }

}
