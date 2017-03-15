<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Course Controller
 *
 * @property \App\Model\Table\CourseTable $Course
 */
class CourseController extends AppController
{

    public function upload()
    {
        $course = $this->Course->newEntity();
        $course->summer = $this->request->getData('Summer');
        $course->fall = $this->request->getData('Fall');
        $course->winter = $this->request->getData('Winter');
        $course->spring = $this->request->getData('Spring');
        $course->units = $this->request->getData('Units');
        $course->name = $this->request->getData('Name');
        $result=$this->Course->save($course);
        print_r($result->id);die();
    }

    public function linkuploads($id)
    {
        $this->Course->deleteAssociations($id);
        $prereqs = [];
        foreach ($this->request->getData('Prerequisites') as $prereq) {
            array_push($prereqs, $this->find('first')->where(['Course.name LIKE' => "$prereq:%"])->id);
        }
        $concurs = [];
        foreach ($this->request->getData('Concurrents') as $concur) {
            array_push($concurs, $this->find('first')->where(['Course.name LIKE' => "$concur:%"])->id);
        }
        $this->Course->savePrerequisites($id, $prereqs);
        $this->Course->saveConcurrents($id, $concurs);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $course = $this->paginate($this->Course->find()->contain(['Concurrents', 'Prerequisites']));

        $this->set(compact('course'));
        $this->set('_serialize', ['course']);
    }

    /**
     * View method
     *
     * @param string|null $id Course id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $course = $this->Course->get($id, [
            'contain' => ['Concurrents', 'Prerequisites']
        ]);

        $this->set('course', $course);
        $this->set('_serialize', ['course']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $course = $this->Course->newEntity();
        $this->set('coursenames', TableRegistry::get('course')->find('list'));
        if ($this->request->is('post')) {
            $course = $this->Course->patchEntity($course, $this->request->data);
            if($course->units > 0) {
                if ($result=$this->Course->save($course)) {
                    $this->Flash->success(__('The course has been saved.'));
                    $this->Course->saveConcurrents($result->id, $this->request->data["concurrents"]);
                    $this->Course->savePrerequisites($result->id, $this->request->data["prerequisites"]);
                    return $this->redirect(['action' => 'index']);
                 }
            } else {
                $this->Flash->error(__('The units must be greater than 0.'));
            }
            $this->Flash->error(__('The course could not be saved. Please, try again.'));
        }
        $this->set(compact('course'));
        $this->set('_serialize', ['course']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Course id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $course = $this->Course->get($id, [
            'contain' => ['Prerequisites', 'Concurrents']
        ]);
        $courseconcurrents = [];
        foreach ($course['concurrents'] as $concurrent) {
            array_push($courseconcurrents, $concurrent->id);
        }
        $courseprerequisites = [];
        foreach ($course['prerequisites'] as $prerequisite) {
            array_push($courseprerequisites, $prerequisite->id);
        }
        $this->set('coursenames', $this->Course->find('list')->where(["id !=" => $course->id]));
        $this->set('courseprerequisites', $courseprerequisites);
        $this->set('courseconcurrents', $courseconcurrents);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $course = $this->Course->patchEntity($course, $this->request->data);
            if($course->units > 0) {
                 if ($this->Course->save($course)) {
                    $this->Course->deleteAssociations($id);
                    $courseconcurrents = $this->request->data["concurrents"];
                    $courseprerequisites = $this->request->data["prerequisites"];
                    if (is_array($courseconcurrents)) $this->Course->saveConcurrents($id, $courseconcurrents);
                    if (is_array($courseprerequisites)) $this->Course->savePrerequisites($id, $courseprerequisites);
                        $this->Flash->success(__('The course has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            } else {
                $this->Flash->error(__('The units must be greater than 0.'));
            }
            $this->Flash->error(__('The course could not be saved. Please, try again.'));
        }
        $this->set(compact('course'));
        $this->set('_serialize', ['course']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Course id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $course = $this->Course->get($id);
        if ($this->Course->delete($course)) {
            $this->Course->deleteAssociations($id);
            $this->Flash->success(__('The course has been deleted.'));
        } else {
            $this->Flash->error(__('The course could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }




    public function deleteAll()
    {
        $this->request->allowMethod(['post', 'deleteAll']);

        if($this->Course->deleteAll(null)) {
            $this->Flash->success(__('All course have been deleted.'));
        } else {
            $this->Flash->error(__('You have no course to delete.'));
        }
        return $this->redirect(['action' => 'index']);
    }


    public function userinfo() {
        $this->set('coursenames', TableRegistry::get('course')->find('list'));
    }

    public function process() {
        $timeout = 32;
        $minPasses = 2;
        $myTermLimit = intval($this->request->getData('TermLimit'));
        $mySubset = array_map('intval', $this->request->getData('Subset'));
        $myFall = $this->request->getData('fall') == '1';
        $myWinter = $this->request->getData('winter') == '1';
        $mySpring = $this->request->getData('spring') == '1';
        $mySummer = $this->request->getData('summer') == '1';
        if (!($myFall || $myWinter || $mySpring || $mySummer)) {
            $this->Flash->error(__('You must select at least one quarter!'));
            return $this->redirect(['action' => 'userinfo']);
        }
        if (empty($mySubset)) {
            $this->Flash->error(__('You must select at least one class!'));
            return $this->redirect(['action' => 'userinfo']);
        }
        $myTermIndex = 0;
        $taken = [];
        $tobetaken = [];
        $schedule = [];
        $curterm = [];
        $passes = 0;
        $curtermlimit = $myTermLimit;
        $maxPasses = $minPasses + count($mySubset);
        while (!empty($mySubset) && $myTermIndex <= $timeout) {
            //debug($taken);
            //debug($myTermIndex);
            foreach ($mySubset as $index => $courseid) {
                if (in_array($courseid, $tobetaken) || in_array($courseid, $taken)) {
                    //debug('inarray');
                    unset($mySubset[$index]);
                    continue;
                }
                $course = $this->Course->get($courseid, ['contain' => ['Prerequisites', 'Concurrents.Prerequisites']]);
                $cost = $course->units;
                $takeable = true;
                if ($course->concurrents != null) {
                    foreach ($course->concurrents as $concur) {
                        $cost += $concur->units;
                        if ($concur->prerequisites != null) {
                            foreach ($concur->prerequisites as $prereq) {
                                if (!in_array($prereq->id, $taken)) {
                                    $takeable = false;
                                    //debug('prereq '.$prereq->id.' for '.$courseid.' not satisfied');
                                    if (!in_array($prereq->id, $mySubset) && !in_array($prereq->id, $tobetaken)) {
                                        array_push($mySubset, $prereq->id);
                                        $passes = 0;
                                    }
                                }
                            }
                        }
                    }
                }
                if ($cost > $myTermLimit) {
                    print_r('Course '.$course->name.' requires enrollment in more than '.$myTermLimit.' units!'); die();
                }
                if ($cost > $curtermlimit) {
                    $takeable = false;
                    //debug('not enough units in term');
                }
                if ($course->prerequisites != null) {
                    foreach ($course->prerequisites as $prereq) {
                        if (!in_array($prereq->id, $taken)) {
                            $takeable = false;
                            //debug('prereq '.$prereq->id.' for '.$courseid.' not satisfied');
                            if (!in_array($prereq->id, $mySubset) && !in_array($prereq->id, $tobetaken)) {
                                array_push($mySubset, $prereq->id);
                                $passes = 0;
                            }
                        }
                    }
                }
                if (!($course->fall || $course->winter || $course->spring || $course->summer)) {
                } elseif ($myTermIndex % 4 == 0) {
                    if (!$course->fall || !$myFall) $takeable = false;
                } elseif ($myTermIndex % 4 == 1) {
                    if (!$course->winter || !$myWinter) $takeable = false;
                } elseif ($myTermIndex % 4 == 2) {
                    if (!$course->spring || !$mySpring) $takeable = false;
                } elseif ($myTermIndex % 4 == 3) {
                    if (!$course->summer || !$mySummer) $takeable = false;
                }
                if ($takeable) {
                    //debug($courseid.' is takeable');
                    array_push($curterm, $course);
                    array_push($tobetaken, $course->id);
                    if ($course->concurrents != null) {
                        foreach ($course->concurrents as $concur) {
                            array_push($curterm, $concur);
                            array_push($tobetaken, $concur->id);
                        }
                    }
                    $curtermlimit -= $cost;
                    $passes = 0;
                } else {
                    $maxPasses = $minPasses + count($mySubset);
                    $passes++;
                }
                if ($passes >= $maxPasses) {
                    $passes = 0;
                    $schedule[$myTermIndex] = $curterm;
                    $curterm = [];
                    $myTermIndex++;
                    $curtermlimit = $myTermLimit;
                    $taken = array_merge($taken, $tobetaken);
                    $tobetaken = [];
                }
            }
        }
        $schedule[$myTermIndex] = $curterm;
        $myTermIndex++;
        if ($myTermIndex > $timeout) $this->Flash->error(__('The courseload you have chosen cannot be completed. Either a cycle exists, you have chosen too many courses, or there are too many prerequisites.'));
        $this->set(['myTerms'=>$schedule, 'myTermIndex'=>$myTermIndex]);
    }
}
