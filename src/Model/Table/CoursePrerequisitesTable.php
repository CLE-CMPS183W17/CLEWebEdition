<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CoursePrerequisites Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Courses
 * @property \Cake\ORM\Association\BelongsTo $Prerequisites
 *
 * @method \App\Model\Entity\CoursePrerequisite get($primaryKey, $options = [])
 * @method \App\Model\Entity\CoursePrerequisite newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CoursePrerequisite[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CoursePrerequisite|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CoursePrerequisite patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CoursePrerequisite[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CoursePrerequisite findOrCreate($search, callable $callback = null, $options = [])
 */
class CoursePrerequisitesTable extends Table
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

        $this->table('course_prerequisites');

        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id'
        ]);
        $this->belongsTo('Prerequisites', [
            'foreignKey' => 'prerequisite_id'
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
            ->requirePresence('id', 'create')
            ->notEmpty('id');

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
        $rules->add($rules->existsIn(['course_id'], 'Courses'));
        $rules->add($rules->existsIn(['prerequisite_id'], 'Prerequisites'));

        return $rules;
    }
}
