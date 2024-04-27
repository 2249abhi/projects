<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ArticleImage Entity
 *
 * @property int $id
 * @property int|null $article_id
 * @property int $user_id
 * @property string $filename
 * @property string $filemime
 * @property int $filesize
 * @property int|null $weight
 * @property string|null $description
 * @property bool|null $status
 * @property int $created
 * @property int $changed
 *
 * @property \App\Model\Entity\Article $article
 * @property \App\Model\Entity\User $user
 */
class ArticleImage extends Entity
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
        'user_id' => true,
        'filename' => true,
        'filemime' => true,
        'filesize' => true,
        'weight' => true,
        'description' => true,
        'status' => true,
        'created' => true,
        'changed' => true,
        'article' => true,
        'user' => true
    ];
}
