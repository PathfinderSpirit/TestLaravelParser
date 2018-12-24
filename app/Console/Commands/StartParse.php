<?php

namespace App\Console\Commands;
use App\Parser;
use Illuminate\Console\Command;

class StartParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:start';
    protected $habrParser;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts Habr.com parsing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->habrParser = new Parser();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->habrParser->startParse();
    }
}
