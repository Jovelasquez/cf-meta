<?php
namespace CustomFields\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomFieldModel Entity
 *
 * @property int $custom_field_id
 * @property string $model
 * @property int $foreign_key
 * @property string $options
 * @property bool $active
 *
 * @property \CustomFields\Model\Entity\CustomField $custom_field
 */
class CustomFieldModel extends Entity
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
        'custom_field_id' => true,
        'model' => true,
        'foreign_key' => true,
        'options' => true,
        'active' => true,
        'custom_field' => true
    ];
}
