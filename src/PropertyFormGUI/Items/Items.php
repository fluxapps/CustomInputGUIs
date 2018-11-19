<?php

namespace srag\CustomInputGUIs\PropertyFormGUI\Items;

use ilCustomInputGUI;
use ilFormPropertyGUI;
use ilFormSectionHeaderGUI;
use ilPropertyFormGUI;
use ilRadioOption;
use srag\CustomInputGUIs\PropertyFormGUI\Exception\PropertyFormGUIException;
use srag\CustomInputGUIs\PropertyFormGUI\PropertyFormGUI;
use srag\CustomInputGUIs\TableGUI\TableGUI;

/**
 * Class BasePropertyFormGUI
 *
 * @package srag\CustomInputGUIs\PropertyFormGUI\Items
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @access  namespace
 */
final class Items {

	/**
	 * @param string                              $key
	 * @param array                               $field
	 * @param ilPropertyFormGUI|ilFormPropertyGUI $parent_item
	 * @param PropertyFormGUI|TableGUI            $parent
	 *
	 * @return PropertyFormGUI|ilFormSectionHeaderGUI|ilRadioOption
	 */
	public static final function getItem($key, array $field, $parent_item, $parent) {
		if (!class_exists($field[PropertyFormGUI::PROPERTY_CLASS])) {
			throw new PropertyFormGUIException("Class " . $field[PropertyFormGUI::PROPERTY_CLASS] . " not exists!");
		}

		/**
		 * @var ilFormPropertyGUI|ilFormSectionHeaderGUI|ilRadioOption $item
		 */
		$item = new $field[PropertyFormGUI::PROPERTY_CLASS]();

		if ($item instanceof ilFormSectionHeaderGUI) {
			$item->setTitle($parent->txt($key));
		} else {
			if ($item instanceof ilRadioOption) {
				$item->setTitle($parent->txt($parent_item->getPostVar() . "_" . $key));

				$item->setValue($key);
			} else {
				$item->setTitle($parent->txt($key));

				$item->setPostVar($key);
			}
		}

		$item->setInfo($parent->txt($key . "_info", ""));

		self::setPropertiesToItem($item, $field);

		return $item;
	}


	/**
	 * @param ilFormPropertyGUI|ilFormSectionHeaderGUI|ilRadioOption $item
	 *
	 * @return mixed
	 */
	public static function getValueFromItem($item) {
		if (method_exists($item, "getChecked")) {
			return boolval($item->getChecked());
		}

		if (method_exists($item, "getDate")) {
			return $item->getDate();
		}

		if (!($item instanceof ilCustomInputGUI)) {
			if ($item->getMulti()) {
				return $item->getMultiValues();
			} else {
				$value = $item->getValue();

				if (empty($value)) {
					$value = "";
				}

				return $value;
			}
		}

		return NULL;
	}


	/**
	 * @param ilFormPropertyGUI|ilFormSectionHeaderGUI|ilRadioOption $item
	 * @param array                                                  $properties
	 */
	private static function setPropertiesToItem($item, array $properties)/*: void*/ {
		foreach ($properties as $property_key => $property_value) {
			$property = "";

			switch ($property_key) {
				case PropertyFormGUI::PROPERTY_DISABLED:
					$property = "setDisabled";
					break;

				case PropertyFormGUI::PROPERTY_MULTI:
					$property = "setMulti";
					break;

				case PropertyFormGUI::PROPERTY_OPTIONS:
					$property = "setOptions";
					$property_value = [ $property_value ];
					break;

				case PropertyFormGUI::PROPERTY_REQUIRED:
					$property = "setRequired";
					break;

				case PropertyFormGUI::PROPERTY_CLASS:
				case PropertyFormGUI::PROPERTY_SUBITEMS:
					break;

				default:
					$property = $property_key;
					break;
			}

			if (!empty($property)) {
				if (!is_array($property_value)) {
					$property_value = [ $property_value ];
				}

				call_user_func_array([ $item, $property ], $property_value);
			}
		}
	}


	/**
	 * @param ilFormPropertyGUI|ilFormSectionHeaderGUI|ilRadioOption $item
	 * @param mixed                                                  $value
	 */
	public static function setValueToItem($item, $value)/*: void*/ {
		if (method_exists($item, "setChecked")) {
			$item->setChecked($value);

			return;
		}

		if (method_exists($item, "setDate")) {
			$item->setDate($value);

			return;
		}

		if (!($item instanceof ilRadioOption || $item instanceof ilCustomInputGUI)) {
			$item->setValue($value);
		}
	}


	/**
	 * Items constructor
	 */
	private function __construct() {

	}
}
