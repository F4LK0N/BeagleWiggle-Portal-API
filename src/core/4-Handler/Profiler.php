<?
namespace Core;
use \JsonSerializable;
use Core\Helper\TIME;
use Core\Helper\STORAGE;
use Core\Helper\JSON;
defined("FKN") or http_response_code(403).die('Forbidden!');

class Profiler implements JsonSerializable
{
    private bool $finished = false;
    
    //Memory (B - Bytes)
    private int $total_memory_used;
    private int $total_memory_allocated;
    
    //Memory (B - Bytes)
    //private int $memory_used;
    //private int $memory_allocated;
    
    //Time (us - microtime)
    private float $time;



    public function __construct (bool $useRequestStartValues=false)
    {
        if($useRequestStartValues===true){
            //$this->memory_used      = PROFILER_MEMORY;
            //$this->memory_allocated = PROFILER_MEMORY_REAL;
            $this->time             = PROFILER_TIME;
        }else{
            //$this->memory_used      = memory_get_usage(false);
            //$this->memory_allocated = memory_get_usage(true);
            $this->time             = microtime(true);
        }
    }

    public function end(): Profiler
    {
        if($this->finished){
            return $this;
        }
        $this->finished = true;
        
        //$this->memory_used      = $this->memory_used      - memory_get_peak_usage(false);
        //$this->memory_allocated = $this->memory_allocated - memory_get_peak_usage(true);
        
        $this->total_memory_used      = memory_get_peak_usage(false);
        $this->total_memory_allocated = memory_get_peak_usage(true);
        
        $this->time             = (microtime(true) - $this->time);
        
        return $this;
    }

    public function jsonSerialize (): mixed
    {
        $this->end();
        return [
            //'memory_used'      => STORAGE::FORMAT($this->memory_used),
            //'memory_allocated' => STORAGE::FORMAT($this->memory_allocated),
            //
            //'total_memory_used'      => STORAGE::FORMAT($this->total_memory_used),
            //'total_memory_allocated' => STORAGE::FORMAT($this->total_memory_allocated),
            
            'memory' => STORAGE::FORMAT($this->total_memory_used)." (".STORAGE::FORMAT($this->total_memory_allocated).")",
            'time' => TIME::FORMAT($this->time),
        ];
    }

    public function format()
    {
        $this->end();
        return
            "<table>" .
//                "<tr><td>Total Memory Allocated:</td><td>" . STORAGE::FORMAT($this->total_memory_allocated) . "</td></tr>" .
//                "<tr><td>Total Memory Used:</td><td>" . STORAGE::FORMAT($this->total_memory_used) . "</td></tr>" .
//
//                "<tr><td>Memory Allocated:</td><td>" . STORAGE::FORMAT($this->memory_allocated) . "</td></tr>" .
//                "<tr><td>Memory Used:</td><td>" . STORAGE::FORMAT($this->memory_used) . "</td></tr>" .
                
                "<tr><td>Memory:</td><td>" . STORAGE::FORMAT($this->total_memory_used). " (".STORAGE::FORMAT($this->total_memory_allocated) . ")</td></tr>" .
                "<tr><td>Time:</td><td>" .TIME::FORMAT($this->time) . "</td></tr>" .
            "</table>";
    }

    public function print()
    {
        $this->end();
        print $this->format();
    }
}
