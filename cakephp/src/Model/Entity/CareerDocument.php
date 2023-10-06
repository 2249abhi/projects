<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CareerDocument Entity
 *
 * @property int $id
 * @property int $career_id
 * @property int $language_id
 * @property string $culture
 * @property int $document_type
 * @property string|null $documents
 *
 * @property \App\Model\Entity\Career $career
 * @property \App\Model\Entity\Language $language
 */
class CareerDocument extends Entity
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
        'career_id' => true,
        'language_id' => true,
        'culture' => true,
        'document_type' => true,
        'documents' => true,
        'career' => true,
        'language' => true
    ];
}
