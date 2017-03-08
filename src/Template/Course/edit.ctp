<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $course->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $course->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Course'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="course form large-9 medium-8 columns content">
    <?= $this->Form->create($course) ?>
    <fieldset>
        <legend><?= __('Edit Course') ?></legend>
        <?php
            //var_dump($course['concurrents']);die();
            echo $this->Form->input('name');
            echo $this->Form->input('units');
            echo $this->Form->input('summer');
            echo $this->Form->input('fall');
            echo $this->Form->input('winter');
            echo $this->Form->input('spring');
            echo $this->Form->input('concurrents', array('type'=>'select', 'options'=>$coursenames, 'multiple'=>true, 'val'=>$courseconcurrents));
            echo $this->Form->input('prerequisites', array('type'=>'select','options'=>$coursenames, 'multiple'=>true, 'val'=>$courseprerequisites));
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
