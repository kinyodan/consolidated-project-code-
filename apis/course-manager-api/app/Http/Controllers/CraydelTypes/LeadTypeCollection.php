<?php
namespace App\Http\Controllers\CraydelTypes;

use Countable;
use Exception;
use Illuminate\Support\Collection;
use Iterator;

class LeadTypeCollection implements Iterator, Countable
{
    /**
     * Leads
     *
     * @var $leads
    */
    protected $leads = [];

    /**
     * Current loop position
    */
    protected $position = 0;

    /**
     * Constructor
     * @param LeadType ...$leads
     */
    public function __construct(LeadType ...$leads)
    {
        $this->leads = $leads;
    }

    /**
     * @return mixed
     */
    public function current(): LeadType
    {
        return $this->leads[$this->position];
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
        return isset($this->leads[$this->position]);
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
        return count($this->leads);
    }

    /**
     * Push leads into the collection
     * @param LeadType|null $lead
     * @throws Exception
     */
    public function push(?LeadType $lead){
        if(!$lead instanceof LeadType){
            throw new Exception('Invalid lead type');
        }

        array_push($this->leads, $lead);
    }

    /**
     * Return a type collection
    */
    public function collection(): ?Collection
    {
        if($this->count() <= 0){
            return null;
        }

        $_collection = collect($this->leads);

        if(is_null($_collection)){
            return null;
        }

        return $_collection->reject(function ($lead){
            if(!is_callable([$lead, 'getMobileNumber'])){
                return true;
            }

            return empty($lead->getMobileNumber());
        });
    }

    /**
     * Implement a to array method
    */
    public function toArray(): array
    {
        if($this->count() <= 0){
            return array();
        }

        $_collection = collect($this->leads);

        if(is_null($_collection)){
            return array();
        }

        $_collection = $_collection->reject(function ($lead){
            if(!is_callable([$lead, 'getMobileNumber'])){
                return true;
            }

            return empty($lead->getMobileNumber());
        });

        if($_collection->count() <= 0){
            return array();
        }

        return $_collection->toArray();
    }
}
