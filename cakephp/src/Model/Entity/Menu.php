<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Menu Entity
 *
 * @property int $id
 * @property int $menu_region_id
 * @property int|null $parent_id
 * @property int|null $lft
 * @property int|null $rght
 * @property string $menu_title
 * @property string $menu_type
 * @property string|null $custom_link
 * @property string|null $object_type
 * @property int $object_id
 * @property int $module_id
 * @property string $redirection
 * @property int $sort_order
 * @property bool $status
 *
 * @property \App\Model\Entity\MenuRegion $menu_region
 * @property \App\Model\Entity\ParentMenu $parent_menu
 * @property \App\Model\Entity\Object $object
 * @property \App\Model\Entity\Module $module
 * @property \App\Model\Entity\MenuTranslation[] $menu_translations
 * @property \App\Model\Entity\ChildMenu[] $child_menus
 */
class Menu extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'menu_region_id' => true,
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'menu_title' => true,
        'menu_type' => true,
        'custom_link' => true,
        'internal_link' => true,
        'object_type' => true,
        'object_id' => true,
        'module_id' => true,
        'redirection' => true,
        'sort_order' => true,
        'status' => true,
        'menu_region' => true,
        'parent_menu' => true,
        'object' => true,
        'module' => true,
        'menu_translations' => true,
        'child_menus' => true
    ];
}
