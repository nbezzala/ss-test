<?php
class MyAdmin extends ModelAdmin {
    private static $managed_models = array('BrowserPollSubmission');
    private static $url_segment = "Browser Poll";
    private static $menu_title = "Browser Poll";
}
