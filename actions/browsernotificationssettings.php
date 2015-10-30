<?php
if (!defined('GNUSOCIAL')) {
    exit(1);
}

class BrowserNotificationsSettingsAction extends SettingsAction
{
    protected function doPost()
    {
        $this->user = common_current_user();

        $settings = array(
            'enabled' => $this->boolean('enabled', false),
            'mentions_only' => $this->boolean('mentions_only', false)
        );

        BrowserNotificationSettings::save($this->user, $settings);

        return _('Settings saved.');
    }

    function title()
    {
        return _m('Browser Notifications Settings');
    }

    function showContent()
    {
        $form = new BrowserNotificationsSettingsForm($this);
        $form->show();
    }
}
