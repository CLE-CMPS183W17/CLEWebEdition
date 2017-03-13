<?php
/**
  * @var \App\View\AppView $this
  */
  //var_dump($course); die()
?>
<!-- <head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head> -->

<body>
<div class="container">
    <div class="row">
        <div class="col-md-auto">
            <div class="page-header">
                <h2>
                    College Length Estimator
                </h2>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr class="info">
                            <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('units') ?></th>
                            <th scope="col">Terms</th>
                            <th scope="col"><?= $this->Paginator->sort('concurrents') ?></th>
                            <th scope="col"><?= $this->Paginator->sort('prerequisites') ?></th>
                            <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($course as $course): ?>
                        <tr>
                            <td><?= h($course->name) ?></td>
                            <td><?= $this->Number->format($course->units) ?></td>
                            <td><?= ($course->summer ? 'Su' : '') ?> <?= ($course->fall ? 'F' : '') ?> <?= ($course->winter ? 'W' : '') ?> <?= ($course->spring ? 'S' : '') ?></td>
                            <td><?= h($course->concurrentNames()) ?></td>
                            <td><?= h($course->prerequisiteNames()) ?></td>
                            <td class="actions">

<!--                                  <?= $this->Html->link(__('View'), ['action' => 'view', $course->id], ['class' => 'btn btn-sm btn-info glyphicon glyphicon-zoom-in']) ?> -->
                                <?= $this->Html->link(__(''), ['action' => 'edit', $course->id], ['class' => 'btn btn-sm btn-primary glyphicon glyphicon-edit'] ) ?>
                                <?= $this->Form->postLink('', ['action' => 'delete', $course->id], ['confirm' => 'Are you sure you want to delete?', 'class' => 'btn btn-sm btn-danger glyphicon glyphicon-trash']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
            </div>
            
            <div class="paginator">
                <ul class="pagination">
                    <?= $this->Paginator->prev('< previous') ?>
                    <?= $this->Paginator->numbers(['before' => '', 'after' => '']) ?>
                    <?= $this->Paginator->next('next >') ?>
                </ul>
                <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
            </div>

        </div>
    </div>
</div>
</body>