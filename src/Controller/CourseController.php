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

    public function processTerms() {
        $myTermLimit = 15;
        $amountOfTermsInYear = 3;
        $myTermIndex = 0;
        // $hasSummerCourses = false;
        // if($this->summerCoursesExist()) {
        //     $hasSummerCourses = true;
        // }

        $rawCourseList = TableRegistry::get('course')->find()->contain(['Concurrents', 'Prerequisites']);
        $yearCount = 1;
        $myTerms = [];
        // array_push($myTerms, []);
        // var_dump($myTerms);die();

        // $course = TableRegistry::get('course')->find()->contain(['Concurrents', 'Prerequisites'])
        // ->first();
        //
        // var_dump($course->concurrents[0]->units);die();

        while(!$this->hasFullyUsedCourses($rawCourseList)) {
            if($myTermIndex != 0 && $myTermIndex % $amountOfTermsInYear == 0) {
                $yearCount++;
            }
            $myCurrentTerm = [];
            $myTermUnits = 0;

            foreach($rawCourseList as $myCourse) {
                if($myCourse->isused) {
                    continue;
                }

                if(!empty($myCourse->prerequisites)) {
                    
                } else if(!empty($myCourse->concurrents)) {
                    $hasUnsatPrereqs = $this->checkForPrereqs($myCourse->concurrents);

                    if($hasUnsatPrereqs) {
                        $myCourse->nexttermindex++;
                        $myCourse = $this->getPrereq($myCourse);
                    } else {
                        $myConcurUnits = 0;
                        foreach($myCourse->concurrents as $myConcurCourse) {
                            $myConcurUnits += $myConcurCourse->units;
                        }

                        if($myConcurUnits + $myTermUnits <= $myTermUnits) {
                            foreach($myCourse->concurrents as $myConcurCourse) {
                                array_push($myCurrentTerm, $myConcurCourse);
                                $myTermUnits += $myConcurCourse->units;
                            }
                        } else {
                            foreach($myCourse->concurrents as $myConcurCourse) {
                                $myConcurCourse->nexttermindex++;
                            }
                        }
                    }
                }

            }
        }
    }

    public function hasFullyUsedCourses($myCourses = null) {
        if($myCourses == null) {
            echo "AAAAAAAAA!";
            return -1;
        }

        foreach($myCourses as $myCourse) {
            if(!$myCourse->isused) {
                return false;
            }
        }
        return true;
    }

    public function summerCoursesExist() {
        $myCourses = TableRegistry::get('course')->find();

        foreach($myCourses as $myCourse) {
            if($myCourse->summer) {
                return true;
            }
        }
        return false;
    }
}
