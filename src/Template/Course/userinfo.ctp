<?php
/**
  * @var \App\View\AppView $this
  */
?>


<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="page-header">
            <h2>Get Schedule</h2>
        </div>
        <?= $this->Form->create(null, ['url' => ['action' => 'process']]) ?>
        <fieldset>
        <?php
            echo $this->Form->input('TermLimit', ['label' => 'Max Units Per Term', 'type' => 'number', 'default' => 15]);
            echo $this->Form->label('Quarters Available');
            echo '<br />';
            echo 'Summer ';
            echo $this->Form->checkbox('summer', ['label' => 'Summer']);
            echo '<br />';
            echo 'Fall ';
            echo $this->Form->checkbox('fall', ['label' => 'Fall', 'default' => True]);
            echo '<br />';
            echo 'Winter ';
            echo $this->Form->checkbox('winter', ['label' => 'Winter', 'default' => True]);
            echo '<br />';
            echo 'Spring ';
            echo $this->Form->checkbox('spring', ['label' => 'Spring', 'default' => True]);
            echo '<br />';
            echo '<br />';
            echo $this->Form->input('Subset', ['type'=>'select', 'options'=>$coursenames, 'multiple'=>true, 'label' => 'Courses Needed']);
        ?>
        </fieldset>
        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
