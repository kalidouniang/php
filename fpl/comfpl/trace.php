<?php
/*
 * trace.php
 */
class TraceLevel {
    public const LEVEL_VERBOSE = 0;
    public const LEVEL_INFO = 1;
    public const LEVEL_WARNING = 2;
    public const LEVEL_ERROR = 3;
}

interface TraceWriter {
    /**
     * Ecrit une trace
     * @param string (GUID) $http_session_id
     * @param string $message
     * @param TraceLevel $level
     */
    public function write_message($http_session_id,$message,$level=TraceLevel::LEVEL_VERBOSE);
    
    /**
     * Obtient la liste des traces
     */
    public function get_trace();
    
    /**
     * Efface les traces existantes
     */
    public function clear_trace();
}

class FileTraceWriter implements TraceWriter {
    function get_log_filename() {
        $log_path = get_filetracewriter_path();
        
        if (!file_exists($log_path))
            mkdir($log_path, 0777, true);
            
        $log_filename = get_filetracewriter_path()."log-".date("d-m-y").".json";
        
        return $log_filename;    
    }
    
    function write_log_to_file($log_msg) {
        $log_filename = $this->get_log_filename();
        file_put_contents($log_filename, $log_msg . "\n", FILE_APPEND);
    }
    
    public function write_message($http_session_id,$message,$level=TraceLevel::LEVEL_VERBOSE) {
        $d = new DateTime();
        
        $trace = array(
            "message" => $message,
            "level"=>$level,
            "date"=> $d->format("d/m/yy H:i:s:v"),
            "http_session_id" => $http_session_id,
            "request_uri" => $_SERVER['REQUEST_URI']
        );
        
        $json_trace = json_encode($trace);
        $this->write_log_to_file($json_trace);
    }
    
    public function get_trace() {
        $json_traces = file_get_contents($this->get_log_filename());
        
        $traces = array();
        foreach(explode("\n",$json_traces) as $json_trace) {
            $trace = json_decode($json_trace);
            
            if($trace==null)
                continue;

                $traces[] = array("message" => $trace->message ,
                    "level" => $trace->level,
                    "date" => $trace->date,
                    "http_session_id" => $trace->http_session_id,
                    "request_uri" => $trace->request_uri
                );
        }
        
        return $traces;
    }
    
    public function clear_trace() {
        unlink($this->get_log_filename());
    }
}

class SessionTraceWriter implements TraceWriter {
    private const SESSION_TRACE_ID = "session_trace_id";
    
    public function get_trace() {
        if(isset($_SESSION[SessionTraceWriter::SESSION_TRACE_ID]))
            $trace = $_SESSION[SessionTraceWriter::SESSION_TRACE_ID];
        else {
            $trace = array();
            $_SESSION[SessionTraceWriter::SESSION_TRACE_ID] = $trace;
        }
        
        return $trace;
    }
    
    public function write_message($http_session_id,$message,$level=TraceLevel::LEVEL_VERBOSE) {
        $d = new DateTime();

        $trace = $this->get_trace();
        $trace[] = array(
            "message" => $message, 
            "level"=>$level, 
            "date"=> $d->format("d/m/yy H:i:s:v"),
            "http_session_id" => $http_session_id,
            "request_uri" => $_SERVER['REQUEST_URI']
        );  
        $_SESSION[SessionTraceWriter::SESSION_TRACE_ID] = $trace;
    }
    
    public function clear_trace() {
        $_SESSION[SessionTraceWriter::SESSION_TRACE_ID] = array();
    }
}

class TraceListener {
    /**
     * Identifiant de la session HTTP
     * @var string (GUID)
     */
    public static $current_http_session_id;
    
    /**
     * Initialise l'identifiant de session HTTP
     */
    public static function set_http_session_id() {
        self::$current_http_session_id = self::new_http_session_id();
    }
    
    public static function new_http_session_id()
    {
        if (function_exists('com_create_guid')){
            \TraceListener::write_message("com_create_guid existe, OS = windows",TraceLevel::LEVEL_INFO);
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = ""
            .chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125) // "}"
            ;
            return $uuid;
        }
    }
    
    /**
     * Canal de sortie
     * @var TraceWriter
     */
    public static $trace_writer = null;
    
    public static function init_writer() {
        if(self::$trace_writer==null) {
            $cl_tracewriter = new ReflectionClass(get_trace_config());
            $trace_writer = $cl_tracewriter->newInstance();
            self::$trace_writer = $trace_writer;
        }
    }
    
    /**
     * Ecrit une trace
     * @param string $message
     * @param TraceLevel $level
     */
    public static function write_message($message,$level=TraceLevel::LEVEL_VERBOSE) {
        self::init_writer();
        self::$trace_writer->write_message(self::$current_http_session_id,$message,$level);
    }
    
    /**
     * Retourne les traces au format HTML
     * @param TraceLevel $level
     * @return string
     */
    public static function get_trace_to_html($level=TraceLevel::LEVEL_VERBOSE) {
        self::init_writer();
        $output = "<table border=1>";
        $output .= "<tr><td>Timestamp</td><td>Level</td><td>Message</td></tr>";
        
        foreach(self::$trace_writer->get_trace() as $trace) {
            if(!isset($trace['http_session_id']))
                continue;
            
            if($trace['http_session_id']!=self::$current_http_session_id)
                continue;
            
            if($trace["level"] >= $level) {
                $tlevel = "";
                switch($trace["level"]) {
                    case TraceLevel::LEVEL_VERBOSE:
                        $tlevel="Verbose";
                        break;
                    case TraceLevel::LEVEL_INFO:
                        $tlevel="Info";
                        break;
                    case TraceLevel::LEVEL_WARNING:
                        $tlevel="Warning";
                        break;
                    case TraceLevel::LEVEL_ERROR:
                        $tlevel="Error";
                        break;
                }
                $output .= "<tr>".
                            "<td>".$trace['date']."</td>".
                            "<td>".$tlevel."</td>".
                            "<td>".$trace['message']."</td>".
                            "</tr>";
                }
        }
        
        $output .="</table>";
        return $output;
    }
    
    public static function get_all_trace_to_html() {
        self::init_writer();
        $output = "";
        $sid = null;    // variable sentinelle
        
        foreach(self::$trace_writer->get_trace() as $trace) {
            if(!isset($trace['http_session_id']))
                continue;
            
            if($trace['http_session_id'] != $sid)    
            {
                // changement de session HTTP
                
                if($sid != null)
                {
                    // fermer les balises de la dernière liste de trace
                    $output .= "</table>";
                    $output .= "</div></div>";
                }
                
                $sid = $trace['http_session_id'];
                $uri = $trace['request_uri'];
                
                $output .= "<div class='row'><div class='col-lg-12'>$uri<br>";
                $output .= "<table class='table'>";
                $output .= "<tr><td>Timestamp</td><td>Level</td><td>Message</td></tr>";
            }
                  
            $tlevel = "";
            switch($trace["level"]) {
                case TraceLevel::LEVEL_VERBOSE:
                    $tlevel="Verbose";
                    break;
                case TraceLevel::LEVEL_INFO:
                    $tlevel="Info";
                    break;
                case TraceLevel::LEVEL_WARNING:
                    $tlevel="Warning";
                    break;
                case TraceLevel::LEVEL_ERROR:
                    $tlevel="Error";
                    break;
            }
            $output .= "<tr>".
                "<td>".$trace['date']."</td>".
                "<td>".$tlevel."</td>".
                "<td>".$trace['message']."</td>".
                "</tr>";
                
        }
     
        return $output;
    }
    
    /**
     * Efface les traces existantes
     */
    public static function clear_trace() {
        self::init_writer();
        self::$trace_writer->clear_trace();
    }
}
?>