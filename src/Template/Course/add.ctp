<?php
/**
  * @var \App\View\AppView $this
  */
?>


<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="page-header">
            <h2>Add Course</h2>
        </div>
        <?= $this->Form->create($course, ['novalidate']) ?>
        <fieldset>
        <?php
            echo $this->Form->input('name', ['label' => 'Namee']);
            echo $this->Form->input('units');
            echo $this->Form->input('summer');
            echo $this->Form->input('fall');
            echo $this->Form->input('winter');
            echo $this->Form->input('spring');
            echo $this->Form->input('concurrents', array('type'=>'select', 'options'=>$coursenames, 'multiple'=>true));
            echo $this->Form->input('prerequisites', array('type'=>'select','options'=>$coursenames, 'multiple'=>true));
        ?>
        </fieldset>
        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>