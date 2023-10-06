<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;

/**
 * Article Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $slug
 * @property string|null $excerpt
 * @property string $content
 * @property string|null $url
 * @property bool $is_home
 * @property int|null $sort_order
 * @property bool $status
 * @property \Cake\I18n\FrozenTime|null $created_at
 * @property \Cake\I18n\FrozenTime|null $modified_at
 *
 * @property \App\Model\Entity\ArticleTranslation[] $article_translations
 */
class Article extends Entity
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
        'user_id' => true,
        'title' => true,
        'slug' => true,
        'meta_title' => true,
        'meta_keywords' => true,
        'meta_description' => true,
        'excerpt' => true,
        'content' => true,
        'cloud_tags' => true,
        'url' => true,
        'is_home' => true,
        'sort_order' => true,
        'header_image' => true,
        'status' => true,
        'created_at' => true,
        'modified_at' => true,
        'article_translations' => true
    ];


    protected function _getUrl($url)
    {
        $url = trim($url);
        if (!empty($url)) {
            return strtolower(Text::slug($url));
        } else {
            return null;
        }
    }
    protected function _setUrl($url)
    {
        $url = trim($url);
        if (!empty($url)) {
            return strtolower(Text::slug($url));
        } else {
            return null;
        }
    }

    protected function _getStatus($status)
    {
        (int) $userId = $_SESSION['Auth']['User']['role_id'];
        if ($userId === 1) {
            $status = 1;
        } else {
            $status = 0;
        }
        return $status;
    }
}
