<?php

/*
 * MIT License
 *  
 * Copyright (c) 2018 Hudhaifa Shatnawi
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

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
        $single->write();

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
