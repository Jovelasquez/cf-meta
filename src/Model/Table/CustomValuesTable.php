<?php
namespace CustomFields\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CustomValues Model
 *
 * @property \CustomFields\Model\Table\CustomFieldsTable|\Cake\ORM\Association\BelongsTo $CustomFields
 * @property \CustomFields\Model\Table\CustomizedsTable|\Cake\ORM\Association\BelongsTo $Customizeds
 *
 * @method \CustomFields\Model\Entity\CustomValue get($primaryKey, $options = [])
 * @method \CustomFields\Model\Entity\CustomValue newEntity($data = null, array $options = [])
 * @method \CustomFields\Model\Entity\CustomValue[] newEntities(array $data, array $options = [])
 * @method \CustomFields\Model\Entity\CustomValue|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \CustomFields\Model\Entity\CustomValue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \CustomFields\Model\Entity\CustomValue[] patchEntities($entities, array $data, array $options = [])
 * @method \CustomFields\Model\Entity\CustomValue findOrCreate($search, callable $callback = null, $options = [])
 */
class CustomValuesTable extends Table
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

        $this->setTable('custom_values');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('CustomFields', [
            'foreignKey' => 'custom_field_id',
            'joinType' => 'INNER',
            'className' => 'CustomFields.CustomFields'
        ]);
        $this->belongsTo('Customizeds', [
            'foreignKey' => 'customized_id',
            'joinType' => 'INNER',
            'className' => 'CustomFields.Customizeds'
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
            ->scalar('customized_type')
            ->requirePresence('customized_type', 'create')
            ->notEmpty('customized_type');

        $validator
            ->scalar('value')
            ->allowEmpty('value');

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
        $rules->add($rules->existsIn(['customized_id'], 'Customizeds'));

        return $rules;
    }
}
