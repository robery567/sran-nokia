<?php
/**
 * Created by PhpStorm.
 * User: alexb
 * Date: 2019-04-02
 * Time: 15:54
 */

namespace App\Command\Cron;


use App\Repository\CronRepository;
use DateTimeImmutable;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunOneCommand extends Command {

	private $cronRepository;

	private $now;

	protected function configure() : void {
		$this->setName('app:cron:run:one')->setDescription('Runs the cron jobs with the given name.');

	}

	/**
	 * RunDueCommand constructor.
	 * @param string|null            $name
	 * @param CronRepository         $cronRepository
	 * @param DateTimeImmutable|null $now
	 * @throws \Exception
	 */
	public function __construct(?string $name, CronRepository $cronRepository, ?DateTimeImmutable $now = null) {
		$this->cronRepository = $cronRepository;
		if($now === null){
			$now = new DateTimeImmutable();
		}
		$this->now = $now;

		parent::__construct($name);
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$cron = $this->cronRepository->find($input->getFirstArgument());
		if($cron === null){
			throw new RuntimeException('Cron not found');
		}
		$cron->run($this->now, $output);
	}

}