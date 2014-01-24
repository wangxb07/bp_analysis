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
	protected $name = 'bps:historyurl';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run LinksHtmlGrabberQueue grabAll method.';

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
        $url = $value = $this->argument('url');

        $this->info('Grabbing...');
        // Init links grabber queue object and call init method
        $queue = new LinksHtmlGrabberQueue();
        // grab all links from remote page.
        $queue->grabAll($url);

        $this->info('Grab completed.');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('url', InputArgument::OPTIONAL, 'Seed url for grabber.'),
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
