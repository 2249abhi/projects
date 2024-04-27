<?php
namespace App\View\Helper;

use Cake\View\Helper\FormHelper as CakeFormHelper;
use Cake\View\View;

class FormHelper extends CakeFormHelper
{
    private $templates = [
        'dateWidget'          => '<span class="form-inline">{{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}</span>',
        'error'               => '<div class="sv-error text-danger help-block">{{content}}</div>',
        'formGroup'           => '<div class="form-group">{{label}}{{input}}{{error}}</div>{{help}}',
        //'formGroup'           => '{{label}}{{input}}{{error}}{{help}}',
        'inputContainer'      => '<div class="input-area {{type}}{{required}}">{{content}}</div>',
        'inputContainerError' => '<div class="input-area {{type}}{{required}} has-error">{{content}}</div>',
        'checkboxContainer'   => '<div class="input-area {{type}}{{required}}">{{content}}</div>',
        'radioContainer'      => '<div class="input-area {{type}}{{required}}">{{content}}</div>',
        //'radioFormGroup'      => '{{label}}<div class="radio-box">{{input}}</div>{{help}}',
        // Label element used for radio and multi-checkbox inputs.
        'nestingLabel'        => '<div class="form-check">{{hidden}}{{input}}<label{{attrs}}>{{text}}</label></div>',
    ];

    private $templatesHorizontal = [
        'label'               => '<label class="control-label col-md-2"{{attrs}}>{{text}}</label>',
        'error'               => '<div class="sv-error text-danger">{{content}}</div>',
        'formGroup'           => '{{label}}<div class="col-md-10">{{input}}{{error}}{{help}}</div>',
        'checkboxFormGroup'   => '<div class="checkbox">{{label}}</div>{{error}}{{help}}',
        'submitContainer'     => '<div class="col-md-10 col-md-offset-2">{{content}}</div>',
        'inputContainer'      => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
        'inputContainerError' => '<div class="form-group {{type}}{{required}} has-error">{{content}}{{error}}</div>',
        'checkboxContainer'   => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
        'radioContainer'      => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
        // Label element used for radio and multi-checkbox inputs.
        'nestingLabel'        => '<div class="form-check">{{hidden}}{{input}}<label{{attrs}}>{{text}}</label></div>',
    ];

    private $defaultClasses = ['form-control'];

    public function __construct(View $View, array $config = [])
    {
        $this->_defaultConfig['templates'] = array_merge($this->_defaultConfig['templates'], $this->templates);
        parent::__construct($View, $config);
    }

    public function create($model = null, array $options = [])
    {
        $formClass = (isset($options['class'])) ? $options['class'] : '';
        $formClass = (!empty($formClass) && !is_array($formClass)) ? explode(' ', $formClass) : $formClass;
        if (!empty($formClass) && in_array('form-horizontal', $formClass)) {
            $options['templates'] = $this->templatesHorizontal;
        }
        return parent::create($model, $options);
    }

    public function button($title, array $options = array())
    {
        $dClasses             = $this->defaultClasses;
        $this->defaultClasses = ['btn'];
        $options              = $this->_mergeClass($options);
        $this->defaultClasses = $dClasses;
        return parent::button($title, $options);
    }

    public function submit($caption = null, array $options = [])
    {
        $dClasses             = $this->defaultClasses;
        $this->defaultClasses = ['btn'];
        $options              = $this->_mergeClass($options);
        $this->defaultClasses = $dClasses;
        return parent::submit($caption, $options);
    }

    public function checkbox($fieldName, array $options = [])
    {
        $dClasses             = $this->defaultClasses;
        $this->defaultClasses = ['form-check-input'];
        $options              = $this->_mergeClass($options);
        $this->defaultClasses = $dClasses;
        $options              = $this->_removeClass($options, 'form-control');
        return parent::checkbox($fieldName, $options);
    }

    public function radio($fieldName, $options = [], array $attributes = [])
    {
        $dClasses             = $this->defaultClasses;
        $this->defaultClasses = ['form-check-input'];
        $attributes           = $this->_mergeClass($attributes);
        $this->defaultClasses = $dClasses;
        $attributes           = $this->_removeClass($attributes, 'form-control');

        $pRetrun = parent::radio($fieldName, $options, $attributes);

        if (isset($this->_defaultConfig['templates']['radioContainer'])) {
            $pRetrun = str_replace(['{{type}}', '{{required}}', "{{content}}"], ['radio', '', $pRetrun], $this->_defaultConfig['templates']['radioContainer']);
        }
        return $pRetrun;
    }

    public function file($fieldName, array $options = [])
    {
        $options = $this->_mergeClass($options);
        return parent::file($fieldName, $options);
    }

    public function input($fieldName, array $options = [])
    {
        $options += [
            'type'      => null,
            'label'     => null,
            'error'     => null,
            'required'  => null,
            'options'   => null,
            'templates' => [],
        ];
        $options = $this->_parseOptions($fieldName, $options);
        $options += ['id' => $this->_domId($fieldName)];
        switch ($options['type']) {
            case 'checkbox':
            case 'radio':
                $options['templates']['label'] = '{{text}}';
                break;
            /*case 'file':
                $options['templates']['inputContainer'] = '<div class="form-group {{type}}{{required}}">{{content}}</div>';
                $options['templates']['label']          = isset($options['templates']['label']) ? $options['templates']['label'] : '<label>{{input}}{{text}}</label>';
                break;
            case 'password':
                $options['templates']['inputContainer'] = '<div class="form-group {{type}}{{required}}">{{content}}</div>';
                $options['templates']['label']          = isset($options['templates']['label']) ? $options['templates']['label'] : '<label>{{input}}{{text}}</label>';
                break;*/
            default:
        }
        $options = $this->_mergeClass($options);
        return parent::input($fieldName, $options);
    }

    public function select($fieldName, $options = [], array $attributes = [])
    {

        if (isset($attributes['multiple']) && $attributes['multiple'] == 'checkbox') {
            $dClasses             = $this->defaultClasses;
            $this->defaultClasses = ['form-check-input'];
            $attributes           = $this->_mergeClass($attributes);
            $this->defaultClasses = $dClasses;
            $attributes           = $this->_removeClass($attributes, 'form-control');
        } else {
            $attributes = $this->_mergeClass($attributes);
        }
        return parent::select($fieldName, $options, $attributes);
    }

    public function textarea($fieldName, array $options = array())
    {
        $options += ['rows' => 3];
        $options = $this->_mergeClass($options);
        return parent::textarea($fieldName, $options);
    }

    public function hour($fieldName, array $options = [])
    {
        $options = $this->_mergeClass($options);
        return parent::hour($fieldName, $options);
    }

    public function time($fieldName, array $options = [])
    {
        $options = $this->_mergeClass($options);
        return parent::time($fieldName, $options);
    }

    public function year($fieldName, array $options = [])
    {
        $options = $this->_mergeClass($options);
        return parent::year($fieldName, $options);
    }

    public function month($fieldName, array $options = [])
    {
        $options = $this->_mergeClass($options);
        return parent::month($fieldName, $options);
    }

    public function day($fieldName = null, array $options = [])
    {
        $options = $this->_mergeClass($options);
        return parent::day($fieldName, $options);
    }

    public function minute($fieldName, array $options = [])
    {
        $options = $this->_mergeClass($options);
        return parent::minute($fieldName, $options);
    }

    public function dateTime($fieldName, array $options = [])
    {
        $options += [
            'empty'      => true,
            'value'      => null,
            'interval'   => 1,
            'round'      => null,
            'monthNames' => true,
            'minYear'    => null,
            'maxYear'    => null,
            'orderYear'  => 'desc',
            'timeFormat' => 24,
            'second'     => false,
        ];
        $options = $this->_initInputField($fieldName, $options);
        $options = $this->_datetimeOptions($options);
        $options = $this->_updateDatetimeOptions($options);
        return parent::dateTime($fieldName, $options);
    }

    protected function _updateDatetimeOptions($options)
    {
        $optionFields = ['year', 'month', 'day', 'hour', 'minute'];
        foreach ($optionFields as $optionField) {
            if (isset($options[$optionField]) && is_array($options[$optionField])) {
                $options[$optionField] = $this->_mergeClass($options[$optionField]);
            }
        }
        return $options;
    }

    public function control($fieldName, array $options = [])
    {
        $options += [
            'type'         => null,
            'label'        => null,
            'error'        => null,
            'required'     => null,
            'options'      => null,
            'templates'    => [],
            'templateVars' => [],
            'labelOptions' => true,
        ];
        $options = $this->_parseOptions($fieldName, $options);
        $options += ['id' => $this->_domId($fieldName)];
        $options = $this->_mergeClass($options);
        return parent::control($fieldName, $options);
    }

    protected function _mergeClass($attributes, $styles = null)
    {
        $attributes += ['class' => []];
        if (!is_array($attributes['class'])) {
            $attributes['class'] = explode(' ', $attributes['class']);
        }
        if (!empty($styles) && !is_array($styles)) {
            $styles = explode(' ', $styles);
        }
        if (!empty($styles) && !empty($this->defaultClasses)) {
            $styles = array_merge($styles, $this->defaultClasses);
        } else if (empty($styles) && !empty($this->defaultClasses)) {
            $styles = $this->defaultClasses;
        }
        if (!empty($styles)) {
            foreach ($styles as $style) {
                if (!in_array($style, $attributes['class'])) {
                    array_push($attributes['class'], $style);
                }
            }
        }
        return $attributes;
    }

    protected function _removeClass($attributes, $styles = null)
    {
        $attributes += ['class' => []];
        if (!is_array($attributes['class'])) {
            $attributes['class'] = explode(' ', $attributes['class']);
        }
        if (!empty($styles) && !is_array($styles)) {
            $styles = explode(' ', $styles);
        }
        if (!empty($styles) && !empty($attributes['class'])) {
            foreach ($attributes['class'] as $key => $class) {
                if (in_array($class, $styles)) {
                    $attributes['class'][$key] = '';
                }
            }
        }
        return $attributes;
    }

    public function __call($method, $params)
    {
        $options = [];
        if (empty($params)) {
            throw new \Exception(sprintf('Missing field name for FormHelper::%s', $method));
        }
        if (isset($params[1])) {
            $options = $params[1];
        }
        if (!isset($options['type'])) {
            $options['type'] = $method;
        }
        if (isset($options['class']) and is_array($options['class'])) {
            $options['class'] = implode(' ', $options['class']);
        }
        $options = $this->_initInputField($params[0], $options);
        $options = $this->_mergeClass($options);
        return $this->widget($options['type'], $options);
    }
}
