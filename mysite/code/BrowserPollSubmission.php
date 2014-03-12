<?php

class BrowserPollSubmission extends DataObject {
    static $db = array(
        'Name'      => 'Text',
        'Email'     => 'Text',
        'Browser'   => 'Text',
        'Reason'    => 'Text',
    );
}
