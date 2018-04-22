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

use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Mar 3, 2018 - 2:32:23 PM
 */
class InboxMessage
        extends DataObject
        implements ManageableDataObject {

    private static $db = [
        'Title' => "Varchar(255)",
        'Content' => "HTMLText",
        'IsRead' => "Boolean",
        'IsNotified' => "Boolean",
    ];
    private static $has_one = [
        'Receiver' => Member::class,
    ];
    private static $defaults = [
        "IsRead" => 0,
    ];
    private static $summary_fields = [
        'Title',
        'Receiver.Title',
        'MessageSummary',
        'IsRead',
        'Created',
    ];

    public function canView($member = false) {
        if (!$member) {
            $member = Member::currentUserID();
        }

        if ($member && is_numeric($member)) {
            $member = DataObject::get_by_id(Member::class, $member);
        }

        if ($this->canEdit($member)) {
            return true;
        }

        if ($member && $this->hasMethod('CreatedBy') && $member == $this->CreatedBy()) {
            return true;
        }

        return $member && $member->ID == $this->ReceiverID;
    }

    public function canEdit($member = false) {
        if (!$member) {
            $member = Member::currentUserID();
        }

        if ($member && is_numeric($member)) {
            $member = DataObject::get_by_id(Member::class, $member);
        }

        if ($member && Permission::checkMember($member, "ADMIN")) {
            return true;
        }

        return false;
    }

    public function canCreate($member = null, $context = array()) {
        if (!$member) {
            $member = Member::currentUserID();
        }

        if ($member && is_numeric($member)) {
            $member = DataObject::get_by_id(Member::class, $member);
        }

        if ($member && Permission::checkMember($member, "ADMIN")) {
            return true;
        }

        return false;
    }

    protected function onAfterWrite() {
        if (!$this->IsNotified) {
            $email = InboxNotification::create($this);
            $email->send();

            $this->IsNotified = 1;
            $this->write();
        }

        parent::onAfterWrite();
    }

    function Link($action = null) {
        $page = InboxPage::get()->first();

        return $page ? $page->Link($action) : null;
    }

    public function canPublicView() {
        return $this->canView();
    }

    public function getMessageSummary() {
        $content = $this->dbObject('Content');
        $value = str_replace('<br>', '__br__', $content->getValue());
        $content->setValue($value);
        return str_replace('__br__', '<br>', $content->Summary());
    }

    public function getObjectDefaultImage() {
        
    }

    public function getObjectEditLink() {
        
    }

    public function getObjectEditableImageName() {
        
    }

    public function getObjectImage() {
        return null;
    }

    public function getObjectItem() {
        return $this->renderWith('Includes/Message_Item');
    }

    public function getObjectLink() {
        return $this->Link("show/$this->ID");
    }

    public function getObjectNav() {
        return $this->renderWith('Includes/Message_Nav');
    }

    public function getObjectRelated() {
        return null;
    }

    public function getObjectSummary() {
        return null;
    }

    public function getObjectTabs() {
        $lists = [];

        if ($this->Content) {
            $lists[] = [
                'Title' => _t('Archives.CONTENT', 'Content'),
                'PlainContent' => 1,
                'Content' => $this->renderWith('Includes/Message')
            ];
        }

        $this->extend('extraTabs', $lists);

        return new ArrayList($lists);
    }

    public function getObjectTitle() {
        return $this->Title;
    }

}
