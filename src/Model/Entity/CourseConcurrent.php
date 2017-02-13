<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CourseConcurrent Entity
 *
 * @property int $id
 * @property int $course_id
 * @property int $concurrent_id
 *
 * @property \App\Model\Entity\Course $course
 * @property \App\Model\Entity\Concurrent $concurrent
 */
class CourseConcurrent extends Entity
{

}
