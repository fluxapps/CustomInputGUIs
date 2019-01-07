<?php

namespace srag\CustomInputGUIs;

use ILIAS\UI\Implementation\Component\Glyph\Factory as ProgressMeterFactoryCore;
use srag\CustomInputGUIs\ProgressMeter\Implementation\Factory as ProgressMeterFactory;
use srag\DIC\DICTrait;

/**
 * Class CustomInputGUIs
 *
 * @package srag\CustomInputGUIs
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @internal
 */
final class CustomInputGUIs {

	use DICTrait;
	/**
	 * @var self
	 */
	protected static $instance = NULL;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @return ProgressMeterFactoryCore|ProgressMeterFactory
	 *
	 * @since ILIAS 5.4
	 */
	public function progressMeter() {
		if (self::version()->is54()) {
			return new ProgressMeterFactoryCore();
		} else {
			return new ProgressMeterFactory();
		}
	}
}