<?php
namespace CustomFields\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;

/**
 * CustomFields Model
 *
 * @property \CustomFields\Model\Table\CustomFieldsTable|\Cake\ORM\Association\BelongsTo $ParentCustomFields
 * @property \CustomFields\Model\Table\CustomFieldModelsTable|\Cake\ORM\Association\HasMany $CustomFieldModels
 * @property \CustomFields\Model\Table\CustomFieldsTable|\Cake\ORM\Association\HasMany $ChildCustomFields
 * @property \CustomFields\Model\Table\CustomValuesTable|\Cake\ORM\Association\HasMany $CustomValues
 *
 * @method \CustomFields\Model\Entity\CustomField get($primaryKey, $options = [])
 * @method \CustomFields\Model\Entity\CustomField newEntity($data = null, array $options = [])
 * @method \CustomFields\Model\Entity\CustomField[] newEntities(array $data, array $options = [])
 * @method \CustomFields\Model\Entity\CustomField|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \CustomFields\Model\Entity\CustomField patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \CustomFields\Model\Entity\CustomField[] patchEntities($entities, array $data, array $options = [])
 * @method \CustomFields\Model\Entity\CustomField findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class CustomFieldsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('custom_fields');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree');
        $this->addBehavior('CustomFields.CustomField');

        $this->belongsTo('ParentCustomFields', [
            'className' => 'CustomFields.CustomFields',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('CustomFieldModels', [
            'foreignKey' => 'custom_field_id',
            'className' => 'CustomFields.CustomFieldModels'
        ]);
        $this->hasMany('ChildCustomFields', [
            'className' => 'CustomFields.CustomFields',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('CustomValues', [
            'foreignKey' => 'custom_field_id',
            'className' => 'CustomFields.CustomValues'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('type')
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('field_format')
            ->allowEmpty('field_format');

        $validator
            ->scalar('possible_values')
            ->allowEmpty('possible_values');

        $validator
            ->scalar('default_values')
            ->allowEmpty('default_values');

        $validator
            ->scalar('regexp')
            ->allowEmpty('regexp');

        $validator
            ->scalar('options')
            ->allowEmpty('options');

        $validator
            ->integer('position')
            ->allowEmpty('position');

        $validator
            ->boolean('is_required')
            ->requirePresence('is_required', 'create')
            ->notEmpty('is_required');

        $validator
            ->boolean('is_printable')
            ->requirePresence('is_printable', 'create')
            ->notEmpty('is_printable');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['parent_id'], 'ParentCustomFields'));

        return $rules;
    }


    /**
     * Configuration Fields Finder and endpoint formatter.
     *
     * @param \Cake\ORM\Query $query Query object.
     * @param array $options Query options.
     * @return \Cake\ORM\Query The query builder.
     */
    public function findConfigField(Query $query, array $options)
    {
        return $query
            ->select($this->getSchema()->columns())
            ->join([
                'CustomFieldModels' => [
                    'table' => 'custom_field_models',
                    'type' => 'INNER',
                    'conditions' => [
                        'CustomFieldModels.custom_field_id = CustomFields.id',
                        'CustomFieldModels.model' => $options['customModel'],
                        'CustomFieldModels.foreign_key' => $options['customModelKey'],
                    ]
                ],
            ])
            ->select(['CustomFieldModels.model','CustomFieldModels.foreign_key', 'CustomFieldModels.options'])
            ->formatResults(function ($results) {
                return $results->map(function ($row) {
                    if ($row === null) {
                        return $row;
                    }

                    return [
                        'id' => Hash::get($row, 'id'),
                        'model' => Hash::get($row, 'CustomFieldModels.model'),
                        'type' => Hash::get($row, 'type'),
                        'name' => Hash::get($row, 'name'),
                        'field_format' => Hash::get($row, 'field_format'),
                        'possible_values' => Hash::get($row, 'possible_values'),
                        'default_values' => Hash::get($row, 'default_values'),
                        'regexp' => Hash::get($row, 'regexp'),
                        'options' => Hash::get($row, 'options'),
                        'position' => Hash::get($row, 'position'),
                        'is_required' => Hash::get($row, 'is_required'),
                    ];
                });
            });
    }

    /**
     * Customs Values Finder and endpoint formatter.
     *
     * @param \Cake\ORM\Query $query Query object.
     * @param array $options Query options.
     * @return \Cake\ORM\Query The query builder.
     */
    public function findCustomValues(Query $query, array $options)
    {
        return $query
            ->select($this->getSchema()->columns())
            ->join([
                'CustomValues' => [
                    'table' => 'custom_values',
                    'type' => 'INNER',
                    'conditions' => [
                        'CustomValues.custom_field_id = CustomFields.id',
                        'CustomValues.customized_type' => $options['customModel'],
                        'CustomValues.customized_id' => $options['customModelKey'],
                    ]
                ],
            ])
            ->select(['CustomValues.customized_type','CustomValues.customized_id','CustomValues.value'])
            ->formatResults(function ($results) use ($options) {
                return $results->map(function ($row) use ($options) {
                    if ($row === null) {
                        return $row;
                    }

                    return [
                        'id' => Hash::get($row, 'id'),
                        'model' => Hash::get($row, 'CustomFieldModels.model'),
                        'config' => Hash::get($row, 'CustomFieldModels.options'),
                        'value' => Hash::get($row, 'CustomValues.value'),
                        'type' => Hash::get($row, 'type'),
                        'name' => Hash::get($row, 'name'),
                        'field_format' => Hash::get($row, 'field_format'),
                        'possible_values' => Hash::get($row, 'possible_values'),
                        'default_values' => Hash::get($row, 'default_values'),
                        'options' => Hash::get($row, 'options'),
                        'position' => Hash::get($row, 'position'),
                        'is_printable' => Hash::get($row, 'is_printable'),
                    ];
                });
            });
    }    
}
