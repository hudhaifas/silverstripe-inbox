<?php

namespace HudhaifaS\Inbox\View;

use DataObjectPageController;
use SilverStripe\Security\Member;
use SilverStripe\View\Requirements;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Mar 3, 2018 - 5:12:54 PM
 */
class InboxPageController
        extends DataObjectPageController {

    private static $allowed_actions = [
        'show',
    ];
    private static $url_handlers = [
        'show/$ID' => 'show',
    ];

    public function init() {
        parent::init();

        Requirements::css("hudhaifas/silverstripe-inbox: res/css/inbox.css");
        if ($this->isRTL()) {
            Requirements::css("hudhaifas/silverstripe-inbox: res/css/inbox-rtl.css");
        }
    }

    public function show() {
        $single = $this->getSingle();
        $single->IsRead = 1;
//        $single->write();

        return $this->showSingle($single);
    }

    public function getObjectsList() {
//        return DataObject::get('InboxMessage');
        $member = Member::currentUser();

        return $member ? $member->Received() : null;
    }

    public function isSearchable() {
        return false;
    }

    public function searchObjects($list, $keywords) {
        return $list ? $list->filterAny([
                    'Title:PartialMatch' => $keywords,
                    'Content:PartialMatch' => $keywords
                ]) : null;
    }

    protected function IsVerticalList() {
        return true;
    }

    public function ExtraClasses() {
        return 'inbox-page';
    }

}
