<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestEmailCommand extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group       = 'App';
    protected $name        = 'testemail';
    protected $description = 'Sends a test email to verify SMTP configuration';
    protected $usage       = 'testemail';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $email = \Config\Services::email();
        $email->setFrom('hola@axiscondo.mx', 'AxisCondo Test');
        $email->setTo('hola@axiscondo.mx');
        $email->setSubject('Test Email Config CLI');
        $email->setMessage('This is a test message to verify SMTP config from CLI.');

        if ($email->send()) {
            CLI::write('SUCCESS: Email sent successfully.', 'green');
        } else {
            CLI::write('FAILED: Could not send email.', 'red');
            CLI::write($email->printDebugger(['headers']));
        }
    }
}
