<?php

class BrowserPollPage extends Page {

}

class BrowserPollPage_Controller extends Page_Controller {
    static $allowed_actions  = array('BrowserPollForm', 'BrowserPollResults');

    public function BrowserPollForm() {
        if (Session::get('BrowserPollVoted')) return false;

        $fields = new FieldList(
            new TextField('Name', 'Your Name'),
            new EmailField('Email', 'Your Email'),
            new OptionsetField('Browser', 'Your Favourite Browser', array(
                    'Firefox' => 'Firefox',
                    'Chrome' => 'Chrome',
                    'Internet Explorer' => 'Internet Explorer',
                    'Opera' => 'Opera',
                    'Safari' => 'Safari',
                    'Konqueror' => 'Konqueror',
                    'Lynx' => 'Lynx'
                )
            ),
            new TextareaField('Reason')
        );

        $actions = new FieldList(
            new FormAction('doBrowserPoll', 'Submit')
        );

        $validator = new RequiredFields('Name', 'Email', 'Browser');

        return new Form($this, 'BrowserPollForm', $fields, $actions, $validator);
    }

    public function doBrowserPoll($data, $form) {

        $submission = new BrowserPollSubmission;
        $form->saveInto($submission);

        $row = BrowserPollSubmission::get()->filter(array(
            'Email' => $form->FieldMap()->Email()->value
        ))->First();
        
        if ( isset($row) ) {
            $submission->ID = $row->ID;
        }

        // If ID is set, it will update instead of insert
        $submission->write();
        Session::set('BrowserPollVoted', true);
        $this->sendEmail($submission);
        return $this->redirectBack();
    }

    public function BrowserPollResults() {
        $submissions = new GroupedList(BrowserPollSubmission::get());
        $total = $submissions->Count();

        $list = new ArrayList();

        foreach ($submissions->groupBy('Browser') as $browserName =>
$browserSubmissions ) {
            $count = $browserSubmissions->Count();
            $percentage = (int) ($count / $total * 100);
            $list->push( new ArrayData(array(
                'Browser'       => $browserName,
                'Count'         => $count,
                'Percentage'    => $percentage
            )));
        }

        return $list;
    }

    public function BrowserPollResultsTable() {
        $submissions = BrowserPollSubmission::get();
        $sorted = $submissions->sort(array('Browser' => 'ASC', 'LastEdited' =>
'ASC'));
        return $sorted;
    }

    public function sendEmail($submission) {
        $from = "nbezzala@gmail.com";
        $to = $submission->Email;
        $subject = "Browser Poll Submitted Successfully";

        $email = new Email($from, $to, $subject);
        $email->setTemplate('BrowserPollEmail');
        $email->populateTemplate(array(
            'Name'  => $submission->Name,
            'Email' => $submission->Email,
            'Browser'   => $submission->Browser,
            'Reason'    => $submission->Reason
        ));
        $email->send();
    }

}

