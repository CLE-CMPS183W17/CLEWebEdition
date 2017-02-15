<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CourseConcurrents Controller
 *
 * @property \App\Model\Table\CourseConcurrentsTable $CourseConcurrents
 */
class CourseConcurrentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Courses', 'Concurrents']
        ];
        $courseConcurrents = $this->paginate($this->CourseConcurrents);

        $this->set(compact('courseConcurrents'));
        $this->set('_serialize', ['courseConcurrents']);
    }

    /**
     * View method
     *
     * @param string|null $id Course Concurrent id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $courseConcurrent = $this->CourseConcurrents->get($id, [
            'contain' => ['Courses', 'Concurrents']
        ]);

        $this->set('courseConcurrent', $courseConcurrent);
        $this->set('_serialize', ['courseConcurrent']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $courseConcurrent = $this->CourseConcurrents->newEntity();
        if ($this->request->is('post')) {
            $courseConcurrent = $this->CourseConcurrents->patchEntity($courseConcurrent, $this->request->data);
            if ($this->CourseConcurrents->save($courseConcurrent)) {
                $this->Flash->success(__('The course concurrent has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The course concurrent could not be saved. Please, try again.'));
        }
        $courses = $this->CourseConcurrents->Courses->find('list', ['limit' => 200]);
        $concurrents = $this->CourseConcurrents->Concurrents->find('list', ['limit' => 200]);
        $this->set(compact('courseConcurrent', 'courses', 'concurrents'));
        $this->set('_serialize', ['courseConcurrent']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Course Concurrent id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $courseConcurrent = $this->CourseConcurrents->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $courseConcurrent = $this->CourseConcurrents->patchEntity($courseConcurrent, $this->request->data);
            if ($this->CourseConcurrents->save($courseConcurrent)) {
                $this->Flash->success(__('The course concurrent has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The course concurrent could not be saved. Please, try again.'));
        }
        $courses = $this->CourseConcurrents->Courses->find('list', ['limit' => 200]);
        $concurrents = $this->CourseConcurrents->Concurrents->find('list', ['limit' => 200]);
        $this->set(compact('courseConcurrent', 'courses', 'concurrents'));
        $this->set('_serialize', ['courseConcurrent']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Course Concurrent id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $courseConcurrent = $this->CourseConcurrents->get($id);
        if ($this->CourseConcurrents->delete($courseConcurrent)) {
            $this->Flash->success(__('The course concurrent has been deleted.'));
        } else {
            $this->Flash->error(__('The course concurrent could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
