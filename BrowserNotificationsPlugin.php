<?php

if (!defined('GNUSOCIAL')) {
    exit(1);
}

class BrowserNotificationsPlugin extends Plugin
{
    const VERSION = '0.0.1';

    public function onEndAccountSettingsNav($action)
    {
        $action->elementStart('li');
        $action->element('a', array('href' => common_local_url('browsernotificationssettings')), 'Browser Notifications');
        $action->elementEnd('li');
        return true;
    }

    public function onRouterInitialized($m)
    {
        $m->connect(
            'settings/browsernotifications',
            array(
                'action' => 'browsernotificationssettings'
            )
        );

        return true;
    }

    public function onEndShowScripts($action)
    {
        $user_settings = BrowserNotificationSettings::getDefaults();

        if (common_logged_in()) {
            $user = common_current_user();

            $bns = BrowserNotificationSettings::getByUserId($user->id);

            if (!empty($bns)) {
                $user_settings = $bns;
            }
        }

        // Only include the JS if the setting is enabled
        if ($user_settings->enabled === true) {
            $action->inlineScript('BrowserNotifications = ' . $user_settings->toJSON());

            $action->script($this->path('js/browser-notifications.js'));
        }

        return true;
    }

    public function onCheckSchema()
    {
        $schema = Schema::get();
        $schema->ensureTable('browser_notifications', BrowserNotificationSettings::schemaDef());
        return true;
    }

    public function onPluginVersion(array &$versions): bool
    {
        $versions[] = [
            'name' => 'BrowserNotifications',
            'version' => self::VERSION,
            'author' => 'chimo',
            'homepage' => 'https://github.com/chimo/gs-browserNotifications',
            'description' =>
            // TRANS: Plugin description.
            _m('Receive browser notifications when a new notice and/or mention comes in.')
        ];
        return true;
    }
}
