<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Post Entity
 *
 * @property int $id
 * @property string $title
 * @property int $object_id
 * @property int|null $parent_id
 * @property int|null $lft
 * @property int|null $rght
 * @property string|null $subtitle
 * @property string $content
 * @property string|null $post_image
 * @property int $user_id
 * @property bool $status
 * @property \Cake\I18n\FrozenTime|null $updated
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Object $object
 * @property \App\Model\Entity\ParentPost $parent_post
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\ChildPost[] $child_posts
 */
class Post extends Entity
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
        'title' => true,
        'article_id' => true,
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'subtitle' => true,
        'content' => true,
        'post_image' => true,
        'user_id' => true,
        'status' => true,
        'updated' => true,
        'created' => true,
        'parent_post' => true,
        'user' => true,
        'child_posts' => true
    ];
}
