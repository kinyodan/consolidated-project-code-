<?php
namespace App\Http\Controllers\CraydelTypes;

use Countable;
use Exception;
use Illuminate\Support\Collection;
use Iterator;

class CraydelCourseIndexListItemTypeCollection implements Iterator, Countable
{
    /**
     * Courses
     *
     * @var array $course_copies
    */
    protected array $course_copies = [];

    /**
     * Current loop position
    */
    protected int $position = 0;

    /**
     * Constructor
     * @param CraydelCourseIndexListItemType ...$course_indexing_copies
     */
    public function __construct(CraydelCourseIndexListItemType ...$course_indexing_copies)
    {
        $this->course_copies = $course_indexing_copies;
    }

    /**
     * @return mixed
     */
    public function current(): CraydelCourseIndexListItemType
    {
        return $this->course_copies[$this->position];
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
        return isset($this->course_copies[$this->position]);
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
        return count($this->course_copies);
    }

    /**
     * Push courses into the collection
     * @param CraydelCourseIndexListItemType|null $craydel_course_type
     * @throws Exception
     */
    public function push(?CraydelCourseIndexListItemType $craydel_course_type){
        if(!$craydel_course_type instanceof CraydelCourseIndexListItemType){
            throw new Exception('Invalid course index list item type');
        }

        $this->course_copies[] = $craydel_course_type;
    }

    /**
     * Return a type collection
    */
    public function collection(): ?Collection
    {
        if($this->count() <= 0){
            return null;
        }

        $_collection = collect($this->course_copies);

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

        $_collection = collect($this->course_copies);

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
