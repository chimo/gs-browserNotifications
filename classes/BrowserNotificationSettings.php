<?php


if (!defined('GNUSOCIAL')) {
    exit(1);
}

class BrowserNotificationSettings extends Managed_DataObject
{
    public $__table = 'browser_notifications'; // table name

    public $user_id;         // int(10)
    public $enabled;         // boolean
    public $mentions_only;   // boolean

    public static function save($user, $settings)
    {
        $bns = new BrowserNotificationSettings();

        $bns->user_id = $user->id;
        $bns->enabled = $settings['enabled'];
        $bns->mentions_only = $settings['mentions_only'];

        // First time saving settings
        if (empty(self::getByUserId($user->id))) {
            $bns->insert();
        } else { // Updating existing settings
            $bns->update();
        }
    }

    public static function getDefaults()
    {
        $bns = new BrowserNotificationSettings();
        $bns->enabled = true;
        $bns->mentions_only = false;

        return $bns;
    }

    public function toJSON()
    {
        return json_encode(array(
            'enabled' => $this->enabled,
            'mentions_only' => $this->mentions_only
        ));
    }

    public static function getByUserId($userid)
    {
        $user_settings = self::getKV('user_id', $userid);

        $user_settings->enabled = (boolean)$user_settings->enabled;
        $user_settings->mentions_only = (boolean)$user_settings->mentions_only;

        return $user_settings;
    }

    public static function schemaDef()
    {
        return array(
            'fields' => array(
                'user_id' => array('type' => 'int(10)', 'not null' => true),
                'enabled' => array('type' => 'int', 'size' => 'tiny', 'default' => 1),
                'mentions_only' => array('type' => 'int', 'size' => 'tiny', 'default' => 0),
            ),
            'primary key' => array('user_id'),
            'foreign keys' => array(
                'browsernotifications_user_id_fkey' => array('user', array('user_id' => 'id'))
            )
        );
    }
}
