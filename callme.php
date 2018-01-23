<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

Class CallMe extends Module
{
    public function __construct()
    {
        $this->name = 'callme';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Oleg Patrushev';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        $this->module_key = 'b90196950551172c91987781b7b13f43';

        parent::__construct();

        $this->displayName = $this->l('Call me');
        $this->description = $this->l('Presents requests for call back with admin email notification.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('OP_CALLME_SETTINGS'))
            $this->warning = $this->l('No settings provided.');
    }

    public function install()
    {
        if (parent::install() &&
            $this->registerHook('displayNav1') &&
            $this->registerHook('header') &&
            Configuration::updateValue('OP_CALLME_SETTINGS', serialize([
                'modaltitle' => $this->l('Request a call'),
                'modaldescription' => $this->l('Our customer will call you'),
                'pagelinktext' => $this->l('Call me'),
                'pagehelptooltiptext' => $this->l('Need a help? Fill the form and our customer will call you!'),
                'notificationemail' => Configuration::get('PS_SHOP_EMAIL')
            ]))
        )
            return true;
        return false;
    }

    public function uninstall()
    {
        if (parent::uninstall() &&
            $this->unregisterHook('displayNav1') &&
            $this->unregisterHook('header') &&
            Configuration::deleteByName('OP_CALLME_SETTINGS')
        )
            return true;
        return false;
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            $modalTitle = strval(Tools::getValue('modaltitle'));
            $modalDescription = strval(Tools::getValue('modaldescription'));
            $pageLinkText = strval(Tools::getValue('pagelinktext'));
            $pageHelpTooltipText = strval(Tools::getValue('pagehelptooltiptext'));
            $notificationEmail = strval(Tools::getValue('notificationemail'));

            if (!$modalTitle
                || empty($modalTitle))
                $output .= $this->displayError($this->l('Modal title is required'));
            elseif (!$pageLinkText
                || empty($pageLinkText))
                $output .= $this->displayError($this->l('Page link text is required'));
            elseif (!$notificationEmail
                || empty($notificationEmail))
                $output .= $this->displayError($this->l('Notification email is required'));
            elseif (!Validate::isEmail($notificationEmail))
                $output .= $this->displayError($this->l('Notification email is incorrect'));
            else {
                Configuration::updateValue('OP_CALLME_SETTINGS', serialize([
                    'modaltitle' => $modalTitle,
                    'modaldescription' => $modalDescription,
                    'pagelinktext' => $pageLinkText,
                    'pagehelptooltiptext' => $pageHelpTooltipText,
                    'notificationemail' => $notificationEmail
                ]));
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }
        return $output . $this->displayForm();
    }

    public function displayForm()
    {
        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Modal title'),
                    'name' => 'modaltitle',
                    'size' => 20,
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Modal description'),
                    'name' => 'modaldescription',
                    'size' => 100,
                    'required' => false
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Page link text'),
                    'name' => 'pagelinktext',
                    'size' => 20,
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Page help tooltip text'),
                    'name' => 'pagehelptooltiptext',
                    'placeholder' => 'Leave empty to hide icon',
                    'size' => 100,
                    'required' => false
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Notification email'),
                    'name' => 'notificationemail',
                    'size' => 20,
                    'required' => true
                ]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ];

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                        '&token=' . Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        $moduleSettings = unserialize(Configuration::get('OP_CALLME_SETTINGS'));
        // Load current value
        $helper->fields_value['modaltitle'] = $moduleSettings['modaltitle'];
        $helper->fields_value['modaldescription'] = $moduleSettings['modaldescription'];
        $helper->fields_value['pagelinktext'] = $moduleSettings['pagelinktext'];
        $helper->fields_value['pagehelptooltiptext'] = $moduleSettings['pagehelptooltiptext'];
        $helper->fields_value['notificationemail'] = $moduleSettings['notificationemail'];

        return $helper->generateForm($fields_form);
    }

    public function hookDisplayNav1($params)
    {
        $moduleSettings = unserialize(Configuration::get('OP_CALLME_SETTINGS'));
        $this->context->smarty->assign([
            'modaltitle' => $moduleSettings['modaltitle'],
            'modaldescription' => $moduleSettings['modaldescription'],
            'pagelinktext' => $moduleSettings['pagelinktext'],
            'pagehelptooltiptext' => $moduleSettings['pagehelptooltiptext'],
            'supportimage' => $this->_path . 'images/support.png'
        ]);
        return $this->display(__FILE__, 'callme.tpl');
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->registerStylesheet('callme_css', $this->_path . 'css/callme.css');
        $this->context->controller->registerJavascript('callme_js', $this->_path . 'js/callme.js');
    }
}