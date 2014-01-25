<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BpAnalysisHelper extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'bps:grab';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run grabAll method of assigned grabber queue.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $classname = $value = $this->argument('classname');
        if (class_exists($classname)) {
            $this->info('Grabbing...');
            // Init links grabber queue object and call init method
            $queue = new $classname();
            // grab all links from remote page.
            $queue->grabAll();

            $this->info('Grab completed.');
        }
        else {
            $this->error('Class ' . $classname . ' not exists');
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('classname', InputArgument::REQUIRED, 'Implementation of HtmlGrabberQueue'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
