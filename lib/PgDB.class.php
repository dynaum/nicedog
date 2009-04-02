<?php

/**
 * NiceDog framework
 *
 * PgDB:
 *   Postgre class
 * @author Elber Ribeiro
 * @version 0.1
 * @created 02-abr-2009 15:34
 */

class PgDB
{
    protected $host;
    protected $user;
    protected $password;
    protected $database;
    protected $port;
    protected $connection;
    public $records;
    public $line;
    public $record_count;
    protected $resource_query;
    protected $str_connection;
    protected $error;
    protected $eof;

    function __construct($query = "")
    {
        $this->str_connection = "host={$this->host} dbname={$this->database} user={$this->user} password={$this->password}";
        if($query)
            $this->execute($query);
    }

    function connect()
    {
        if ( !$this->connection )
        {
            $con = pg_connect( $this->str_connection );

            if( $con )
                $this->connection = $con;
            else
                throw new Exception("PgDB Exception ( host={$this->host} dbname={$this->database} )");
        }
    }

    function execute($query)
    {
        $sql = trim($query);
        
        if ( empty($sql) ) return false;

        $this->connect();

        $this->resource_query = @pg_query($this->connection, $sql);
        $this->error = @pg_last_error($this->connection);

        if (!$this->resource_query) return false;

        if(pg_num_rows($this->resource_query))
            $this->record_count = pg_num_rows($this->resource_query);
        else
            $this->record_count = pg_affected_rows($this->resource_query);

        $this->line = 0;
        if($this->record_count == 0)
            $this->eof = true;
        else
            $this->eof = false;

        $this->records = false;
        $line = 0;
        while($register = @pg_fetch_array($this->resource_query))
        {
            if(is_array($register))
            {
                foreach($register as $id => $value)
                    $register[$id] = htmlspecialchars($value);
            }
            $this->records[$line++] = $register;
        }

        @pg_free_result($this->resource_query);

        return true;
    }

    function tupla()
    {
        if(is_array($this->records[$this->line]))
            return $this->records[$this->line];
    }

    function tupla_line($line)
    {
        if(is_array($this->records[$line]))
            return $this->records[$line];
    }

    function first()
    {
        $this->line = 0;
        $this->eof = $this->record_count == 0 ? true : false;
    }

    function eof()
    {
        return $this->eof;
    }

    function next()
    {
        if(is_array($this->records[$this->line+1]))
        {
            $this->eof = false;
            $this->line++;
            return true;
        }
        else
        {
            $this->eof = true;
            return false;
        }
    }

    function field($name, $convert = true)
    {
        if(is_array($this->records[$this->line]))
        {
            return $this->records[$this->line][$name];
        }
    }

    function get_error()
    {
        return $this->error;
    }

    function link_ok()
    {
        $this->connection = pg_connect( $this->str_connection );
        $stat = pg_connection_status($dbconn);
        
        if ($stat === PGSQL_CONNECTION_OK){
            return true;
        }
        return false;
    }
    
    function getAll( $implode = null )
    {
        $retorno = array();
        
        while ( !$this->eof() )
        {
            $retorno[] = $implode ? implode( $implode, $this->tupla() ) : $this->tupla();
            $this->next();
        }
        
        return $retorno;
    }
}
