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

        $rawCourseList = $this->Course->find()->contain(['Prerequisites', 'Concurrents', 'Dependents']);
        $myTerms = [];
        //debug($rawCourseList);die();

        $touched = [];
        foreach($rawCourseList as $myCourse) {
            if($myCourse->units > $myTermLimit) {
                echo "Process Term Error: Course given that exceeds term limit.";
                return -1;
            }
            $myCourse->nexttermindex = 0;
            $myCourse->isused = false;
            $this->Course->save($myCourse);
            $touched[$myCourse->id] = false;
        }

        while(!$this->hasFullyUsedCourses($rawCourseList)) {
            $myCurrentTerm = [];
            $myTermUnits = 0;

            foreach($rawCourseList as $myCourse) {
                if($myCourse->isused || $myCourse->nexttermindex != $myTermIndex) {
                    continue;
                }

                debug($touched[$myCourse->id]);
                if($touched[$myCourse->id]) {
                    $myCourse = $this->Course->get($myCourse->id);
                }

                //Priorities:
                //1. Check if course has prerequisite. If so and it's not used,
                //return it and check this course later.
                if(!empty($myCourse->prerequisites) && !$this->hasFullyUsedCourses($myCourse->prerequisites)) {
                    $myCourse = $this->prerequisiteHelper($myCourse, $myTermIndex, $myCurrentTerm, $myTermUnits, $myTermLimit, $touched);
                }
                //2. Does this course have concurrents? If so, get all of them,
                //make sure they don't have any prerequisites either, and if
                //they do not, make sure they can fit in the current term, and
                //put them in there if they do.
                if(!empty($myCourse->concurrents) && !$this->hasFullyUsedCourses($myCourse->concurrents)) {
                    $myCourse = $this->concurrentHelper($myCourse, $myTermIndex, $myCurrentTerm, $myTermUnits, $myTermLimit, $touched);
                }

                if($myCourse->nexttermindex == $myTermIndex) {
                    if($myCourse->units + $myTermUnits <= $myTermLimit) {
                        $myCourse->isused = true;
                        $myTermUnits += $myCourse->units;
                        $touched[$myCourse->id] = true;
                        $this->Course->save($myCourse);
                        array_push($myCurrentTerm, $myCourse);

                        if(!empty($myCourse->dependents)) {
                            foreach($myCourse->dependents as $myFutureCourse) {
                                if($myFutureCourse->nexttermindex == $myTermIndex) {
                                    $myFutureCourse->nexttermindex++;
                                    $touched[$myFutureCourse->id] = true;
                                    $this->Course->save($myFutureCourse);
                                }
                            }
                        }
                    } else {
                        $myCourse->nexttermindex++;
                        $this->Course->save($myCourse);
                    }
                }
            }
            array_push($myTerms, $myCurrentTerm);
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

    public function &prerequisiteHelper(&$myPrereqCourse = null, &$myTermIndex, &$myCurrentTerm, &$myTermUnits, &$myTermLimit, &$touched) {
        if($myPrereqCourse == null) {
            echo "prerequisiteHelper Error: null reference given for myPrereqCourse";
            return -1;
        }

        if($myPrereqCourse->prerequisites == null || $myPrereqCourse->nexttermindex != $myTermIndex) {
            return $myCourse;
        }

        foreach($myPrereqCourse->prerequisites as $myCourse) {
            if(!$myCourse->isused && $myCourse->nexttermindex == $myTermIndex) {
                if(!empty($myCourse->concurrents) && !$this->hasFullyUsedCourses($myCourse->concurrents)) {
                    return $this->concurrentHelper($myCourse, $myTermIndex, $myCurrentTerm, $myTermUnits, $myTermLimit, $touched);
                }
                return $myCourse;
            }
        }
        return $myPrereqCourse;
    }

    public function concurrentHelper(&$myConcurrentCourse = null, &$myTermIndex, &$myCurrentTerm, &$myTermUnits, &$myTermLimit, &$touched) {
        if($myConcurrentCourse == null) {
            echo "concurrentHelper Error: null reference given for myConcurrentCourse or myCurrentTerm.";
            return -1;
        }

        if($myConcurrentCourse->nexttermindex != $myTermIndex) {
            return $myConcurrentCourse;
        }

        foreach($myConcurrentCourse->concurrents as $myCourse) {
            if(!empty($myCourse->prerequisites) && !$this->hasFullyUsedCourses($myCourse->prerequisites)) {
                return $this->prerequisiteHelper($myCourse, $myTermIndex, $myCurrentTerm, $myTermUnits, $myTermLimit, $touched);
            }
        }

        $concurrentUnits = 0;
        foreach($myConcurrentCourse->concurrents as $myCourse) {
            $concurrentUnits += $myCourse->units;
        }

        if($concurrentUnits > $myTermLimit) {
            echo "Course Controller Error: Impossible concurrent chain given for defined Quarter Limit.";
            return -1;
        }

        if($concurrentUnits + $myTermUnits <= $myTermLimit) {
            foreach($myConcurrentCourse->concurrents as $myCourse) {
                $myCourse->isused = true;
                $myTermUnits += $myCourse->units;
                $touched[$myCourse->id] = true;
                $this->Course->save($myCourse);
                array_push($myCurrentTerm, $myCourse);
            }
        } else {
            foreach($myConcurrentCourse->concurrents as $myCourse) {
                $myCourse->nexttermindex++;
                $touched[$myCourse->id] = true;
                $this->Course->save($myCourse);
            }
            $myConcurrentCourse->nexttermindex++;
            $touched[$myCourse->id] = true;
            $this->Course->save($myConcurrentCourse);
        }
        return $myConcurrentCourse;
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
}
