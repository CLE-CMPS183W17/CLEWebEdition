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





    public function process() {
        $myTermLimit = 15;
        $myTermIndex = 0;
        $termCheck = 0;
        // $hasSummerCourses = false;
        // if($this->summerCoursesExist()) {
        //     $hasSummerCourses = true;
        // }

        $rawCourseList = $this->Course->find()->contain(['Prerequisites', 'Concurrents', 'Dependents']);
        $myTerms = [];

        $nexttermindex = [];
        foreach($rawCourseList as $myCourse) {
            if($myCourse->units > $myTermLimit) {
                echo "Process Term Error: Course given that exceeds term limit.";
                return -1;
            }
            $nexttermindex[$myCourse->id] = 0;
        }

        while(!$this->hasFullyUsedCourses($rawCourseList, $nexttermindex)) {
            $myCurrentTerm = [];
            $myTermUnits = 0;

            if($termCheck > 3) {
                $termCheck = 0;
            }

            foreach($rawCourseList as $myCourse) {
                if($nexttermindex[$myCourse->id] != $myTermIndex) {
                    continue;
                }

                //Priorities:
                //1. Check if course has prerequisite. If so and it's not used,
                //return it and check this course later.
                if(!empty($myCourse->prerequisites) && !$this->hasFullyUsedCourses($myCourse->prerequisites, $nexttermindex)) {
                    $myCourse = $this->prerequisiteHelper($myCourse, $myTermIndex, $myCurrentTerm, $myTermUnits, $myTermLimit, $nexttermindex);
                }
                //2. Does this course have concurrents? If so, get all of them,
                //make sure they don't have any prerequisites either, and if
                //they do not, make sure they can fit in the current term, and
                //put them in there if they do.
                if(!empty($myCourse->concurrents) && !$this->hasFullyUsedCourses($myCourse->concurrents, $nexttermindex)) {
                    $myCourse = $this->concurrentHelper($myCourse, $myTermIndex, $myCurrentTerm, $myTermUnits, $myTermLimit, $nexttermindex);
                }

                if($nexttermindex[$myCourse->id] == $myTermIndex) {
                    if($myCourse->units + $myTermUnits <= $myTermLimit) {
                        if($myCourse->fall == null && $myCourse->winter == null && $myCourse->spring == null && $myCourse->summer == null) {
                            $myTermUnits += $myCourse->units;
                            $nexttermindex[$myCourse->id] = -1;
                            array_push($myCurrentTerm, $myCourse);

                            if (!empty($myCourse->dependents)) {
                                foreach ($myCourse->dependents as $myFutureCourse) {
                                    if ($nexttermindex[$myFutureCourse->id] == $myTermIndex) {
                                        $nexttermindex[$myFutureCourse->id]++;
                                    }
                                }
                            }

                        } else {
                            switch ($termCheck) {
                                case 0:
                                    if($myCourse->fall == 1) {
                                        $myTermUnits += $myCourse->units;
                                        $nexttermindex[$myCourse->id] = -1;
                                        array_push($myCurrentTerm, $myCourse);

                                        if (!empty($myCourse->dependents)) {
                                            foreach ($myCourse->dependents as $myFutureCourse) {
                                                if ($nexttermindex[$myFutureCourse->id] == $myTermIndex) {
                                                    $nexttermindex[$myFutureCourse->id]++;
                                                }
                                            }
                                        }
                                    } else {
                                        $nexttermindex[$myCourse->id]++;
                                    }
                                    break;
                                case 1:
                                    if($myCourse->winter == 1) {
                                        $myTermUnits += $myCourse->units;
                                        $nexttermindex[$myCourse->id] = -1;
                                        array_push($myCurrentTerm, $myCourse);

                                        if (!empty($myCourse->dependents)) {
                                            foreach ($myCourse->dependents as $myFutureCourse) {
                                                if ($nexttermindex[$myFutureCourse->id] == $myTermIndex) {
                                                    $nexttermindex[$myFutureCourse->id]++;
                                                }
                                            }
                                        }
                                    } else {
                                        $nexttermindex[$myCourse->id]++;
                                    }
                                    break;
                                case 2:
                                    if($myCourse->spring == 1) {
                                        $myTermUnits += $myCourse->units;
                                        $nexttermindex[$myCourse->id] = -1;
                                        array_push($myCurrentTerm, $myCourse);

                                        if (!empty($myCourse->dependents)) {
                                            foreach ($myCourse->dependents as $myFutureCourse) {
                                                if ($nexttermindex[$myFutureCourse->id] == $myTermIndex) {
                                                    $nexttermindex[$myFutureCourse->id]++;
                                                }
                                            }
                                        }
                                    } else {
                                        $nexttermindex[$myCourse->id]++;
                                    }
                                    break;
                                case 3:
                                    if($myCourse->summer == 1) {
                                        $myTermUnits += $myCourse->units;
                                        $nexttermindex[$myCourse->id] = -1;
                                        array_push($myCurrentTerm, $myCourse);

                                        if (!empty($myCourse->dependents)) {
                                            foreach ($myCourse->dependents as $myFutureCourse) {
                                                if ($nexttermindex[$myFutureCourse->id] == $myTermIndex) {
                                                    $nexttermindex[$myFutureCourse->id]++;
                                                }
                                            }
                                        }
                                    } else {
                                        $nexttermindex[$myCourse->id]++;
                                    }
                                    break;
                            }

                        }

                    } else {
                        $nexttermindex[$myCourse->id]++;
                    }
                }
            }
            array_push($myTerms, $myCurrentTerm);
            $myTermIndex++;
            $termCheck++;
        }

        $this->set(['myTerms'=>$myTerms, 'myTermIndex'=>$myTermIndex]);

    }

    public function &prerequisiteHelper(&$myPrereqCourse = null, &$myTermIndex, &$myCurrentTerm, &$myTermUnits, &$myTermLimit, &$nexttermindex) {
        if($myPrereqCourse == null) {
            echo "prerequisiteHelper Error: null reference given for myPrereqCourse";
            return -1;
        }

        if($myPrereqCourse->prerequisites == null || $nexttermindex[$myPrereqCourse->id] != $myTermIndex) {
            return $myCourse;
        }

        foreach($myPrereqCourse->prerequisites as $myCourse) {
            if($nexttermindex[$myCourse->id] == $myTermIndex) {
                if(!empty($myCourse->concurrents) && !$this->hasFullyUsedCourses($myCourse->concurrents, $nexttermindex)) {
                    return $this->concurrentHelper($myCourse, $myTermIndex, $myCurrentTerm, $myTermUnits, $myTermLimit, $nexttermindex);
                }
                return $myCourse;
            }
        }
        return $myPrereqCourse;
    }

    public function concurrentHelper(&$myConcurrentCourse = null, &$myTermIndex, &$myCurrentTerm, &$myTermUnits, &$myTermLimit, &$nexttermindex) {
        if($myConcurrentCourse == null) {
            echo "concurrentHelper Error: null reference given for myConcurrentCourse or myCurrentTerm.";
            return -1;
        }

        if($nexttermindex[$myConcurrentCourse->id] != $myTermIndex) {
            return $myConcurrentCourse;
        }

        foreach($myConcurrentCourse->concurrents as $myCourse) {
            if(!empty($myCourse->prerequisites) && !$this->hasFullyUsedCourses($myCourse->prerequisites, $nexttermindex)) {
                return $this->prerequisiteHelper($myCourse, $myTermIndex, $myCurrentTerm, $myTermUnits, $myTermLimit, $nexttermindex);
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
                $myTermUnits += $myCourse->units;
                $nexttermindex[$myCourse->id] = -1;
                array_push($myCurrentTerm, $myCourse);
            }
        } else {
            foreach($myConcurrentCourse->concurrents as $myCourse) {
                $nexttermindex[$myCourse->id]++;
            }
            $nexttermindex[$myConcurrentCourse->id]++;
        }
        return $myConcurrentCourse;
    }

    public function hasFullyUsedCourses(&$myCourses = null, &$nexttermindex) {
        if($myCourses == null) {
            echo "Course Controller Error: null reference given for myCourse.";
            return -1;
        }

        foreach($myCourses as $myCourse) {
            if($nexttermindex[$myCourse->id] > -1) {
                return false;
            }
        }
        return true;
    }
}
