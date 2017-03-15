<?php
namespace App\Model\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
/**
 * Course Model
 *
 * @method \App\Model\Entity\Course get($primaryKey, $options = [])
 * @method \App\Model\Entity\Course newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Course[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Course|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Course patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Course[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Course findOrCreate($search, callable $callback = null, $options = [])
 */
class CourseTable extends Table
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
        $this->table('course');
        $this->displayField('name');
        $this->primaryKey('id');
    $this->belongsToMany('Prerequisites', [
        'className' => 'Course',
        'through' => 'course_prerequisites',
        'foreignKey' => 'from_id',
        'targetForeignKey' => 'to_id'
    ]);
    $this->belongsToMany('Concurrents', [
        'className' => 'Course',
        'through' => 'course_concurrents',
	'foreignKey' => 'from_id',
	'targetForeignKey' => 'to_id',
    ]);
    $this->belongsToMany('Dependents', [
        'className' => 'Course',
        'through' => 'course_prerequisites',
        'foreignKey' => 'to_id',
        'targetForeignKey' => 'from_id'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name')
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);
        $validator
            ->decimal('units')
            ->requirePresence('units', 'create')
            ->notEmpty('units');
        $validator
            ->boolean('summer')
            ->allowEmpty('summer');
        $validator
            ->boolean('fall')
            ->allowEmpty('fall');
        $validator
            ->boolean('winter')
            ->allowEmpty('winter');
        $validator
            ->boolean('spring')
            ->allowEmpty('spring');
        return $validator;
    }
    public function saveConcurrents($id, $concurs) {
        if(!is_array($concurs)) {
            return;
        }
        $concurrents = TableRegistry::get('CourseConcurrents');
        //var_dump($concurs);die();
        foreach ($concurs as $concur) {
            $query = $concurrents->query();
            $query->insert(['from_id', 'to_id'])->values(['from_id' => $id, 'to_id'=>(int)$concur])->execute();
        }
    }
    public function savePrerequisites($id, $prereqs) {
        if(!is_array($prereqs)) {
            return;
        }
        $prerequisites = TableRegistry::get('CoursePrerequisites');
        foreach ($prereqs as $prereq) {
            $query = $prerequisites->query();
            $query->insert(['from_id', 'to_id'])->values(['from_id' => $id, 'to_id'=>(int)$prereq])->execute();
        }
    }
    public function deleteAssociations($id) {
        $concurrents = TableRegistry::get('CourseConcurrents');
        $prerequisites = TableRegistry::get('CoursePrerequisites');
        $queryC = $concurrents->query();
        $queryC->delete()
            ->where(['from_id'=>$id])
            ->execute();
        $queryC = $concurrents->query();
        $queryC->delete()
            ->where(['to_id'=>$id])
            ->execute();
        $queryP = $prerequisites->query();
        $queryP->delete()
            ->where(['from_id'=>$id])
            ->execute();
        $queryP = $prerequisites->query();
        $queryP->delete()
            ->where(['to_id'=>$id])
            ->execute();
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
        $rules->add($rules->isUnique(['name']));
        return $rules;
    }
}
