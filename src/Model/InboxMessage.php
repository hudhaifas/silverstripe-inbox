<?php

namespace HudhaifaS\Inbox\Model;

use HudhaifaS\DOM\Model\ManageableDataObject;
use HudhaifaS\Inbox\View\InboxPage;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Mar 3, 2018 - 2:32:23 PM
 */
class InboxMessage
        extends DataObject
        implements ManageableDataObject {

    private static $table_name = 'InboxMessage';
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

        if ($member && $this->hasMethod('CreatedBy') && $this->CreatedBy() && $member->ID == $this->CreatedBy()->ID) {
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
        if (!$this->IsNotified && filter_var($this->Receiver()->Email, FILTER_VALIDATE_EMAIL)) {
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
        return $this->CreatedBy()->ProfileImage();
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
                'Title' => _t('Inbox.MESSAGE', 'Message'),
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

    public function markAsRead($flag = 1) {
        $this->IsRead = $flag;

        // Finds the specific class that directly holds the given field and returns the table
        $table = DataObject::getSchema()->tableForField($this->ClassName, 'IsRead');

        if (Security::database_is_ready()) {
            DB::prepared_query(
                    sprintf('UPDATE "%s" SET "IsRead" = ? WHERE "ID" = ?', $table),
                    [
                $this->IsRead,
                $this->ID
            ]);
        }
    }

}
