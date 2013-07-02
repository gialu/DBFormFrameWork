<?php
/**
 * @project:	db_test (wp11-12-99)
 * @module:	RecordSet
 * @copyright:	2013 SBW Neue Media AG
 * @author:	Johannes Kingma
 */
namespace db;

class RecordSet
{
	protected $recordType;
	protected $records = array();
	public function __contstruct( $recordType )
	{
		$this->recordType = $recordType;
	}
	public fill( $where = null, $query = null )
	{
		$r = new $this->recordType;
		$records = $r->findAll( $where, $query );
	}


}
// Wrap a PDOStatement to iterate through all result rows. Uses a 
// local cache to allow rewinding.
class RecordSet implements Iterator
{
    public
        $stmt,
        $cache,
        $next;

    public function __construct( $recordType, $where = null, $order = null )
    {
        $this->cache = array();
        $this->stmt = $stmt;
    }

    public function rewind()
    {
        reset($this->cache);
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
            // Fetch the next row of data
            $row = $this->stmt->fetch(PDO::FETCH_ASSOC);

            // Fetch successful
            if ($row)
            {
                // Add row to data cache
                $this->cache[] = $row;
            }

            $this->next = each($this->cache);
        }
    }
