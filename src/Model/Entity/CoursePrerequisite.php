<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CoursePrerequisite Entity
 *
 * @property int $id
 * @property int $course_id
 * @property int $prerequisite_id
 *
 * @property \App\Model\Entity\Course $course
 * @property \App\Model\Entity\Prerequisite $prerequisite
 */
class CoursePrerequisite extends Entity
{

}
