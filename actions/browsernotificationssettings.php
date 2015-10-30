<?php
if (!defined('GNUSOCIAL')) {
    exit(1);
}

class BrowserNotificationsSettingsAction extends Action
{
    function prepare($args)
    {
        parent::prepare($args);

        if (!common_logged_in()) { // Make sure we're logged in
            $this->clientError(_('Not logged in.'));
            return;
        } else if (!common_is_real_login()) { // Make _really_ sure we're logged in...
            common_set_returnto($this->selfUrl());
            $user = common_current_user();
            if (Event::handle('RedirectToLogin', array($this, $user))) {
                common_redirect(common_local_url('login'), 303);
            }
        } else { // k, I think by now we're logged in. For realz.
            $this->user = common_current_user();
        }

        if ($this->isPost()) {
            $this->checkSessionToken();
        }

        return true;
    }

    function handle($args)
    {
        parent::handle($args);

        if ($this->isPost()) {
            $settings = array(
                'enabled' => $this->boolean('enabled', false),
                'mentions_only' => $this->boolean('mentions_only', false)
            );

            BrowserNotificationSettings::save($this->user, $settings);
        }

        $this->showPage();
    }

    function title()
    {
        return _m('Browser Notifications Settings');
    }

    function showContent()
    {
        // TODO: Show 'success'/'error' msg after a form submit

        $form = new BrowserNotificationsSettingsForm($this);
        $form->show();
    }
}
