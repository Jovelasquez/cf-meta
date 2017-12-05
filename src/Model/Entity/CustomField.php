<?php
namespace CustomFields\Model\Entity;

use Cake\ORM\Entity;

/**
 * CustomField Entity
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string $field_format
 * @property string $possible_values
 * @property string $default_values
 * @property string $regexp
 * @property string $options
 * @property int $position
 * @property bool $is_required
 * @property bool $is_printable
 * @property int $parent_id
 * @property int $lft
 * @property int $rght
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \CustomFields\Model\Entity\ParentCustomField $parent_custom_field
 * @property \CustomFields\Model\Entity\CustomFieldModel[] $custom_field_models
 * @property \CustomFields\Model\Entity\ChildCustomField[] $child_custom_fields
 * @property \CustomFields\Model\Entity\CustomValue[] $custom_values
 * @property \CustomFields\Model\Entity\Phinxlog[] $phinxlog
 */
class CustomField extends Entity
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
        'type' => true,
        'name' => true,
        'field_format' => true,
        'possible_values' => true,
        'default_values' => true,
        'regexp' => true,
        'options' => true,
        'position' => true,
        'is_required' => true,
        'is_printable' => true,
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'created' => true,
        'modified' => true,
        'parent_custom_field' => true,
        'custom_field_models' => true,
        'child_custom_fields' => true,
        'custom_values' => true,
        'phinxlog' => true
    ];
}
