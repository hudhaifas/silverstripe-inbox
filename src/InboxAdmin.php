<?php

namespace HudhaifaS\Inbox;

use SilverStripe\Admin\ModelAdmin;

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Mar 3, 2018 - 5:24:32 PM
 */
class InboxAdmin
        extends ModelAdmin {

    private static $managed_models = [
        'InboxMessage',
    ];
    private static $url_segment = 'inbox';
    private static $menu_title = "Inbox";
    private static $menu_icon = "hudhaifas/silverstripe-inbox: res/images/icn-inbox.png";
    public $showImportForm = false;

}
