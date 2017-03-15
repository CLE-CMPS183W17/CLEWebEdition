<nav class="navbar navbar-inverse">
    <div class="container">

        <div class="navbar-header">
            <?= $this->Html->link('CLE', ['action' => 'index'], ['class' => 'navbar-brand']) ?>
        </div>

        <ul class="nav navbar-nav">
            <li>
                <?= $this->Html->link('List Courses', ['action' => 'index']) ?>
            </li>
        </ul>
            
    <?php if (!isset($course->id)): ?>
        <ul class="nav navbar-nav">
          <li>
            <?= $this->Html->link('Add Course', [ 'action' => 'add']) ?>
          </li>
          <li>
            <?= $this->Form->postLink(__('Estimate'), ['action' => 'userinfo']) ?>
          </li>
          <li>
            <?= $this->Form->postLink('Delete all courses', ['action' => 'deleteAll'], ['confirm' => 'Delete all courses?']) ?>
        </li>
        </ul>
    <?php endif; ?>

<!--     <?php if(isset($course->id)): ?>
        <ul class="nav navbar-nav">
            <li>
                <?= $this->Html->link('Edit Course', ['action' => 'edit', $course->id]) ?>
            </li>
        </ul>
    <?php endif; ?> -->

    <?php if(isset($course->id)): ?>
        <ul class="nav navbar-nav">
            <li>
                <?= $this->Form->postLink('Delete Course', ['action' => 'delete', $course->id], ['confirm' => 'Are you sure you want to delete?']) ?>
            </li>
        </ul>
    <?php endif; ?>

            
    </div>
</nav>
