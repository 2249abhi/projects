<?php
namespace App\View\Helper;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\View\Helper;
use Cake\View\View;

class MenuHelper extends Helper
{
    public $helpers = ['Html'];

    private $currentUrl = '';

    private $MenuRegions;

    private $menuType = [
        'custom' => 'Custom',
        'object' => 'Article',
        'internal' => 'Internal',
    ];

    public function initialize(array $config)
    {
        // Plugin table
        $this->MenuRegions = TableRegistry::get('MenuRegions');
        $this->currentUrl  = Router::url(null, ['_full' => true]);
    }

    public function get($menu_region_slug)
    {
        $language = (Configure::check('language')) ? Configure::read('language.culture') . '__' : '';
        $cacheKey = 'menu__' . $language . $menu_region_slug;
        $menus    = Cache::read($cacheKey, 'silvermenu');
        if ($menus !== false) {
            return $menus;
        }
        $menuRegions = $this->MenuRegions->findBySlug($menu_region_slug)
            ->contain([
                'Menus' => function ($q) {
                    $q->find('threaded');
                    $q->select([
                        'Menus.id',
                        'Menus.parent_id',
                        'Menus.menu_region_id',
                        'menu_title' => "IF(MenuTranslation.menu_title != '',MenuTranslation.menu_title,Menus.menu_title)",
                        'Menus.menu_type',
                        'Menus.custom_link',
                        'Menus.internal_link',
                        'Menus.object_type',
                        'Menus.object_id',
                        'Menus.module_id',
                        'Menus.redirection',
                        'Menus.sort_order',
                        'Menus.status',
                    ])->contain([
                        'Articles'        => function ($q) {
                            $q->select(['id', 'url']);
                            $q->where(['Articles.status' => 1]);
                            return $q;
                        },
                        'MenuTranslation' => function ($q) {
                            if (Configure::check('language')) {
                                $q->where(['MenuTranslation.culture' => Configure::read('language.culture')]);
                            } else {
                                $q->where(['MenuTranslation.language_id' => 0]);
                            }
                            return $q;
                        },
                    ])->where([
                        'Menus.status' => 1,
                    ])->order([
                        /*'Menus.lft' => 'ASC', */ 'Menus.sort_order' => 'ASC',
                    ]);
                    return $q;
                },
            ])->first();
        $menus = [];
        if (!empty($menuRegions) && $menuRegions->has('menus')) {
            $menus = $this->_FilterMenusTree($menuRegions->menus);
        }
        Cache::write($cacheKey, $menus, 'silvermenu');
        return $menus;
    }

    public function render($menu_region_slug, $options = array())
    {
        $options = [
            'selectedClass' => 'active',
            'item'        => [
                'tag'   => 'li',
                'class' => '',
                'selectedClass' => 'active',
            ],
            'submenu'     => [
                'tag'   => 'ul',
                'class' => 'nav-submenu',
            ],
        ] + $options;

        $menus     = $this->get($menu_region_slug);
        $_menu_str = $this->_MenuIterator($menus, $options);

        return $_menu_str;
    }

    private function _MenuIterator($menus, $options)
    {
        $_menu = '';
        if (!empty($menus)) {
            foreach ($menus as $menu) {
                $iOption = $options;
                $selected = false;
                if ($this->currentUrl == $menu['url']) {
                    $selected = true;
                    if (isset($menu['options']['class'])) {
                        $menu['options']['class'] = (!is_array($menu['options']['class'])) ? explode(' ', $menu['options']['class']) : $menu['options']['class'];
                    } else {
                        $menu['options']['class'] = [];
                    }
                    $menu['options']['class'][] = $iOption['selectedClass'];
                }
                $_submenu = '';
                if (!empty($menu['submenu'])) {
                    $_submenu .= '<' . $iOption['submenu']['tag'] . ' class="' . $iOption['submenu']['class'] . '">';
                    $_submenu .= $this->_MenuIterator($menu['submenu'], $options);
                    $_submenu .= '</' . $iOption['submenu']['tag'] . '>';
                }

                if ($selected) {
                   $iOption['item']['class'] = $iOption['item']['selectedClass'];
                }
                $_menu .= '<' . $iOption['item']['tag'] . ' class="startup ' . $iOption['item']['class'] . '">';
                $_menu .= $this->Html->link($menu['title'], $menu['url'], $menu['options']);
                $_menu .= $_submenu;
                $_menu .= '</' . $iOption['item']['tag'] . '>';
            }
        }
        return $_menu;
    }
    private function _FilterMenusTree($menus, $level = 0)
    {
        $pushMenus = [];
        if (!empty($menus)) {
            $i = 0;
            foreach ($menus as $menu) {
                $gMenu = $this->_GenerateMenu($menu);
                if (!empty($gMenu)) {
                    $pushMenus[$i] = $gMenu;
                    if (!empty($menu['children'])) {
                        $pushMenus[$i]['submenu'] = $this->_FilterMenusTree($menu['children'], ($level + 1));
                    }
                }
                $i++;
            }
        }
        return $pushMenus;
    }

    private function _GenerateMenu($menu)
    {
        $isShow  = false;
        $link    = '';
        $options = [];
        if ($menu->redirection == 'new-window') {
            $options['target']['target'] = '_blank';
        }
        if ($menu->object_type == 'article' && $menu->has('article')) {
            $isShow  = true;
			$lOption = [
				'prfix'      => false,
				'controller' => 'Articles',
				'action'     => 'page',
				'id'         => $menu->article->id,
			];
			$link = Router::url($lOption, ['pass' => ['id'], '_full' => true]);
        } else if (!empty($menu->custom_link)) {
            $isShow = true;
            if (!in_array($menu->custom_link, ['#'])) {
                $link = Router::url($menu->custom_link, ['_full' => true]);
            } else {
                $link = $menu->custom_link;
            }
        } else if ($menu->menu_type == 'internal') {
            $isShow = true;
            $lbption = ['prfix'=> false,'controller'=>$menu->internal_link];
            $link = Router::url($lbption, ['_full' => true]);
        }
        
        if ($isShow) {
            return [
                'id'      => $menu->id,
                'title'   => $menu->menu_title,
                'type'    => $this->menuType[$menu->menu_type],
                'url'     => $link,
                'options' => $options,
                'html'    => $this->Html->link($menu->menu_title, $link, $options),
            ];
        }
        return [];
    }

    public function printMenu($menus, $is_sub = false)
    {
        $menuStr = '';
        if (!empty($menus)) {
            foreach ($menus as $menu) {
                $subMenuStr   = '';
                $subMenuClass = 'nav-item ';
                if (!empty($menu['submenu'])) {
                    $subMenuClass .= 'submenu dropdown';
                    $subMenuStr .= '<div class="dropdown-menu nav-submenu">';
                        $subMenuStr .= '<ul class="nav flex-column pl-3 nav-submenu">';
                        $subMenuStr .= $this->printMenu($menu['submenu'], true);
                        $subMenuStr .= '</ul>';
                    $subMenuStr .= '</div>';
                    $menu['options']["data-toggle"] = "dropdown";
                    $menu['options']["aria-haspopup"] = "true";
                    $menu['options']["aria-expanded"] = "false";
                }
                $menuStr .= '<li class="' . $subMenuClass . '">';
                $menuStr .= $this->Html->link($menu['title'], $menu['url'], $menu['options']);
                $menuStr .= $subMenuStr;
                $menuStr .= '</li>';
            }
        }
        return $menuStr;
    }
}
