<?php
namespace CustomFields\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomValue Entity
 *
 * @property int $id
 * @property int $custom_field_id
 * @property string $customized_type
 * @property int $customized_id
 * @property string $value
 * @property bool $active
 *
 * @property \CustomFields\Model\Entity\CustomField $custom_field
 * @property \CustomFields\Model\Entity\Customized $customized
 */
class CustomValue extends Entity
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
        'customized_type' => true,
        'customized_id' => true,
        'value' => true,
        'active' => true,
        'custom_field' => true,
        'customized' => true
    ];
}
