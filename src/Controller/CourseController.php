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
        $myTermIndex = 0;
        // $hasSummerCourses = false;
        // if($this->summerCoursesExist()) {
        //     $hasSummerCourses = true;
        // }

        $rawCourseList = TableRegistry::get('course')->find()->contain(['Prerequisites', 'Concurrents']);
        $myTerms = [];

        foreach($rawCourseList as $myCourse) {
            $myCourse->nexttermindex = 0;
            $myCourse->isused = false;
        }
        // array_push($myTerms, []);
        // debug($myTerms);die();

        // $course = TableRegistry::get('course')->find()->contain(['Concurrents', 'Prerequisites'])
        // ->first();
        //
        // var_dump($course->concurrents[0]->units);die();

        while(!$this->hasFullyUsedCourses($rawCourseList)) {
            $myCurrentTerm = [];
            $myTermUnits = 0;

            foreach($rawCourseList as $myCourse) {
                if($myCourse->isused || $myCourse->nexttermindex != $myTermIndex) {
                    continue;
                }
                $myCurrentCourse = $myCourse;

                if(!empty($myCourse->prerequisites) && !$this->hasFullyUsedCourses($myCourse->prerequisites)) {
                    $myCourse->nexttermindex++;
                    $myCurrentCourse = $this->getPrereq($myCourse, $myTermIndex);

                    if($myCurrentCourse->units + $myTermUnits <= $myTermLimit) {
                        debug("okay.");
                        array_push($myCurrentTerm, $myCurrentCourse);
                        $myCurrentCourse->isused = true;
                        debug($myCourse->isused);
                        $myTermUnits += $myCurrentCourse->units;
                    } else {
                        $myCurrentCourse->nexttermindex++;
                    }
                } else if(!empty($myCourse->concurrents) && !$this->hasFullyUsedCourses($myCourse->concurrents)) {
                    $hasUnsatPrereqs = $this->checkForPrereqs($myCourse->concurrents, $myTermIndex);

                    if($hasUnsatPrereqs) {
                        $myCourse->nexttermindex++;
                        $myCurrentCourse = $this->getPrereq($myCourse, $myTermIndex);

                        if($myCurrentCourse->units + $myTermUnits <= $myTermLimit) {
                            array_push($myCurrentTerm, $myCurrentCourse);
                            $myTermUnits += $myCurrentCourse->units;
                            $myCurrentCourse->isused = true;
                        } else {
                            $myCurrentCourse->nexttermindex++;
                        }
                    } else {
                        $myConcurUnits = 0;
                        foreach($myCourse->concurrents as $myConcurCourse) {
                            $myConcurUnits += $myConcurCourse->units;
                        }

                        if($myConcurUnits + $myTermUnits <= $myTermLimit) {
                            foreach($myCourse->concurrents as $myConcurCourse) {
                                array_push($myCurrentTerm, $myConcurCourse);
                                $myTermUnits += $myConcurCourse->units;
                                $myConcurCourse->isused = true;
                            }
                            array_push($myCurrentTerm, $myCurrentCourse);
                            $myTermUnits += $myCurrentCourse->units;
                            $myCurrentCourse->isused = true;
                        } else {
                            foreach($myCourse->concurrents as $myConcurCourse) {
                                $myConcurCourse->nexttermindex++;
                            }
                            $myCourse->nexttermindex++;
                        }
                    }
                } else {
                    if($myCurrentCourse->units + $myTermUnits <= $myTermLimit) {
                        debug("hmm..");
                        debug($myCurrentCourse->name);
                        debug($myCurrentCourse->isused);
                        array_push($myCurrentTerm, $myCurrentCourse);
                        $myCurrentCourse->isused = true;
                        $myTermUnits += $myCurrentCourse->units;
                    } else {
                        $myCurrentCourse->nexttermindex++;
                    }
                }
            }
            array_push($myTerms, $myCurrentTerm);
            debug($myTermIndex);
            $myTermIndex++;
        }

        $myQuarterNumber = 0;
        foreach($myTerms as $myCurrentTerm) {
            $myQuarterNumber++;
            debug("Quarter");
            foreach($myCurrentTerm as $myCourse) {
                $myResultLine = $myCourse->name . ": " . $myCourse->units . " Units";
                debug($myResultLine);
            }
        }
        die();
    }

    public function &getPrereq(&$myCourse = null, &$termIndex) {
        if($myCourse == null) {
            echo "Course Controller Error: null reference given for myCourse.";
            return -1;
        }

        // var_dump($myCourse);die();
        if($myCourse->prerequisites == null) {
            return $myCourse;
        }
        foreach($myCourse->prerequisites as $myPrereqCourse) {
            if(!$myPrereqCourse->isused && $myPrereqCourse->nexttermindex == $termIndex) {
                return $myPrereqCourse;
            }
        }
        return $myCourse;
    }

    public function checkForPrereqs(&$myConcurrents = null, &$myTermIndex) {
        if($myConcurrents == null) {
            echo "Course Controller Error: null reference given for myConcurrents.";
            return -1;
        }

        foreach($myConcurrents as $myCourse) {
            if(!empty($myCourse->prerequisites) && !$this->hasFullyUsedCourses($myCourse->prerequisites)) {
                return true;
            }
        }
        return false;
    }

    public function hasFullyUsedCourses(&$myCourses = null) {
        if($myCourses == null) {
            echo "Course Controller Error: null reference given for myCourse.";
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
