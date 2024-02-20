<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\DhbwTraining\DICTrait;

/**
 * Class ilObjDhbwTrainingGUI
 *
 * @author: Benjamin Seglias   <bs@studer-raimann.ch>
 */

/**
 * User Interface class for dhbw training repository object.
 *
 * @author            Benjamin Seglias <bs@studer-raimann.ch>
 *
 * @version           1.0.00
 *
 * Integration into control structure:
 * - The GUI class is called by ilRepositoryGUI
 * - GUI classes used by this class are ilPermissionGUI (provides the rbac
 *   screens) and ilInfoScreenGUI (handles the info screen).
 *
 * The most complicated thing is the control-flow.
 *
 *
 * @ilCtrl_isCalledBy ilObjDhbwTrainingGUI: ilRepositoryGUI
 * @ilCtrl_isCalledBy ilObjDhbwTrainingGUI: ilObjPluginDispatchGUI
 * @ilCtrl_isCalledBy ilObjDhbwTrainingGUI: ilAdministrationGUI
 * @ilCtrl_Calls      ilObjDhbwTrainingGUI: ilPermissionGUI,ilInfoScreenGUI, ilPropertyFormGUI, ilRepositorySelectorInputGUI, DhbwRepositorySelectorInputGUI, ilFormPropertyDispatchGUI
 * @ilCtrl_Calls      ilObjDhbwTrainingGUI: ilObjectCopyGUI
 * @ilCtrl_Calls      ilObjDhbwTrainingGUI: xdhtStartGUI
 * @ilCtrl_Calls      ilObjDhbwTrainingGUI: xdhtSettingsGUI
 * @ilCtrl_Calls      ilObjDhbwTrainingGUI: xdhtParticipantGUI
 * @ilCtrl_Calls      ilObjDhbwTrainingGUI: xdhtExportGUI
 * @ilCtrl_Calls      ilObjDhbwTrainingGUI: xdhtLearningProgressGUI
 * @ilCtrl_Calls      ilObjDhbwTrainingGUI: ilLearningProgressGUI
 * @ilCtrl_Calls      ilObjDhbwTrainingGUI: ilExportGUI
 * @ilCtrl_Calls      ilObjDhbwTrainingGUI: ilCommonActionDispatcherGUI
 */
class ilObjDhbwTrainingGUI extends ilObjectPluginGUI
{

    use DICTrait;
    const PLUGIN_CLASS_NAME = ilDhbwTrainingPlugin::class;
    const CMD_STANDARD = "index";
    const CMD_EDIT = "edit";
    const CMD_UPDATE = "update";
    const CMD_AFTER_SAVE = "afterSave";
    const TAB_START = "start";
    const TAB_SETTINGS = "settings";
    const TAB_COMPETENCE = "competence";
    const TAB_PARTICIPANTS = "participants";
    const TAB_LEARNING_PROGRESS = "learning_progress";
    const TAB_EXPORT = "export";
    const TAB_PERMISSIONS = "permissions";
    /* #SUR# is not necessary
    public ?ilObject $object = null;
    */
    /**
     * @var ilPropertyFormGUI
     */
    protected $form;
    /**
     * #SUR# type definition
     * @var ilTabsGUI
     */
    protected ilTabsGUI $tabs;
    /**
     * @var xdhtObjectFacadeInterface
     */
    protected $facade;

    /**
     * #SUR# return type definition
     * @return void
     * @throws \srag\DIC\DhbwTraining\Exception\DICException
     * @throws ilCtrlException
     * @throws ilException
     */
    public function executeCommand(): void
    {

        $this->setTitleAndDescription();
        if (!$this->getCreationMode()) {
            $this->tpl->setTitleIcon(ilObject::_getIcon($this->object->getId()));
        } else {
            $this->tpl->setTitleIcon(ilObject::_getIcon(ilObject::_lookupObjId($_GET['ref_id']), 'big'), self::plugin()->translate('obj_'
                . ilObject::_lookupType($_GET['ref_id'], true)));
        }

        $next_class = self::dic()->ctrl()->getNextClass($this);

        if ($this->creation_mode) {
            $cmd = self::dic()->ctrl()->getCmd(self::CMD_EDIT);
        } else {
            $cmd = self::dic()->ctrl()->getCmd(xdhtStartGUI::CMD_STANDARD);
        }

        switch ($next_class) {
            case strtolower(xdhtParticipantGUI::class):
                $this->setTabs();
                $this->setLocator();
                $this->tabs->activateTab(self::TAB_PARTICIPANTS);
                //has to be called because in this case parent::executeCommand is not executed(contains getStandardTempplate and Show)
                //Show Method has to be called in the corresponding methods
            if(method_exists($this->tpl,'getStandardTemplate')) {
                $this->tpl->getStandardTemplate();
            }

                $xdhtParticipantsGUI = new xdhtParticipantGUI($this->facade);
                $this->ctrl->forwardCommand($xdhtParticipantsGUI);
                break;

            case strtolower(xdhtQuestionGUI::class):
                $this->setTabs();
                $this->setLocator();
                $this->tabs->activateTab(self::TAB_START);
                //has to be called because in this case parent::executeCommand is not executed(contains getStandardTempplate and Show)
                //Show Method has to be called in the corresponding methods
                if(method_exists($this->tpl,'getStandardTemplate')) {
                $this->tpl->getStandardTemplate();
            }
                $xdhtQuestionGUI = new xdhtQuestionGUI($this->facade);
                $this->ctrl->forwardCommand($xdhtQuestionGUI);
                break;

            case strtolower(xdhtStartGUI::class):
                $this->setTabs();
                $this->setLocator();
                $this->tabs->activateTab(self::TAB_START);
                if(method_exists($this->tpl,'getStandardTemplate')) {
                $this->tpl->getStandardTemplate();
            }
                $xdhtStartGUI = new xdhtStartGUI($this->facade);
                $this->ctrl->forwardCommand($xdhtStartGUI);
                break;

            case strtolower(xdhtExportGUI::class):
                $this->setTabs();
                $this->setLocator();
                $this->tabs->activateTab(self::TAB_EXPORT);
                if(method_exists($this->tpl,'getStandardTemplate')) {
                $this->tpl->getStandardTemplate();
            }
                $exp_gui = new xdhtExportGUI($this); // $this is the ilObj...GUI class of the resource
                //$exp_gui->addFormat("xml");
                self::dic()->ctrl()->forwardCommand($exp_gui);
                break;

            /*			case 'ilexportgui':
                            $this->setTabs();
                            $this->setLocator();
                            $this->tabs->activateTab(self::TAB_START);
                            if(method_exists($this->tpl,'getStandardTemplate')) {
                $this->tpl->getStandardTemplate();
            }
                            $exp = new ilExportGUI($this);
                            $exp->addFormat('xml');
                            $this->ctrl->forwardCommand($exp);
                            break;*/

            case strtolower(ilLearningProgressGUI::class):
                if ($this->facade->settings()->getLearningProgress()) {
                    //#SUR# do not return
                    parent::executeCommand();
                } else {
                    //ilUtil::sendFailure(self::plugin()->translate('permission_denied'), true);
                    $this->tpl->setOnScreenMessage('failure',self::plugin()->translate('permission_denied'), true);
                }
                break;

            default:
                //#SUR# do not return
                parent::executeCommand();
                break;
        }
    }

    /**
     * #SUR# return type definition
     * @return void
     * @throws \srag\DIC\DhbwTraining\Exception\DICException
     * @throws ilCtrlException
     */
    protected function setTabs(): void
    {

        if (strtolower($_GET['baseClass']) != 'iladministrationgui') {
            $this->tabs->addTab(self::TAB_START, self::plugin()->translate('start'), $this->ctrl->getLinkTargetByClass(xdhtStartGUI::class, xdhtStartGUI::CMD_STANDARD));
            if ($this->checkPermissionBool('write')) {
                $this->tabs->addTab(self::TAB_PARTICIPANTS, self::plugin()->translate('participant'), $this->ctrl->getLinkTargetByClass(array(
                    strtolower(ilObjDhbwTrainingGUI::class),
                    strtolower(xdhtParticipantGUI::class),
                ), xdhtParticipantGUI::CMD_STANDARD));
            }
            if ($this->facade->settings()->getLearningProgress() && ilLearningProgressAccess::checkAccess($this->object->getRefId())) {
                $this->tabs->addTab(self::TAB_LEARNING_PROGRESS,
                    self::plugin()->translate('learning_progress'),
                    $this->ctrl->getLinkTargetByClass(array('ilobjdhbwtraininggui','illearningprogressgui'), ''),
                    array('illearningprogressgui', 'illplistofobjectsgui', 'illplistofsettingsgui', 'illplistofprogressgui'),
                    );
            }
            if (ilObjDhbwTrainingAccess::hasWriteAccess()) {
                $this->tabs->addTab(self::TAB_SETTINGS, self::plugin()->translate('settings'), $this->ctrl->getLinkTarget($this, self::CMD_EDIT));
            }
            if ($this->checkPermissionBool('write')) {
                $this->tabs->addTab(self::TAB_EXPORT,
                    self::plugin()->translate("export"),
                    self::dic()->ctrl()->getLinkTargetByClass(xdhtExportGUI::class, ''));
            }
            if ($this->checkPermissionBool('edit_permission')) {
                $this->tabs->addTab(self::TAB_PERMISSIONS, self::plugin()->translate('permissions'), $this->ctrl->getLinkTargetByClass(array(
                    strtolower(ilObjDhbwTrainingGUI::class),
                    strtolower(ilPermissionGUI::class),
                ), 'perm'));
            }
        } else {
            $this->addAdminLocatorItems();
            $this->tpl->setLocator();
            $this->setAdminTabs();
        }
    }

    /**
     * #SUR# return type definition
     * @return string
     */
    function getType(): string
    {
        return ilDhbwTrainingPlugin::PLUGIN_PREFIX;
    }

    /**
     * #SUR# return type definition
     * @return void
     */
    public function edit(): void
    {
        $this->tabs->activateTab(self::TAB_SETTINGS);
        $xdhtSettingsFormGUI = new xdhtSettingsFormGUI($this, $this->facade);
        $xdhtSettingsFormGUI->fillForm();
        $this->tpl->setContent($xdhtSettingsFormGUI->getHTML());
    }

    /**
     * #SUR# return type definition
     * @return void
     * @throws \srag\DIC\DhbwTraining\Exception\DICException
     * @throws ilCtrlException
     */
    public function update(): void
    {
        $this->tabs->activateTab(self::TAB_SETTINGS);
        $xdhtSettingsFormGUI = new xdhtSettingsFormGUI($this, $this->facade);
        if ($xdhtSettingsFormGUI->updateObject() && $this->object->update()) {
            //ilUtil::sendSuccess(self::plugin()->translate('changes_saved_success'), true);
            $this->tpl->setOnScreenMessage('success',self::plugin()->translate('changes_saved_success'), true);
            self::dic()->ctrl()->redirect($this, self::CMD_EDIT);

            return;
        }
        $xdhtSettingsFormGUI->setValuesByPost();
        $this->tpl->setContent($xdhtSettingsFormGUI->getHTML());
    }

    /**
     * Cmd that will be redirected to after creation of a new object.
     * #SUR# return type definition
     * @return string
     */
    function getAfterCreationCmd(): string
    {
        return self::CMD_EDIT;
    }

    /**
     * #SUR# return type definition
     * @return string
     */
    function getStandardCmd(): string
    {
        return xdhtStartGUI::CMD_STANDARD;
    }


    public function getId()
    {
        return self::class;
    }

    /**
     * #SUR# return type definition
     * @return void
     */
    protected function afterConstructor(): void
    {
        global $DIC;

        if ($this->ref_id) {
            $this->facade = xdhtObjectFacade::getInstance($this->ref_id);
            $this->object = $this->facade->training_object();
        }
        $this->dic = $DIC;
       /* #SUR# is not necessary
       $this->access = new ilObjDhbwTrainingAccess();
        */
        $this->pl = ilDhbwTrainingPlugin::getInstance();
        $this->tabs = $DIC->tabs();
        $this->ctrl = $DIC->ctrl();
        $this->tpl = $DIC['tpl'];
        $lng = self::dic()->language();
        $lng->loadLanguageModule('assessment');
    }

    /**
     * #SUR# return type definition
     * @param string $cmd
     * @return void
     * @throws \srag\DIC\DhbwTraining\Exception\DICException
     * @throws ilCtrlException
     */
    public function performCommand(string $cmd): void
    {
        //#SUR# $cmd comes now from method
        //$cmd = $this->ctrl->getCmd(xdhtStartGUI::CMD_STANDARD);

        switch ($cmd) {
            case self::CMD_STANDARD:
                if (ilObjDhbwTrainingAccess::hasReadAccess()) {
                    self::dic()->ctrl()->redirect(new xdhtStartGUI($this->facade), xdhtStartGUI::CMD_STANDARD);
                    break;
                } else {
                    //ilUtil::sendFailure(ilDhbwTrainingPlugin::getInstance()->txt('permission_denied'), true);
                    $this->tpl->setOnScreenMessage('failure',ilDhbwTrainingPlugin::getInstance()->txt('permission_denied'), true);
                    break;
                }
            case self::CMD_EDIT:
            case self::CMD_UPDATE:
            case self::CMD_AFTER_SAVE:
                if (ilObjDhbwTrainingAccess::hasWriteAccess()) {
                    $this->{$cmd}();
                    break;
                } else {
                    //ilUtil::sendFailure(self::plugin()->translate('permission_denied'), true);
                    $this->tpl->setOnScreenMessage('failure',self::plugin()->translate('permission_denied'), true);
                    break;
                }
        }
    }


    protected function getSettings()
    {
        if (xdhtSettings::where(['dhbw_training_object_id' => intval($this->object->getId())])->hasSets()) {
            return xdhtSettings::where(['dhbw_training_object_id' => intval($this->object->getId())])->first();
        } else {
            return new xdhtSettings();
        }
    }
}