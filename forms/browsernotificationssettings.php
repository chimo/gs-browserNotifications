<?php
if (!defined('GNUSOCIAL')) {
    exit(1);
}

class BrowserNotificationsSettingsForm extends Form
{
    public function __construct($out=null)
    {
        parent::__construct($out);
    }

    public function id()
    {
        return 'browser_notifications_settings';
    }

    public function formClass()
    {
        return 'form_settings';
    }

    public function action()
    {
        return common_local_url('browsernotificationssettings');
    }

    public function formData()
    {
        $user = common_current_user();

        $this->out->elementStart('fieldset');

        $this->out->elementStart('ul', 'form_data');

        // Start with default settings
        $user_settings = BrowserNotificationSettings::getDefaults();

        // Overwrite defaults with user settings if available
        $bns = BrowserNotificationSettings::getByUserId($user->id);

        if (!empty($bns)) {
            $user_settings = $bns;
        }

        // Enabled?
        $this->li();
        $this->out->checkbox(
            'enabled',  // id
            'Enabled',  // label
            $user_settings->enabled // checked
        );
        $this->unli();

        // Mentions only?
        // TODO
        /* $this->li();
        $this->out->checkbox(
            'mentions_only',
            'Only show notifications for notices that mention me',
            $user_settings->mentions_only;
        );
        $this->unli(); */

        $this->elementEnd('ul');
        $this->elementEnd('fieldset');
    }

    public function formActions()
    {
        $this->out->submit('browser-notifications-settings-submit', _m('BUTTON', 'Save'), 'submit', 'submit');
    }
}
