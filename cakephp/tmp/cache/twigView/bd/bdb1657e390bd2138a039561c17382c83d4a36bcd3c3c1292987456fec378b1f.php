<?php

/* C:\xampp\htdocs\cooperative\vendor\cakephp\bake\src\Template\Bake\Model\table.twig */
class __TwigTemplate_a501c63fe9497eb851613572a273a043f1d0c63f8d06750e54a02ced8c00c452 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 16
        $context["annotations"] = $this->getAttribute(($context["DocBlock"] ?? null), "buildTableAnnotations", array(0 => ($context["associations"] ?? null), 1 => ($context["associationInfo"] ?? null), 2 => ($context["behaviors"] ?? null), 3 => ($context["entity"] ?? null), 4 => ($context["namespace"] ?? null)), "method");
        // line 17
        echo "<?php
namespace";
        // line 18
        echo twig_escape_filter($this->env, ($context["namespace"] ?? null), "html", null, true);
        echo "\\Model\\Table;";
        // line 20
        $context["uses"] = array(0 => "use Cake\\ORM\\Query;", 1 => "use Cake\\ORM\\RulesChecker;", 2 => "use Cake\\ORM\\Table;", 3 => "use Cake\\Validation\\Validator;");
        // line 21
        echo twig_join_filter(($context["uses"] ?? null), "
");
        // line 23
        echo $this->getAttribute(($context["DocBlock"] ?? null), "classDescription", array(0 => ($context["name"] ?? null), 1 => "Model", 2 => ($context["annotations"] ?? null)), "method");
        echo "
class";
        // line 24
        echo twig_escape_filter($this->env, ($context["name"] ?? null), "html", null, true);
        echo "Table extends Table
{

    /**
     * Initialize method
     *
     * @param array \$config The configuration for the Table.
     * @return void
     */
    public function initialize(array \$config)
    {
        parent::initialize(\$config);";
        // line 37
        if (($context["table"] ?? null)) {
            // line 38
            echo "        \$this->setTable('";
            echo twig_escape_filter($this->env, ($context["table"] ?? null), "html", null, true);
            echo "');";
        }
        // line 41
        if (($context["displayField"] ?? null)) {
            // line 42
            echo "        \$this->setDisplayField('";
            echo twig_escape_filter($this->env, ($context["displayField"] ?? null), "html", null, true);
            echo "');";
        }
        // line 45
        if (($context["primaryKey"] ?? null)) {
            // line 46
            if ((twig_test_iterable(($context["primaryKey"] ?? null)) && (twig_length_filter($this->env, ($context["primaryKey"] ?? null)) > 1))) {
                // line 47
                echo "        \$this->setPrimaryKey([";
                echo $this->getAttribute(($context["Bake"] ?? null), "stringifyList", array(0 => ($context["primaryKey"] ?? null), 1 => array("indent" => false)), "method");
                echo "]);";
                // line 48
                echo "
";
            } else {
                // line 50
                echo "        \$this->setPrimaryKey('";
                echo twig_escape_filter($this->env, twig_first($this->env, $this->env->getExtension('Jasny\Twig\ArrayExtension')->asArray(($context["primaryKey"] ?? null))), "html", null, true);
                echo "');";
                // line 51
                echo "
";
            }
        }
        // line 55
        if (($context["behaviors"] ?? null)) {
        }
        // line 59
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["behaviors"] ?? null));
        foreach ($context['_seq'] as $context["behavior"] => $context["behaviorData"]) {
            // line 60
            echo "        \$this->addBehavior('";
            echo twig_escape_filter($this->env, $context["behavior"], "html", null, true);
            echo "'";
            echo (($context["behaviorData"]) ? (((", [" . $this->getAttribute(($context["Bake"] ?? null), "stringifyList", array(0 => $context["behaviorData"], 1 => array("indent" => 3, "quotes" => false)), "method")) . "]")) : (""));
            echo ");";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['behavior'], $context['behaviorData'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 63
        if ((($this->getAttribute(($context["associations"] ?? null), "belongsTo", array()) || $this->getAttribute(($context["associations"] ?? null), "hasMany", array())) || $this->getAttribute(($context["associations"] ?? null), "belongsToMany", array()))) {
        }
        // line 67
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["associations"] ?? null));
        foreach ($context['_seq'] as $context["type"] => $context["assocs"]) {
            // line 68
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($context["assocs"]);
            foreach ($context['_seq'] as $context["_key"] => $context["assoc"]) {
                // line 69
                $context["assocData"] = array();
                // line 70
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["assoc"]);
                foreach ($context['_seq'] as $context["key"] => $context["val"]) {
                    if ( !($context["key"] === "alias")) {
                        // line 71
                        $context["assocData"] = twig_array_merge(($context["assocData"] ?? null), array($context["key"] => $context["val"]));
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['key'], $context['val'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 73
                echo "        \$this->";
                echo twig_escape_filter($this->env, $context["type"], "html", null, true);
                echo "('";
                echo twig_escape_filter($this->env, $this->getAttribute($context["assoc"], "alias", array()), "html", null, true);
                echo "', [";
                echo $this->getAttribute(($context["Bake"] ?? null), "stringifyList", array(0 => ($context["assocData"] ?? null), 1 => array("indent" => 3)), "method");
                echo "]);";
                // line 74
                echo "
";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['assoc'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['type'], $context['assocs'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 77
        echo "    }";
        // line 78
        echo "
";
        // line 80
        if (($context["validation"] ?? null)) {
            // line 81
            echo "
    /**
     * Default validation rules.
     *
     * @param \\Cake\\Validation\\Validator \$validator Validator instance.
     * @return \\Cake\\Validation\\Validator
     */
    public function validationDefault(Validator \$validator)
    {";
            // line 90
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["validation"] ?? null));
            foreach ($context['_seq'] as $context["field"] => $context["rules"]) {
                // line 91
                $context["validationMethods"] = $this->getAttribute(($context["Bake"] ?? null), "getValidationMethods", array(0 => $context["field"], 1 => $context["rules"]), "method");
                // line 92
                if (($context["validationMethods"] ?? null)) {
                    // line 93
                    echo "        \$validator";
                    // line 94
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(($context["validationMethods"] ?? null));
                    $context['loop'] = array(
                      'parent' => $context['_parent'],
                      'index0' => 0,
                      'index'  => 1,
                      'first'  => true,
                    );
                    if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                        $length = count($context['_seq']);
                        $context['loop']['revindex0'] = $length - 1;
                        $context['loop']['revindex'] = $length;
                        $context['loop']['length'] = $length;
                        $context['loop']['last'] = 1 === $length;
                    }
                    foreach ($context['_seq'] as $context["_key"] => $context["validationMethod"]) {
                        // line 95
                        if ($this->getAttribute($context["loop"], "last", array())) {
                            // line 96
                            $context["validationMethod"] = ($context["validationMethod"] . ";");
                        }
                        // line 98
                        echo $context["validationMethod"];
                        ++$context['loop']['index0'];
                        ++$context['loop']['index'];
                        $context['loop']['first'] = false;
                        if (isset($context['loop']['length'])) {
                            --$context['loop']['revindex0'];
                            --$context['loop']['revindex'];
                            $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                        }
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['validationMethod'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['field'], $context['rules'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 103
            echo "        return \$validator;
    }";
        }
        // line 107
        if (($context["rulesChecker"] ?? null)) {
            // line 108
            echo "
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \\Cake\\ORM\\RulesChecker \$rules The rules object to be modified.
     * @return \\Cake\\ORM\\RulesChecker
     */
    public function buildRules(RulesChecker \$rules)
    {";
            // line 118
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["rulesChecker"] ?? null));
            foreach ($context['_seq'] as $context["field"] => $context["rule"]) {
                // line 119
                echo "        \$rules->add(\$rules->";
                echo twig_escape_filter($this->env, $this->getAttribute($context["rule"], "name", array()), "html", null, true);
                echo "(['";
                echo twig_escape_filter($this->env, $context["field"], "html", null, true);
                echo "']";
                echo ((($this->getAttribute($context["rule"], "extra", array(), "any", true, true) && $this->getAttribute($context["rule"], "extra", array()))) ? (((", '" . $this->getAttribute($context["rule"], "extra", array())) . "'")) : (""));
                echo "));";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['field'], $context['rule'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 121
            echo "
        return \$rules;
    }";
        }
        // line 126
        if ( !(($context["connection"] ?? null) === "default")) {
            // line 127
            echo "
    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName()
    {
        return '";
            // line 135
            echo twig_escape_filter($this->env, ($context["connection"] ?? null), "html", null, true);
            echo "';
    }";
        }
        // line 138
        echo "}
";
    }

    public function getTemplateName()
    {
        return "C:\\xampp\\htdocs\\cooperative\\vendor\\cakephp\\bake\\src\\Template\\Bake\\Model\\table.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  269 => 138,  264 => 135,  254 => 127,  252 => 126,  247 => 121,  235 => 119,  231 => 118,  220 => 108,  218 => 107,  214 => 103,  195 => 98,  192 => 96,  190 => 95,  173 => 94,  171 => 93,  169 => 92,  167 => 91,  163 => 90,  153 => 81,  151 => 80,  148 => 78,  146 => 77,  135 => 74,  127 => 73,  120 => 71,  115 => 70,  113 => 69,  109 => 68,  105 => 67,  102 => 63,  92 => 60,  88 => 59,  85 => 55,  80 => 51,  76 => 50,  72 => 48,  68 => 47,  66 => 46,  64 => 45,  59 => 42,  57 => 41,  52 => 38,  50 => 37,  36 => 24,  32 => 23,  29 => 21,  27 => 20,  24 => 18,  21 => 17,  19 => 16,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{#
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
#}
{% set annotations = DocBlock.buildTableAnnotations(associations, associationInfo, behaviors, entity, namespace) %}
<?php
namespace {{ namespace }}\\Model\\Table;

{% set uses = ['use Cake\\\\ORM\\\\Query;', 'use Cake\\\\ORM\\\\RulesChecker;', 'use Cake\\\\ORM\\\\Table;', 'use Cake\\\\Validation\\\\Validator;'] %}
{{ uses|join('\\n')|raw }}

{{ DocBlock.classDescription(name, 'Model', annotations)|raw }}
class {{ name }}Table extends Table
{

    /**
     * Initialize method
     *
     * @param array \$config The configuration for the Table.
     * @return void
     */
    public function initialize(array \$config)
    {
        parent::initialize(\$config);

{% if table %}
        \$this->setTable('{{ table }}');
{% endif %}

{%- if displayField %}
        \$this->setDisplayField('{{ displayField }}');
{% endif %}

{%- if primaryKey %}
    {%- if primaryKey is iterable and primaryKey|length > 1 %}
        \$this->setPrimaryKey([{{ Bake.stringifyList(primaryKey, {'indent': false})|raw }}]);
        {{- \"\\n\" }}
    {%- else %}
        \$this->setPrimaryKey('{{ primaryKey|as_array|first }}');
        {{- \"\\n\" }}
    {%- endif %}
{% endif %}

{%- if behaviors %}

{% endif %}

{%- for behavior, behaviorData in behaviors %}
        \$this->addBehavior('{{ behavior }}'{{ (behaviorData ? (\", [\" ~ Bake.stringifyList(behaviorData, {'indent': 3, 'quotes': false})|raw ~ ']') : '')|raw }});
{% endfor %}

{%- if associations.belongsTo or associations.hasMany or associations.belongsToMany %}

{% endif %}

{%- for type, assocs in associations %}
    {%- for assoc in assocs %}
        {%- set assocData = [] %}
        {%- for key, val in assoc if key is not same as('alias') %}
            {%- set assocData = assocData|merge({(key): val}) %}
        {%- endfor %}
        \$this->{{ type }}('{{ assoc.alias }}', [{{ Bake.stringifyList(assocData, {'indent': 3})|raw }}]);
        {{- \"\\n\" }}
    {%- endfor %}
{% endfor %}
    }
{{- \"\\n\" }}

{%- if validation %}

    /**
     * Default validation rules.
     *
     * @param \\Cake\\Validation\\Validator \$validator Validator instance.
     * @return \\Cake\\Validation\\Validator
     */
    public function validationDefault(Validator \$validator)
    {
{% for field, rules in validation %}
{% set validationMethods = Bake.getValidationMethods(field, rules) %}
{% if validationMethods %}
        \$validator
{% for validationMethod in validationMethods %}
{% if loop.last %}
{% set validationMethod = validationMethod ~ ';' %}
{% endif %}
            {{ validationMethod|raw }}
{% endfor %}

{% endif %}
{% endfor %}
        return \$validator;
    }
{% endif %}

{%- if rulesChecker %}

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \\Cake\\ORM\\RulesChecker \$rules The rules object to be modified.
     * @return \\Cake\\ORM\\RulesChecker
     */
    public function buildRules(RulesChecker \$rules)
    {
{% for field, rule in rulesChecker %}
        \$rules->add(\$rules->{{ rule.name }}(['{{ field }}']{{ (rule.extra is defined and rule.extra ? (\", '#{rule.extra}'\") : '')|raw }}));
{% endfor %}

        return \$rules;
    }
{% endif %}

{%- if connection is not same as('default') %}

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName()
    {
        return '{{ connection }}';
    }
{% endif %}
}
", "C:\\xampp\\htdocs\\cooperative\\vendor\\cakephp\\bake\\src\\Template\\Bake\\Model\\table.twig", "C:\\xampp\\htdocs\\cooperative\\vendor\\cakephp\\bake\\src\\Template\\Bake\\Model\\table.twig");
    }
}
