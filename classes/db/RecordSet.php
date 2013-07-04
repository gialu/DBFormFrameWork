<?php
/**
 * @project:	db_test (wp11-12-99)
 * @module:	RecordSet
 * @copyright:	2013 SBW Neue Media AG
 * @author:	Johannes Kingma
 */
namespace db;

/**
 * Sammlung von Records
 * mit Zwischenspeicher
 */
class RecordSet implements \Iterator
{
	protected $recordType;
	protected $where;
	protected $order;
	
	protected $records = array();
	protected $next;

    public function __construct( $recordType, $where = null, $order = null )
    {
        $this->recordType = $recordType;
        $this->cache = array();
    }

    public function rewind()
    {
        reset( $this->cache );
        $this->next();
    }

    public function valid()
    {
        return (FALSE !== $this->next);
    }

    public function current()
    {
        return $this->next[1];
    }

    public function key()
    {
        return $this->next[0];
    }

    public function next()
    {
        // Try to get the next element in our data cache.
        $this->next = each($this->cache);

        // Past the end of the data cache
        if (FALSE === $this->next)
        {
        	$type = $this->recordType;
        	$this->cache = $type::findAll( $this->where, $this->order );

            $this->next = each($this->cache);
        }
    }
}