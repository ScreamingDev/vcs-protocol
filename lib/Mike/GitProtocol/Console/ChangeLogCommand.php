<?php

namespace Mike\GitProtocol\Console;


use Mike\GitProtocol\Vcs\GitWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Print change log based on commit messages.
 *
 * @package Mike\GitProtocol\Console
 */
class ChangeLogCommand extends Command {
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$wrapper = new GitWrapper();

		$commitMessages = $wrapper->getCommitMessages(
			$input->getArgument('start-hash'),
			$input->getArgument('end-hash')
		);

		$output->writeln('changelog');
	}

	protected function configure() {
		$this->addArgument(
			'start-hash',
			InputArgument::REQUIRED,
			'Start reading commits at this version'
		);

		$this->addArgument(
			'end-hash',
			InputArgument::OPTIONAL,
			'Stop reading commits at this version',
			'HEAD'
		);
	}


}