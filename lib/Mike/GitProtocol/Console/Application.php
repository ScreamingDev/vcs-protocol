<?php

namespace Mike\GitProtocol\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Application extends \Symfony\Component\Console\Application {
	public function __construct() {
		$name = str_replace( '\\', ' ', __NAMESPACE__ );
		parent::__construct( $name, '1.0.0' );

		$finder = new Finder();
		$finder->name( '*Command.php' )->in( __DIR__ );

		$lastClass = '';
		foreach ( $finder as $commandFile ) {
			/** @var SplFileInfo $commandFile */

			require_once $commandFile->getRealPath();

			$currentClass = array_slice( get_declared_classes(), - 1 );
			$currentClass = current( $currentClass );

			if ( $currentClass == $lastClass ) {
				continue;
			}

			$name = str_replace( __NAMESPACE__ . '\\', '', $currentClass );
			$name = preg_replace( '/Command$/', '', $name );
			$name = str_replace( '\\', ':', $name );
			$name = strtolower( $name );

			$reflectClass = new \ReflectionClass( $currentClass );
			$comment      = $reflectClass->getDocComment();
			preg_match( '/\s\*\s.*\./', $comment, $description );
			$description = preg_replace( '/^\s\*\s/', '', current( $description ) );

			preg_match( '/(?<=\/\*\*).*(?=\n\s*\*\s@)/s', $comment, $help );      // get long description
			$help = preg_replace( '/\s*\*\s*/s', "\n", current( $help ) );          // remove comment-star
			$help = preg_replace( '/([^\n])\n{1}([^\n])/s', '$1 $2', $help );     // remove single new-lines
			$help = str_replace( "\n", "\n ", $help );                            // indent a bit

			/** @var Command $command */
			$command = new $currentClass( $name );
			$command->setDescription( $description );
			$command->setHelp( $help );

			$this->add( $command );
			$lastClass = $currentClass;
		}

	}
}