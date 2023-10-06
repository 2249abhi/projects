<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TenderDocument Entity
 *
 * @property int $id
 * @property int $tender_id
 * @property int $language_id
 * @property string $culture
 * @property int $document_type
 * @property string|null $documents
 * @property bool $status
 *
 * @property \App\Model\Entity\Tender $tender
 * @property \App\Model\Entity\Language $language
 */
class TenderDocument extends Entity
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
        'tender_id' => true,
        'language_id' => true,
        'culture' => true,
        'document_type' => true,
        'documents' => true,
        'status' => true,
        'tender' => true,
        'language' => true
    ];
}
