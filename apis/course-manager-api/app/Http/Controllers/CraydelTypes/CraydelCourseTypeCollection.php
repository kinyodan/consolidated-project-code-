<?php
namespace App\Http\Controllers\CraydelTypes;

use Countable;
use Exception;
use Illuminate\Support\Collection;
use Iterator;

class CraydelCourseTypeCollection implements Iterator, Countable
{
    /**
     * Courses
     *
     * @var array $courses
    */
    protected array $courses = [];

    /**
     * Current loop position
    */
    protected int $position = 0;

    /**
     * Constructor
     * @param CraydelCourseType ...$courses
     */
    public function __construct(CraydelCourseType ...$courses)
    {
        $this->courses = $courses;
    }

    /**
     * @return mixed
     */
    public function current(): CraydelCourseType
    {
        return $this->courses[$this->position];
    }

    /**
     * Move to next position in the collection
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * @return float|int|null
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->courses[$this->position]);
    }

    /**
     *
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Implementation of method declared in \Countable.
     * Provides support for count()
     */
    public function count(): int
    {
        return count($this->courses);
    }

    /**
     * Push courses into the collection
     * @param CraydelCourseType|null $craydel_course_type
     * @throws Exception
     */
    public function push(?CraydelCourseType $craydel_course_type){
        if(!$craydel_course_type instanceof CraydelCourseType){
            throw new Exception('Invalid course type');
        }

        $this->courses[] = $craydel_course_type;
    }

    /**
     * Return a type collection
    */
    public function collection(): ?Collection
    {
        if($this->count() <= 0){
            return null;
        }

        $_collection = collect($this->courses);

        if(is_null($_collection)){
            return null;
        }

        return $_collection->reject(function ($course){
            if(!is_callable([$course, 'getCourseCode'])){
                return true;
            }

            return empty($course->getCourseCode());
        });
    }

    /**
     * Implement to array method
    */
    public function toArray(): array
    {
        if($this->count() <= 0){
            return array();
        }

        $_collection = collect($this->courses);

        if(is_null($_collection)){
            return array();
        }

        $_collection = $_collection->reject(function ($lead){
            if(!is_callable([$lead, 'getCourseCode'])){
                return true;
            }

            return empty($lead->getCourseCode());
        });

        if($_collection->count() <= 0){
            return array();
        }

        return $_collection->toArray();
    }
}
