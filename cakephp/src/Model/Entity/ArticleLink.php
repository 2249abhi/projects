<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ArticleLink Entity
 *
 * @property int $id
 * @property int $article_id
 * @property string|null $link_type
 * @property int|null $object_id
 * @property string|null $custom_link
 * @property string|null $link_title
 * @property string $redirection
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $updated
 *
 * @property \App\Model\Entity\Article $article
 */
class ArticleLink extends Entity
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
        'article_id' => true,
        'link_type' => true,
        'object_id' => true,
        'custom_link' => true,
        'internal_link' => true,
        'link_title' => true,
        'redirection' => true,
        'created' => true,
        'updated' => true,
        'article' => true
    ];
}
