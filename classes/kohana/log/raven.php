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
            $this->raven->getIdent($this->raven->captureMessage($message['body'], array(), array('level'=>$this->mapRavenLevel($message['level'])));
        }
    }

    private function mapRavenLevel($level) {
        switch($level) {
            case self::LOG_EMERG: return Raven_Client::FATAL;
            case self::LOG_ALERT: return Raven_Client::FATAL;
            case self::LOG_CRIT: return Raven_Client::FATAL;
            case self::LOG_ERR: return Raven_Client::ERROR;
            case self::LOG_WARNING: return Raven_Client::WARNING;
            case self::LOG_NOTICE: return Raven_Client::INFO;
            case self::LOG_INFO: return Raven_Client::INFO;
            case self::LOG_DEBUG: return Raven_Client::DEBUG;
            case 8: return Raven_Client::DEBUG;
        }
        return Raven_Client::INFO;
    }
}