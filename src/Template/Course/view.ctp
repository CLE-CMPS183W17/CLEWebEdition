<?php
/**
  * @var \App\View\AppView $this
  */
?>

<div class="course view large-9 medium-8 columns content">
    <h3><?= h($course->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($course->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Concurrents') ?></th>
            <td><?= h($course->concurrentNames()) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Prerequisites') ?></th>
            <td><?= h($course->prerequisiteNames()) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($course->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Units') ?></th>
            <td><?= $this->Number->format($course->units) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Summer') ?></th>
            <td><?= $course->summer ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Fall') ?></th>
            <td><?= $course->fall ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Winter') ?></th>
            <td><?= $course->winter ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Spring') ?></th>
            <td><?= $course->spring ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
