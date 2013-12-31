<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Raven log writer. Writes out messages and stores them in Sentry.
 *
 * @package    Kohana
 * @category   Logging
 * @author     Kohana Team
 * @copyright  (c) 2008-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_Log_Raven extends Log_Writer {

    protected $raven;

    /**
     * Creates a new raven logger. 
     *
     *     $writer = new Raven_Log();
     *
     * @param   string  log directory
     * @return  void
     */
    public function __construct()
    {
        if (($dsn = Kohana::$config->load('raven.default.dsn')) && !empty($dsn)) {
            $this->raven = new Raven_Client($dsn);
        }else{
            throw new Kohana_Exception('Fail to create Raven Client');
        }
    }

    /**
     * Writes each of the messages into the raven. 
     *
     *     $writer->write($messages);
     *
     * @param   array   messages
     * @return  void
     */
    public function write(array $messages)
    {
        foreach ($messages as $message)
        {
            // Write each message into the log file
            // Format: time --- level: body
            $this->raven->getIdent($this->raven->captureMessage($message['body'], array(), array('level'=>$this->mapRavenLevel($message['level']))));
        }
    }

    private function mapRavenLevel($level) {
        switch($level) {
            case 0: return Raven_Client::FATAL;
            case 1: return Raven_Client::FATAL;
            case 2: return Raven_Client::FATAL;
            case 3: return Raven_Client::ERROR;
            case 4: return Raven_Client::WARNING;
            case 5: return Raven_Client::INFO;
            case 6: return Raven_Client::INFO;
            case 7: return Raven_Client::DEBUG;
            case 8: return Raven_Client::DEBUG;
        }
        return Raven_Client::INFO;
    }
}