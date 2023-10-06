<?php
namespace App\View\Helper;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Form\Form;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Utility\Text;
use Cake\Validation\Validator;
use Cake\View\Helper;
use Cake\View\View;

class SilverFormHelper extends Helper
{
    public $helpers = ['Html', 'Form'];

    public $fieldPrefix = 'silverform';

    public $fieldSubPrefix = 'field_';

    public $tablePrefix = 'silver_form_';

    public $fieldColumnPrefix = 'field_';

    private $session;

    private $Forms;

    private $FormFieldRelations;

    private $SilverFormData;

    private $SilverFormAssign;

    private $Roles;

    private $_errors;

    public $dbConnection = 'default';

    private $frmKey;

    private $tmpQueryData;

    public function initialize(array $config)
    {
        $this->session            = $this->request->getSession();
        $this->Forms              = TableRegistry::get('Forms');
        $this->FormFieldRelations = TableRegistry::get('FormFieldRelations');
        $this->SilverFormData     = TableRegistry::get('SilverFormData');
        $this->Roles              = TableRegistry::get('Roles');
        $this->SilverFormAssign   = TableRegistry::get('SilverFormAssign');
    }

    public function _SetDBConnection($connection)
    {
        $this->dbConnection = $connection;
    }

    public function get($form_id, $content = true)
    {
        $output            = [];
        $formFields        = $this->_GetFormFields($form_id, $content);
        $output['form']    = (isset($formFields['form'])) ? $formFields['form'] : [];
        $output['fields']  = (isset($formFields['fields'])) ? $formFields['fields'] : [];
        $output['content'] = (isset($formFields['content'])) ? $formFields['content'] : '';
        return $output;
    }

    public function save($form_id = null, $submit_id = null, $data = array())
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->_errors    = [];
            $form_id          = ($form_id) ? $form_id : $this->request->getData('form_id');
            $submit_id        = ($submit_id) ? $submit_id : $this->request->getData('submit_id');
            $silverFormFields = $this->request->getData($this->fieldPrefix);
            $other            = [];
            if (!empty($data)) {
                $silverFormFields = $data;
            }
            if (isset($silverFormFields['other'])) {
                $other = $silverFormFields['other'];
                unset($silverFormFields['other']);
            }

            if (!empty($form_id) && !empty($silverFormFields)) {
                //Check Table
                $table      = $this->tablePrefix . $form_id;
                $connection = ConnectionManager::get($this->dbConnection);
                $checkTable = $connection->execute("SHOW TABLES LIKE '" . $table . "'")->fetchAll('assoc');
                if (!empty($checkTable)) {
                    $this->SilverFormData->setTable($table);
                } else {
                    $this->SilverFormData->setTable('silver_form_data');
                }

                if (!empty($submit_id)) {
                    $silverFormData = $this->SilverFormData->findById($submit_id)->first();
                    if (empty($silverFormData)) {
                        $silverFormData = $this->SilverFormData->newEntity();
                    }
                } else {
                    $silverFormData = $this->SilverFormData->newEntity();
                }
                $isNew = ($silverFormData->isNew()) ? true : false;

                $forms  = $this->get($form_id, false);
                $fields = (!empty($forms['fields'])) ? $forms['fields'] : [];
                if (!empty($fields)) {
                    $fields = Hash::extract($fields, '{n}[fr-type=frm-field]');
                    if (!empty($fields)) {
                        $fields = Hash::combine($fields, '{n}.field_id', '{n}');
                    }
                }
                if (!$isNew) {
                    foreach ($fields as $field_id => $field) {
                        $fieldKey = $this->fieldColumnPrefix . $field_id;
                        if (!isset($silverFormFields[$fieldKey]) && isset($silverFormData->{$fieldKey})) {
                            $value = $silverFormData->{$fieldKey};
                            if ($field['field_type'] == 'file') {
                                if (trim($value) != '' && !is_null($value) && !empty($field['validations'])) {
                                    $fields[$field_id]['validations'] = Hash::remove($field['validations'], '{n}[rule=required]');
                                }
                            } else {
                                $value                                             = $this->_jsonToArray($value);
                                $silverFormFields[$fieldKey][$field['field_name']] = $value;
                            }
                        }
                    }
                }
                $errors = $this->validFields($form_id, $fields, $silverFormFields, $isNew);
                if (empty($errors)) {
                    $frmData       = [];
                    $_unlink_files = [];
                    foreach ($silverFormFields as $fieldKey => $fData) {
                        $field_id = str_replace($this->fieldSubPrefix, '', $fieldKey);
                        $field    = (isset($fields[$field_id])) ? $fields[$field_id] : [];
                        $value    = $this->_FilterSubmitFieldData($field, $fData);
                        if (!$isNew && (isset($field['field_type']) && $field['field_type'] == 'file')) {
                            $_fieldKey   = $this->fieldColumnPrefix . $field_id;
                            $_file_value = (isset($silverFormData->{$_fieldKey})) ? $silverFormData->{$_fieldKey} : null;
                            if (trim($value) == '' && is_null($value)) {
                                $value = $_file_value;
                            } else {
                                $_f_unlink = $this->_jsonToArray($_file_value);
                                if (!empty($_f_unlink)) {
                                    $_f_unlink     = (is_array($_f_unlink)) ? $_f_unlink : [$_f_unlink];
                                    $_unlink_files = array_merge($_f_unlink, $_unlink_files);
                                }
                            }
                        }
                        $column           = $this->fieldColumnPrefix . $field_id;
                        $frmData[$column] = $value;
                    }
                    $frmData['other'] = $this->_SetValue($other);
                    //Check Table And Filter Field
                    if (empty($checkTable)) {
                        $fields             = json_encode($frmData);
                        $frmData            = [];
                        $frmData['form_id'] = $form_id;
                        $frmData['fields']  = $fields;
                    }
                    if ($isNew) {
                        if ($this->request->getSession()->check('Auth.User.id')) {
                            $frmData['created_by'] = $this->request->getSession()->read('Auth.User.id');
                        }
                        $frmData['created_at'] = date('Y-m-d H:i:s');
                    }
                    //Save
                    $silverFormData = $this->SilverFormData->patchEntity($silverFormData, $frmData);
                    $silverFormData = $this->SilverFormData->save($silverFormData);
                    $errors         = $silverFormData->getErrors();
                    if ($silverFormData) {
                        $submit_id = $silverFormData->id;

                        //Form Assgin Process
                        if ($isNew && $forms['form']['is_processes_form'] == 1) {
                            if (isset($silverFormData->field_130) && isset($silverFormData->field_5) && isset($silverFormData->field_6)) {
                                $departmant_id = $silverFormData->field_130;
                                $taluka_id     = $silverFormData->field_5;
                                $village_id    = $silverFormData->field_6;
                                $roles         = $this->Roles->find('all')
                                    ->contain(['Users' => function ($q) {
                                        $q->select(['id', 'role_id']);
                                        $q->limit(1);
                                        return $q;
                                    }])
                                    ->where([
                                        'Roles.departments LIKE \'%"' . $departmant_id . '"%\'',
                                        'Roles.talukas LIKE \'%"' . $taluka_id . '"%\'',
                                        'Roles.villages IS NOT NULL',
                                        'Roles.villages != "[]"',
                                    ])
                                    ->enableHydration(false)
                                    ->toArray();
                                $findUser = $forms['form']['default_assign_user'];
                                if (!empty($roles)) {
                                    foreach ($roles as $role) {
                                        if (!empty($role['villages'][$taluka_id]) &&
                                            in_array($village_id, $role['villages'][$taluka_id]) &&
                                            !empty($role['users'])) {
                                            $findUser = $role['users'][0]['id'];
                                            break;
                                        }
                                    }
                                }
                                if (!empty($findUser)) {
                                    $_assign_table = $this->tablePrefix . 'assgin_' . $form_id;
                                    $this->SilverFormAssign->setTable($_assign_table);
                                    $assignNew  = $this->SilverFormAssign->newEntity();
                                    $assignData = [];
                                    if ($this->request->getSession()->check('Auth.User.id')) {
                                        $assignData[] = [
                                            'submission_id' => $submit_id,
                                            'assign_id'     => $findUser,
                                            'template_id'   => 0,
											'remarks' 		=> "Created",
                                            'status'        => 0,
                                            'created_at'    => date('Y-m-d H:i:s'),
											'sender_id'     => $this->request->getSession()->read('Auth.User.id'),
                                        ];
                                    }
                                    $assignData[] = [
                                        'submission_id' => $submit_id,
                                        'user_id'       => $findUser,
                                        'template_id'   => 0,
										'remarks' 		=> "Assign",
                                        'status'        => 1,
                                        'created_at'    => date('Y-m-d H:i:s'),
										'sender_id'     => $findUser,
                                    ];
                                    //Save
                                    $assignData = $this->SilverFormAssign->patchEntities($assignNew, $assignData);
                                    $this->SilverFormAssign->saveMany($assignData);
                                }
                            }
                        }

                        if (!$isNew && !empty($_unlink_files)) {
                            foreach ($_unlink_files as $_unlink_file) {
                                @unlink(WWW_ROOT . $_unlink_file);
                            }
                        }
                        return $submit_id;
                    } else {
                        $this->setErrors($errors);
                        return false;
                    }
                } else {
                    $this->setErrors($errors);
                    return false;
                }
            } else {
                return false;
            }
        }
        return;
    }

    public function setErrors($errors)
    {
        if (!empty($this->_errors)) {
            $errors = array_merge($this->_errors, $errors);
        }
        $this->_errors = $errors;

    }

    public function errors()
    {
        $_form = new Form();
        if (!empty($this->_errors)) {
            $_form->setErrors(json_decode(json_encode($this->_errors), true));
        }
        return $_form;
    }

    public function getDependent($arg)
    {
        $result = ['status' => false, 'response' => [], 'error' => ''];
        if ($this->request->is(['post'])) {
            $frmKey = $arg['frmKey'];
            if ($this->session->check('form-builder.frmKeys') && in_array($frmKey, $this->session->read('form-builder.frmKeys'))) {
                $result['status'] = true;
                $type             = $arg['type'];
                $dbquery          = $arg['dbquery'];
                $dependent        = $arg['dependent'];
                $value            = $arg['value'];
                if (empty($type) || empty($dbquery) || empty($dependent)) {
                    $result['error'] = 'invalid-dependent';
                } else {
                    if (trim($value) != '' && !is_null($value)) {
                        $depField = $dependent['dependent_field'];
                        if (empty($dbquery['conditions'])) {
                            $dbquery['db_condition'] = 1;
                            $dbquery['conditions']   = [];
                        }
                        $dbquery['conditions'][] = ['column' => $depField, 'value' => $value];
                        switch ($type) {
                            case 'select':
                            case 'multi-select':
                                $options = $this->_GetOptionsByQuery($dbquery);
                                if (!empty($options)) {
                                    $result['response'] = $options;
                                }
                            default:
                                //
                                break;
                        }
                    }
                }
            } else {
                $result['error'] = 'invalid';
            }
        } else {
            $result['error'] = 'invalid_request_method';
        }

        return $result;
    }

    private function _GetFormFields($form_id, $content = true)
    {
        $language = (Configure::check('language')) ? Configure::read('language.culture') . '__' : '';
        $cacheKey = 'form__' . $language . $form_id;
        $frmObj   = Cache::read($cacheKey, 'silverform');
        if ($frmObj === false) {
            $frmObj = [];
            $form   = $this->Forms->findById($form_id)->first();
            if (!empty($form) && $form->get('status') == 1) {
                $frmObj['form'] = [
                    'form_id'               => $form->get('id'),
                    'title'                 => $this->_GetLanguageText($form->get('title')),
                    'is_processes_form'     => $form->get('is_processes_form'),
                    'assign_to'             => $form->get('assign_to'),
                    'is_dependent'          => $form->get('is_dependent'),
                    'dependent_field'       => $form->get('dependent_field'),
                    'dependent_assign_user' => $form->get('dependent_assign_user'),
                    'default_assign_user'   => $form->get('default_assign_user'),
                    'status'                => $form->get('status'),
                ];
                $fieldRelations = $this->FormFieldRelations->find('all')
                    ->contain(['Fields' => function ($q) {
                        $q->where(['Fields.status' => 1]);
                        return $q;
                    }])
                    ->where([
                        'FormFieldRelations.form_id' => $form->id,
                        'FormFieldRelations.status'  => 1,
                    ])
                    ->order(['FormFieldRelations.sort_order' => 'ASC'])
                    ->toArray();
                $frmObj['fields'] = [];
                if (!empty($fieldRelations)) {
                    foreach ($fieldRelations as $fieldRelation) {
                        $type = $fieldRelation->get('type');
                        if ($type == 'frm-field') {
                            if ($fieldRelation->has('field') && $fieldRelation->field->get('status') == 1) {
                                $field_label        = $this->_GetLanguageText($fieldRelation->get('field_label'));
                                $field              = $fieldRelation->field;
                                $fField             = $this->_FilterField($field, $type);
                                $field_id           = $fField['field_id'];
                                $fField['label']    = (!empty($field_label)) ? $field_label : $fField['label'];
                                $fField['form_id']  = $form->id;
                                $frmObj['fields'][] = $fField;
                            }
                        } else if ($type == 'frm-group' && $fieldRelation->get('status') == 1) {
                            $fField             = $this->_FilterField($fieldRelation, $type);
                            $fField['form_id']  = $form->id;
                            $frmObj['fields'][] = $fField;
                        }
                    }
                }
            }
            Cache::write($cacheKey, $frmObj, 'silverform');
        }
        $frmObj = $this->_FilterFormFields($frmObj, $content);
        return $frmObj;
    }

    private function _FilterFormFields($frmObj = array(), $content = true)
    {
        if (!empty($frmObj)) {
            $frmKey            = $this->_GetFrmKey();
            $frmObj['content'] = '';
            foreach ($frmObj as $key => $value) {
                if ($key == 'form') {
                    $frmObj[$key]['frmKey'] = $frmKey;
                } else if ($key == 'fields') {
                    $value = Hash::insert($value, '{n}.frmKey', $frmKey);
                    if ($content) {
                        $ffHtml = $this->_GenerateFields($value);
                        //pr($ffHtml);exit;
                        if (!empty($ffHtml)) {
                            foreach ($ffHtml['fields'] as $fKey => $fHtml) {
                                if (isset($value[$fKey])) {
                                    $value[$fKey] = array_merge($fHtml, $value[$fKey]);
                                }
                            }
                            $frmObj['content'] = implode('', $ffHtml['html']);
                        }
                        $frmObj[$key] = $value;
                    }
                }
            }
        }
        return $frmObj;
    }

    private function _FilterField($field, $type = null)
    {
        if (empty($field)) {return $field;}
        if ($type == 'frm-field') {
            //Get Options
            $options   = $field->get('options');
            $dependent = $field->get('dependent');
            if ($field->get('option_type') === 'custom' && !empty($options)) {
                $options = $this->_FilterOptions($options);
            } else if ($field->get('option_type') === 'dbquery') {
                $isDependent = (!empty($dependent) && (isset($dependent['dependent']) && $dependent['dependent'] == 1)) ? true : false;
                if (!$isDependent) {
                    $options = $this->_GetOptionsByQuery($options);
                }
            }

            //Get Info
            $info = $field->get('info');
            if (!empty($info) && isset($info['show']) && $info['show'] == 1) {
                unset($info['show']);
                $info = $this->_GetLanguageText($info);
            } else {
                $info = null;
            }
            //Field Array
            $ffield = [
                'fr-type'     => $type,
                'field_id'    => $field->get('id'),
                'field_name'  => $field->get('field_name'),
                'label'       => $this->_GetLanguageText($field->get('label')),
                'info'        => $info,
                'field_type'  => $field->get('field_type'),
                'upload_path' => $field->get('upload_path'),
                'option_type' => $field->get('option_type'),
                'options'     => $options,
                'value'       => $field->get('value'),
                'dependent'   => $dependent,
                'attributes'  => $this->_FilterAttributes($field->get('attributes')),
                'validations' => $this->_FilterValidations($field->get('validations')),
                'status'      => $field->get('status'),
            ];
        } else if ($type == 'frm-group') {
            //Field Array
            $ffield = [
                'fr-type'     => $type,
                'label'       => $this->_GetLanguageText($field->get('field_label')),
                'description' => $this->_GetLanguageText($field->get('description')),
                'status'      => $field->get('status'),
            ];
        }
        return $ffield;
    }

    public function _GetFrmKey()
    {
        if (empty($this->frmKey)) {
            $uniqKey   = Text::uuid();
            $frmKeys   = [];
            $frmKeys[] = $uniqKey;
            if ($this->session->check('form-builder.frmKeys')) {
                $sessionFrmKeys = $this->session->read('form-builder.frmKeys');
                if (!empty($sessionFrmKeys)) {
                    $frmKeys = array_merge($frmKeys, $sessionFrmKeys);
                }
            }
            $this->session->write('form-builder.frmKeys', $frmKeys);
            $this->frmKey = $uniqKey;
        }

        return $this->frmKey;
    }

    private function _GenerateFields($fields = array())
    {
        if (empty($fields)) {return [];}
        $fOutput = ['fields' => [], 'html' => []];
        foreach ($fields as $key => $field) {
            if ($field['fr-type'] == 'frm-field') {
                $field_id             = $field['field_id'];
                $field_name           = $this->fieldPrefix . '.' . $this->fieldSubPrefix . $field_id . '.' . $field['field_name'];
                $attr                 = [];
                $attr['data-fname']   = $field['field_name'];
                $attr['data-frm-key'] = (isset($field['frmKey'])) ? $field['frmKey'] : $this->_GetFrmKey();
                $attr['label']        = $field['label'];
                if (isset($field['value']) && $field['value'] != '' && trim($field['value']) != '' && !is_null($field['value'])) {
                    $attr['value'] = $field['value'];
                }
                //Field Attributes
                $attr = (!empty($field['attributes'])) ? array_merge($attr, $field['attributes']) : $attr;
                //Field Options
                $isDependent = false;
                if (!empty($field['dependent']) &&
                    (isset($field['dependent']['dependent']) && $field['dependent']['dependent'] == 1)) {
                    $isDependent                   = true;
                    $attr['data-is_dependent']     = true;
                    $attr['data-opt_db_dependent'] = json_encode($field['dependent']);
                    if ($field['option_type'] === 'dbquery') {
                        $attr['data-opt_dbquery'] = json_encode($field['options']);
                        $field['options']         = [];
                    }
                }
                /*if ($field['option_type'] === 'dbquery') {
                if ($isDependent) {
                $attr['data-opt_dbquery'] = json_encode($field['options']);
                $field['options']         = [];
                } else {
                $field['options'] = $this->_GetOptionsByQuery($field['options']);
                }
                }*/
                //Field validations
                $field['validations'] = $this->_FilterToBootstrapValid($field['field_type'], $field['validations']);
                $attr                 = (!empty($field['validations'])) ? array_merge($attr, $field['validations']) : $attr;
                //Field Type Attr
                switch ($field['field_type']) {
                    case 'date':
                        $attr['type'] = 'date-text';
                        break;
                    case 'time':
                        $attr['type'] = 'time-text';
                        break;

                    case 'file':
                        if (isset($attr['multiple']) && $attr['multiple'] != false) {
                            $field_name = $field_name . '[]';
                        }
                        $attr['type'] = 'file';
                        break;

                    case 'radio':
                        $attr['type']        = 'radio';
                        $attr['hiddenField'] = false;
                        $attr['options']     = $field['options'];
                        break;

                    case 'checkbox':
                        $attr['hiddenField'] = false;
                        $attr['multiple']    = 'checkbox';
                        $attr['options']     = $field['options'];
                        if (count($field['options']) == 1) {
                            $attr['label'] = false;
                        }
                        break;

                    case 'select':
                    case 'multi-select':
                        $attr['type']    = 'select';
                        $attr['options'] = $field['options'];
                        if (isset($attr['placeholder'])) {
                            $attr['empty'] = $attr['placeholder'];
                            //unset($attr['placeholder']);
                        }
                        if (!isset($attr['empty'])) {$attr['empty'] = false;}
                        if ($field['field_type'] == 'multi-select') {$attr['multiple'] = true;}
                        break;

                    default:
                        $attr['type'] = $field['field_type'];
                        break;
                }
                if (trim($field['info']) != '' && !is_null($field['info'])) {
                    $attr['templateVars'] = ['help' => '<div class="frm-field-info">' . $field['info'] . '</div>'];
                }
                $fieldHtml = $this->Form->control($field_name, $attr);
                $fieldHtml = str_replace(
                    ['type="date-text"', 'type="time-text"'],
                    ['type="date"', 'type="time"'],
                    $fieldHtml
                );
                if (isset($attr['type']) && in_array($attr['type'], ['time-text', 'date-text'])) {
                    $attr['type'] = ($attr['type'] == 'date-text') ? 'date' : 'time';
                }
                $fOutput['fields'][$key] = ['html' => $fieldHtml, '_tag_name' => $field_name, '_tag_attr' => $attr];
                $fOutput['html'][$key]   = $fieldHtml;
            } else if ($field['fr-type'] == 'frm-group') {
                $fieldHtml = "";
                $fieldHtml .= (trim($field['label']) != '' && !is_null($field['label'])) ? '<div class="frm-gp-name">' . $field['label'] . '</div>' : '';
                $fieldHtml .= (trim($field['description']) != '' && !is_null($field['description'])) ? '<div class="frm-gp-description">' . $field['description'] . '</div>' : '';
                $fieldHtml               = (!empty($fieldHtml)) ? '<div class="frm-gp-area">' . $fieldHtml . '</div>' : '';
                $fOutput['fields'][$key] = ['html' => $fieldHtml, 'label' => trim($field['label']), 'description' => trim($field['description'])];
                $fOutput['html'][$key]   = $fieldHtml;
            }
        }
        return $fOutput;
    }

    public function _GenerateFieldHtml($field)
    {
        if (empty($field)) {return $field;}
        $fieldHtml = $this->_GenerateFields([$field]);
        return (isset($fieldHtml['html'][0])) ? $fieldHtml['html'][0] : '';
    }

    public function _GetOptionsByQuery($arg = array())
    {
        $language   = (Configure::check('language')) ? Configure::read('language.culture') : 'default';
        $connection = ConnectionManager::get($this->dbConnection);
        $query      = $connection->newQuery();
        $select     = [
            'value_field' => $arg['value_field'],
            'label_field' => $arg['label_field'],
        ];
        if (!empty($arg['select'])) {
            foreach ($arg['select'] as $k => $kk) {
                if (!in_array($k, ['value_field', 'label_field'])) {
                    $select[$k] = $kk;
                }
            }
        }
        $query->select($select);
        $query->from($arg['db_table']);
        if ((isset($arg['db_condition']) && $arg['db_condition'] == 1) && !empty($arg['conditions'])) {
            foreach ($arg['conditions'] as $condition) {
                $query->where([$condition['column'] => $condition['value']]);
            }
        }
        $_qstring = $query->sql();
        if (!isset($this->tmpQueryData[$language][$_qstring])) {
            $options = $query->execute()->fetchAll('assoc');
            if (!empty($options)) {
                foreach ($options as $key => $option) {
                    $opt = [
                        'value' => $option['value_field'],
                        'text'  => $this->_GetLanguageText($this->_jsonToArray($option['label_field'])),
                    ];
                    if (!empty($option)) {
                        foreach ($option as $k => $kk) {
                            if (!in_array($k, ['value_field', 'label_field'])) {
                                $opt[$k] = $this->_GetLanguageText($this->_jsonToArray($kk));
                            }
                        }
                    }
                    $options[$key] = $opt;
                }
                $this->tmpQueryData[$language][$_qstring] = $options;
                return $options;
            } else {
                return [];
            }
        } else {
            return $this->tmpQueryData[$language][$_qstring];
        }
    }

    public function _GetLanguageText($text = null)
    {
        if (empty($text)) {return '';}
        $text = $this->_jsonToArray($text);
        $text = (is_array($text)) ? $text : ['default' => $text];
        if (Configure::check('language')) {
            $culture = Configure::read('language.culture');
            if (!empty($text[$culture])) {
                return $text[$culture];
            } else {
                return (!empty($text['default'])) ? $text['default'] : '';
            }
        } else {
            return (!empty($text['default'])) ? $text['default'] : '';
        }
    }

    public function _FilterSubmitFieldData($field, $submitData)
    {
        $value = null;
        if (!empty($field) && isset($field['field_type'])) {
            switch ($field['field_type']) {
                case 'file':
                    $uploadFiles = [];
                    $path        = $field['upload_path'];
                    $field_name  = $field['field_name'];
                    if (isset($submitData[$field_name])) {
                        $files = $submitData[$field_name];
                        if (!empty($files)) {
                            $files = (isset($files['tmp_name'])) ? [$files] : $files;
                            foreach ($files as $file) {
                                if (!empty($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
                                    $ext      = explode('.', $file['name']);
                                    $ext      = '.' . strtolower(end($ext));
                                    $filename = Text::slug($field_name . '-' . uniqid() . '-' . time());
                                    $filename = $filename . $ext;
                                    if (move_uploaded_file($file['tmp_name'], WWW_ROOT . $path . $filename)) {
                                        $uploadFiles[] = $path . $filename;
                                    }
                                }
                            }
                        }
                    }
                    $value = $this->_FilterValue($uploadFiles);
                    break;
                default:
                    $value = $this->_FilterValue($submitData);
                    break;
            }
        } else {
            $value = $this->_FilterValue($submitData);
        }
        $value = $this->_SetValue($value);
        return $value;
    }

    private function _FilterValue($value = null)
    {
        if (is_array($value)) {
            $_val = [];
            foreach ($value as $val) {
                if (is_array($val)) {
                    foreach ($val as $v) {
                        $_val[] = trim($v);
                    }
                } else {
                    $_val[] = trim($val);
                }
            }
            $value = (count($_val) == 1) ? $_val[0] : $_val;
        }
        return $value;
    }

    private function _SetValue($value = null)
    {
        if (!empty($value)) {
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }
        } else {
            $value = null;
        }

        return $value;
    }
    private function _FilterOptions($options = array())
    {
        if (!empty($options) && is_array($options)) {
            foreach ($options as $key => $_option) {
                if (is_array($_option) && isset($_option['text'])) {
                    $options[$key]['text'] = $this->_GetLanguageText($_option['text']);
                }
            }
        }
        return $options;
    }
    private function _FilterAttributes($attributes = array())
    {
        if (!empty($attributes) && is_array($attributes)) {
            $fAttributes = [];
            foreach ($attributes as $key => $_attribute) {
                if (!empty($_attribute['name']) && isset($_attribute['value'])) {
                    $fAttributes[$_attribute['name']] = $this->_GetLanguageText($_attribute['value']);
                }
            }
            $attributes = $fAttributes;
        }
        return $attributes;
    }

    private function _FilterValidations($validations = array())
    {
        if (!empty($validations) && is_array($validations)) {
            foreach ($validations as $key => $_validation) {
                if (is_array($_validation) && !empty($_validation['rule'])) {
                    $validations[$key]['message'] = $this->_GetLanguageText($_validation['message']);
                } else if (!isset($_validation['rule'])) {
                    $validations[$key]            = [];
                    $validations[$key]['rule']    = $key;
                    $validations[$key]['value']   = $_validation;
                    $validations[$key]['message'] = "";
                }
            }
        }
        return $validations;
    }

    private function _FilterToBootstrapValid($fieldType = 'text', $validations)
    {
        $bsValid = [];
        $bsValid = $this->_addBootstrapDefaultFieldTypeValid($fieldType, $bsValid);
        if (!empty($validations) && is_array($validations)) {
            foreach ($validations as $_validation) {
                if ($_validation['rule'] == 'required') {
                    $bsValid['required'] = 'required';
                    if (!empty($_validation['message'])) {
                        $bsValid['data-bv-notempty-message'] = $_validation['message'];
                    }
                } else if ($_validation['rule'] == 'pattern') {
                    $bsValid['data-bv-regexp']        = 'true';
                    $bsValid['data-bv-regexp-regexp'] = $_validation['value'];
                    if (!empty($_validation['message'])) {
                        $bsValid['data-bv-regexp-message'] = $_validation['message'];
                    }
                } else if ($_validation['rule'] == 'extension' && !empty($_validation['value'])) {
                    $_validation['value']              = implode(',', explode('|', $_validation['value']));
                    $bsValid['data-bv-file-extension'] = $_validation['value'];
                    if (!empty($_validation['message'])) {
                        $bsValid['data-bv-file-extension-message'] = $_validation['message'];
                    }
                    $bsValid['data-bv-file'] = "true";
                } else if ($_validation['rule'] == 'mimetype' && !empty($_validation['value'])) {
                    $_validation['value']         = implode(',', explode('|', $_validation['value']));
                    $bsValid['data-bv-file-type'] = $_validation['value'];
                    if (!empty($_validation['message'])) {
                        $bsValid['data-bv-file-type-message'] = $_validation['message'];
                    }
                    $bsValid['data-bv-file'] = "true";
                } else {
                    $bsValid['data-bv-' . $_validation['rule']] = $_validation['value'];
                    if (!empty($_validation['message'])) {
                        $bsValid['data-bv-' . $_validation['rule'] . '-message'] = $_validation['message'];
                    }
                }
            }
        }
        return $bsValid;
    }

    private function _addBootstrapDefaultFieldTypeValid($fieldType, $margeObj = array())
    {
        return $margeObj;
    }

    private function _isJson($string = null)
    {
        if ((!empty($string) && !is_null($string)) &&
            (!is_array($string) && !is_object($string))) {
            @json_decode($string);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }

    public function _jsonToArray($json = null)
    {
        if ($this->_isJson($json)) {
            return json_decode($json, true);
        } else if (is_object($json)) {
            return json_decode(json_encode($json), true);
        }
        return $json;
    }

    public function validFields($form_id, $fields, $submitFields, $isNew = true)
    {
        if (!empty($fields)) {
            $validator    = new Validator();
            $vFieldIdData = [];
            $vData        = [];
            foreach ($fields as $field) {
                if ($field['fr-type'] == 'frm-field') {
                    $field_id   = $field['field_id'];
                    $field_name = $field['field_name'];
                    if ($field['field_type'] == 'file') {
                        $validator->setProvider('fileValidator', 'App\Model\Validation\FileValidatorRules');
                    }
                    if (!empty($field['validations'])) {
                        $tmp_field_name = $this->fieldPrefix . '.' . $this->fieldSubPrefix . $field['field_id'] . '.' . $field['field_name'];
                        if (isset($submitFields[$this->fieldSubPrefix . $field_id][$field_name])) {
                            $vData[$tmp_field_name] = $submitFields[$this->fieldSubPrefix . $field_id][$field_name];
                        } else {
                            $vData[$tmp_field_name] = null;
                        }
                        $isRequired = false;
                        foreach ($field['validations'] as $_validation) {
                            if ($_validation['rule'] == 'required' && strtolower($_validation['value']) == 'required') {
                                $validator
                                    ->requirePresence($tmp_field_name)
                                    ->notEmpty($tmp_field_name, $_validation['message']);
                                $isRequired = true;
                            } else if ($_validation['rule'] == 'pattern' && !empty($_validation['value'])) {
                                $validator->add($tmp_field_name, 'validFormat', [
                                    'rule'    => ['custom', ('/' . trim($_validation['value'], '/') . '/')],
                                    'message' => $_validation['message'],
                                ]);
                            } else if ($_validation['rule'] == 'extension' && !empty($_validation['value'])) {
                                $validator
                                    ->add($tmp_field_name, 'extension', [
                                        'rule'     => ['checkfile', ['extension' => ['ext' => $_validation['value'], 'message' => $_validation['message']]]],
                                        'provider' => 'fileValidator',
                                    ]);
                            } else if ($_validation['rule'] == 'mimetype' && !empty($_validation['value'])) {
                                $validator
                                    ->add($tmp_field_name, 'mimetype', [
                                        'rule'     => ['checkfile', ['mimetype' => ['type' => $_validation['value'], 'message' => $_validation['message']]]],
                                        'provider' => 'fileValidator',
                                    ]);
                            } else if ($_validation['rule'] == 'filesize' && !empty($_validation['value'])) {
                                $validator
                                    ->add($tmp_field_name, 'filesize', [
                                        'rule'     => ['checkfile', ['filesize' => ['operator' => '<=', 'size' => $_validation['value'], 'message' => $_validation['message']]]],
                                        'provider' => 'fileValidator',
                                    ]);
                            } else if ($_validation['rule'] == 'filesize-min' && !empty($_validation['value'])) {
                                $validator
                                    ->add($tmp_field_name, 'filesize-min', [
                                        'rule'     => ['checkfile', ['filesize' => ['operator' => '>=', 'size' => $_validation['value'], 'message' => $_validation['message']]]],
                                        'provider' => 'fileValidator',
                                    ]);
                            } else if ($_validation['rule'] == 'filesize-max' && !empty($_validation['value'])) {
                                $validator
                                    ->add($tmp_field_name, 'filesize-max', [
                                        'rule'     => ['checkfile', ['filesize' => ['operator' => '<=', 'size' => $_validation['value'], 'message' => $_validation['message']]]],
                                        'provider' => 'fileValidator',
                                    ]);
                            } else if ($_validation['rule'] == 'dimensions-min' && !empty($_validation['value'])) {
                                $validator
                                    ->add($tmp_field_name, 'dimensions-min', [
                                        'rule'     => ['checkfile', [
                                            'dimensions' => [
                                                'min'     => $_validation['value'],
                                                'message' => $_validation['message'],
                                            ],
                                        ],
                                        ],
                                        'provider' => 'fileValidator',
                                    ]);
                            } else if ($_validation['rule'] == 'dimensions-max' && !empty($_validation['value'])) {
                                $validator
                                    ->add($tmp_field_name, 'dimensions-max', [
                                        'rule'     => ['checkfile', [
                                            'dimensions' => [
                                                'max'     => $_validation['value'],
                                                'message' => $_validation['message'],
                                            ],
                                        ],
                                        ],
                                        'provider' => 'fileValidator',
                                    ]);
                            } else if (!empty($_validation['rule']) && !empty($_validation['value'])) {
                                $validator->add($tmp_field_name, $_validation['rule'], [
                                    'rule'    => ['custom', ('/' . trim($_validation['value'], '/') . '/')],
                                    'message' => $_validation['message'],
                                ]);
                            }
                        }
                        if (!$isRequired) {
                            $validator->allowEmpty($tmp_field_name);
                        }
                    }
                }
            }
            $validErrors = $validator->errors($vData);
            $errors      = [];
            if (!empty($validErrors)) {
                foreach ($validErrors as $key => $error) {
                    $errors = Hash::insert($errors, $key, $error);
                }
            }
            return $errors;
        } else {
            return false;
        }
    }

    public function _GetFieldTypes()
    {
        return [
            'text'           => 'Text',
            'number'         => 'Number',
            'email'          => 'Email',
            'url'            => 'Url',
            'textarea'       => 'Textarea',
            'file'           => 'File',
            'radio'          => 'Radio',
            'checkbox'       => 'Checkbox',
            'select'         => 'Drop Down',
            'multi-select'   => 'Drop Down Multi Select',
            'date'           => 'Date',
            'time'           => 'Time',
            'datetime-local' => 'Date & Time',
            'color'          => 'Color',
            'hidden'         => 'Hidden',
        ];
    }

    public function _GetOptionType()
    {
        return [
            ''        => 'None',
            'custom'  => 'Custom Option',
            'dbquery' => 'Database Option',
        ];
    }

    public function getFieldValue($form_id, $field_id, $value = null)
    {
        $value = $this->_jsonToArray($value);
        $_form = $this->get($form_id, false);
        $field = Hash::extract($_form['fields'], '{n}[field_id=' . $field_id . ']');
        $field = (!empty($field) && !empty($field[0])) ? $field[0] : [];
        if (empty($field)) {return $value;}

        $field_type  = $field['field_type'];
        $isDependent = (!empty($field['dependent']) && (isset($field['dependent']['dependent']) && $field['dependent']['dependent'] == 1)) ? true : false;
        if (in_array($field_type, ['radio', 'checkbox', 'select', 'multi-select'])) {
            if ($field['option_type'] == 'dbquery' && $isDependent) {
                $field['options'] = $this->_GetOptionsByQuery($field['options']);
            }
        }
        $_value = '';
        //Field Type
        switch ($field_type) {
            case 'file':
                $value = (is_array($value)) ? $value : [$value];
                $_v    = [];
                foreach ($value as $v) {
                    if (!empty($v)) {
                        $link = Router::url('/' . trim($v, '/'), ['_full' => true]);
                        $v    = explode('/', $v);
                        $v    = end($v);
                        $_v[] = $this->Html->link($v, $link, ['escape' => false, 'target' => '_blank']);
                    }
                }
                $_value = (!empty($_v)) ? implode(' ,', $_v) : '';
                break;
            case 'radio':
            case 'checkbox':
            case 'select':
            case 'multi-select':
                if (!empty($value) && !empty($field['options'])) {
                    $value = (is_array($value)) ? $value : [$value];
                    $_v    = [];
                    foreach ($value as $v) {
                        $v    = Hash::extract($field['options'], '{n}[value=' . $v . '].text');
                        $_v[] = (!empty($v) && isset($v[0])) ? $v[0] : '';
                    }
                    $_value = (!empty($_v)) ? implode(' ,', $_v) : '';
                } else {
                    $_value = '';
                }
                break;

            default:
                if (!empty($value)) {
                    $value = (is_array($value)) ? $value : [$value];
                    $value = implode(' ,', $value);
                }
                $_value = $value;
                break;
        }
        return $_value;
    }

    public function _FilterFieldForApi($field)
    {
        if (!empty($field)) {
            if ($field['fr-type'] == 'frm-field') {
                $options     = $field['options'];
                $dependent   = $field['dependent'];
                $isDependent = (!empty($dependent) && (isset($dependent['dependent']) && $dependent['dependent'] == 1)) ? true : false;
                if ($field['option_type'] === 'dbquery' && $isDependent) {
                    $dependent['dbquery'] = $options;
                    $field['dependent']   = $dependent;
                    $field['options']     = null;
                    $dbquery = $dependent['dbquery'];
                    $dbquery['select'] = [
                        'map_field_id' => $dependent['dependent_field']
                    ];
                    $options = $this->_GetOptionsByQuery($dbquery);
                    if (!empty($options)) {
                        $field['options'] = $options;
                    }
                }
                $placeholder = false;
                if (!empty($field['attributes'])) {
                    $attributes = [];
                    foreach ($field['attributes'] as $name => $value) {
                        if ($name == 'placeholder' && !empty($value)) {$placeholder = true;}
                        $attributes[] = [
                            'name'  => trim($name),
                            'value' => $value,
                        ];
                    }
                    $field['attributes'] = $attributes;
                }

                if (in_array($field['field_type'], ['select', 'multi-select']) && $placeholder == false) {
                    $field['validations'] = null;
                }
            }
        }
        return $field;
    }

    public function list_toOption($aslist = array(), $selectedId = ""){
        $ssOptionList = "";
        
        foreach ($aslist as $snCode => $ssName) {
//            $ssName = strtolower($ssName);
//            $ssName = ucwords($ssName);
            if ($selectedId != "" && $selectedId == $snCode) {
                
                $ssOptionList .= "<option value='$snCode' selected>$ssName</option>";
            } else {
                $ssOptionList .= "<option value='$snCode'>$ssName</option>";
            }
        }
        return $ssOptionList;
    }
}
