<?php

require_once(dirname(__FILE__) . '../../../config/config.inc.php');
require_once(dirname(__FILE__) . '../../../init.php');

switch (Tools::getValue('method')) {
    case 'requestCall' :
        $context = Context::getContext();
        $data = json_decode(Tools::getValue('data'), true);
        $moduleSettings = unserialize(Configuration::get('OP_CALLME_SETTINGS'));
        if (Mail::Send(
            $context->language->id,
            'callme_admin_notification',
            'Someone requests a call',
            [
                '{name}' => Tools::safeOutput(strval($data['name'])),
                '{phone}' => Tools::safeOutput(strval($data['phone'])),
                '{email}' => Tools::safeOutput(strval($data['email'])),
                '{message}' => Tools::safeOutput(strval($data['message']))
            ],
            Tools::safeOutput(strval($moduleSettings['notificationemail'])),
            'Admin',
            null,
            null,
            null,
            null,
            _PS_MODULE_DIR_ . 'callme/'
        )) {
            die(json_encode(['success' => true]));
        } else {
            die(json_encode(['success' => false]));
        }

        break;
    default:
        exit;
}
exit;