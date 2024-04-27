<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AnnouncementTranslation Entity
 *
 * @property int $id
 * @property int $announcement_id
 * @property int $language_id
 * @property string $culture
 * @property string $title
 * @property string|null $description
 * @property string|null $filename
 *
 * @property \App\Model\Entity\Announcement $announcement
 * @property \App\Model\Entity\Language $language
 */
class AnnouncementTranslation extends Entity
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
        'announcement_id' => true,
        'language_id' => true,
        'culture' => true,
        'title' => true,
        'description' => true,
        'filename' => true,
        'announcement' => true,
        'language' => true
    ];
}
