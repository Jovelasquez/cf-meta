<?php
namespace CustomFields\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CustomFieldModels Model
 *
 * @property \CustomFields\Model\Table\CustomFieldsTable|\Cake\ORM\Association\BelongsTo $CustomFields
 *
 * @method \CustomFields\Model\Entity\CustomFieldModel get($primaryKey, $options = [])
 * @method \CustomFields\Model\Entity\CustomFieldModel newEntity($data = null, array $options = [])
 * @method \CustomFields\Model\Entity\CustomFieldModel[] newEntities(array $data, array $options = [])
 * @method \CustomFields\Model\Entity\CustomFieldModel|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \CustomFields\Model\Entity\CustomFieldModel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \CustomFields\Model\Entity\CustomFieldModel[] patchEntities($entities, array $data, array $options = [])
 * @method \CustomFields\Model\Entity\CustomFieldModel findOrCreate($search, callable $callback = null, $options = [])
 */
class CustomFieldModelsTable extends Table
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

        $this->setTable('custom_field_models');

        $this->belongsTo('CustomFields', [
            'foreignKey' => 'custom_field_id',
            'joinType' => 'INNER',
            'className' => 'CustomFields.CustomFields'
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
            ->scalar('model')
            ->requirePresence('model', 'create')
            ->notEmpty('model');

        $validator
            ->integer('foreign_key')
            ->requirePresence('foreign_key', 'create')
            ->notEmpty('foreign_key');

        $validator
            ->scalar('options')
            ->allowEmpty('options');

        $validator
            ->boolean('active')
            ->requirePresence('active', 'create')
            ->notEmpty('active');

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
        $rules->add($rules->existsIn(['custom_field_id'], 'CustomFields'));

        return $rules;
    }
}
