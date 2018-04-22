<?php

namespace HudhaifaS\Inbox\View;

use HudhaifaS\DOM\DataObjectPage;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Mar 3, 2018 - 5:12:54 PM
 */
class InboxPage
        extends DataObjectPage {

    private static $table_name = 'InboxPage';

    public function canCreate($member = null, $context = array()) {
//        if (!$member || !(is_a($member, Member::class)) || is_numeric($member)) {
//            $member = Member::currentUserID();
//        }
//
//        return (DataObject::get($this->owner->class)->count() > 0) ? false : true;
        return true;
    }

}
