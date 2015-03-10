<?php namespace Admsa\Larachet\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Admsa\Larachet\Library\PushServer;

class LarachetCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larachet:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run ratchet server';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PushServer $pushServer)
    {
        return $pushServer->run();
    }

}
