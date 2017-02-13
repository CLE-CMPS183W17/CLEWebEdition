<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CoursePrerequisites Controller
 *
 * @property \App\Model\Table\CoursePrerequisitesTable $CoursePrerequisites
 */
class CoursePrerequisitesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Courses', 'Prerequisites']
        ];
        $coursePrerequisites = $this->paginate($this->CoursePrerequisites);

        $this->set(compact('coursePrerequisites'));
        $this->set('_serialize', ['coursePrerequisites']);
    }

    /**
     * View method
     *
     * @param string|null $id Course Prerequisite id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $coursePrerequisite = $this->CoursePrerequisites->get($id, [
            'contain' => ['Courses', 'Prerequisites']
        ]);

        $this->set('coursePrerequisite', $coursePrerequisite);
        $this->set('_serialize', ['coursePrerequisite']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $coursePrerequisite = $this->CoursePrerequisites->newEntity();
        if ($this->request->is('post')) {
            $coursePrerequisite = $this->CoursePrerequisites->patchEntity($coursePrerequisite, $this->request->data);
            if ($this->CoursePrerequisites->save($coursePrerequisite)) {
                $this->Flash->success(__('The course prerequisite has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The course prerequisite could not be saved. Please, try again.'));
        }
        $courses = $this->CoursePrerequisites->Courses->find('list', ['limit' => 200]);
        $prerequisites = $this->CoursePrerequisites->Prerequisites->find('list', ['limit' => 200]);
        $this->set(compact('coursePrerequisite', 'courses', 'prerequisites'));
        $this->set('_serialize', ['coursePrerequisite']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Course Prerequisite id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $coursePrerequisite = $this->CoursePrerequisites->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $coursePrerequisite = $this->CoursePrerequisites->patchEntity($coursePrerequisite, $this->request->data);
            if ($this->CoursePrerequisites->save($coursePrerequisite)) {
                $this->Flash->success(__('The course prerequisite has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The course prerequisite could not be saved. Please, try again.'));
        }
        $courses = $this->CoursePrerequisites->Courses->find('list', ['limit' => 200]);
        $prerequisites = $this->CoursePrerequisites->Prerequisites->find('list', ['limit' => 200]);
        $this->set(compact('coursePrerequisite', 'courses', 'prerequisites'));
        $this->set('_serialize', ['coursePrerequisite']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Course Prerequisite id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $coursePrerequisite = $this->CoursePrerequisites->get($id);
        if ($this->CoursePrerequisites->delete($coursePrerequisite)) {
            $this->Flash->success(__('The course prerequisite has been deleted.'));
        } else {
            $this->Flash->error(__('The course prerequisite could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
