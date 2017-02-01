<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Course'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="course form large-9 medium-8 columns content">
    <?= $this->Form->create($course) ?>
    <fieldset>
        <legend><?= __('Add Course') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('units');
            echo $this->Form->input('prerequisites');
            echo $this->Form->input('concurrents');
            echo $this->Form->input('summer');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
