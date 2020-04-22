<?php

declare(strict_types=1);

namespace duels\utils;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\customUI\CustomUI;
use pocketmine\customUI\elements\customForm\Dropdown;
use pocketmine\customUI\elements\customForm\Input;
use pocketmine\customUI\elements\customForm\Label;
use pocketmine\customUI\elements\customForm\Slider;
use pocketmine\customUI\elements\customForm\StepSlider;
use pocketmine\customUI\elements\customForm\Toggle;
use pocketmine\customUI\elements\simpleForm\Button;

use function is_string;
use function is_array;
use function is_int;
use function is_bool;
use function strtolower;
use function json_encode;

class Form{

	public static function createGUI(callable $function, $title = "", $content = "", $type = "form") {
		return new GuiManager($function, $title, $content, $type);
	}
}

class ButtonMC extends Button {

	public function __construct($text) {
		parent::__construct($text);
	}

	public function handle($data, $player){
	}

	public function addImage($imageType, $imagePath) {
		parent::addImage($imageType, $imagePath);
	}

}

abstract class GUI implements CustomUI {
	private $function;

	const MODAL_FORM = "modal";
	const SIMPLE_FORM = "form";
	const CUSTOM_FORM = "custom_form";

	const IMAGE_TYPE_PATH = 'path';
	const IMAGE_TYPE_URL = 'url';

	public function __construct(callable $function) {
		$this->function = $function;
	}

	public function toJSON() {
	}

	public function close($player) {
	}

	public function handle($responde, $player) {
		$responde = $this->getResult($responde);
		$function = $this->function;
		if($function !== null) {
			$function($player, $responde);
		}
	}

	public function sendForm(Player $player) {
		$player->showModal($this);
	}

	public function getResult(&$data) : array {
	}
}

class GuiManager extends GUI {
	protected $simpleui;
	protected $callable;
	protected $json = '';
	protected $typeiu = "form";
	protected $data = [];

	protected $title = '';
	protected $content = '';
	protected $buttons = [];

	protected $customdata = [];
	protected $customdatadefault = 0;
	protected $iconURL = '';


	protected $trueButtonText = '';
	protected $falseButtonText = '';

	public function __construct(callable $function, $title, $content, $type = "form") {
		parent::__construct($function);
		$this->title = $title;
		$this->content = $content;
		$this->setTypeForm($type);
	}

	public function setButton($text = "", $imageType = false, $imagePath = false) {
		$button = new ButtonMC($text);
		if ($imageType !== false and $imagePath !== false) {
			$button->addImage($imageType, $imagePath);
		}
		$this->buttons[] = $button;
		$this->json = '';
	}

	public function setTextModal(string $true, string $false) {
		$this->trueButtonText = $true;
		$this->falseButtonText = $false;
	}

	public function setTextContent(string $title, string $content) {
		$this->title = $title;
		$this->content = $content;
	}

	public function setCustomButton($formtype, $text = "", $value1 = false, $value2 = false, $value3 = false) {
		if (!is_string($text)) {
			$text = "";
		}
		switch ($formtype) {
			case 'Dropdown':
			$drop = new Dropdown($text, is_array($value1) ? $value1 : []);
			if ($value2 !== false) {
				$drop->setOptionAsDefault($value2);
			}
			$this->customdata[] = $drop;
			break;

			case "Input":
			$input = new Input($text, is_string($value1) ? $value1 : "", is_string($value2) ? $value2 : "");
			$this->customdata[] = $input;
			break;

			case "Label":
			$label = new Label($text);
			$this->customdata[] = $label;
			break;

			case "Slider":
			$slider = new Slider($text, is_int($value1) ? $value1 : 1, is_int($value2) ? $value2 : 2, is_int($value3) ? $value3 : 0);
			$this->customdata[] = $slider;
			break;

			case "Toggle":
			$toggle = new Toggle($text, is_bool($value1) ? $value1 : false);
			$this->customdata[] = $toggle;
			break;
		}
		$this->json = '';
	}

	public function getTypeForm() {
		return $this->typeiu;
	}

	public function setTypeForm($type) {
		switch (strtolower($type)) {
			case 'modal':
			$this->typeiu = self::MODAL_FORM;
			break;

			case 'form':
			$this->typeiu = self::SIMPLE_FORM;
			break;

			case 'custom':
			$this->typeiu = self::CUSTOM_FORM;
			break;

			default:
			$this->typeiu = self::SIMPLE_FORM;
			break;
		}
	}

	final public function toJSON() {
		if ($this->json != '') {
			return $this->json;
		}
		$data = [];
		switch ($this->getTypeForm()) {
			case self::CUSTOM_FORM:
			$data = [
				'type' => 'custom_form',
				'title' => $this->title,
				'content' => []
			];
			if ($this->iconURL != '') {
				$data['icon'] = [
					"type" => "url",
					"data" => $this->iconURL
				];
			}
			foreach ($this->customdata as $contentdata) {
				$data['content'][] = $contentdata->getDataToJson();
			}
			break;

			case self::SIMPLE_FORM:
			$data = [
				'type' => 'form',
				'title' => $this->title,
				'content' => $this->content,
				'buttons' => []
			];
			foreach ($this->buttons as $button) {
				$data['buttons'][] = $button->getDataToJson();
			}
			break;

			case self::MODAL_FORM:
			return $this->json = json_encode([
				'type' => 'modal',
				'title' => $this->title,
				'content' => $this->content,
				'button1' => $this->trueButtonText,
				'button2' => $this->falseButtonText,
			]);
			break;
		}
		return $this->json = json_encode($data);
	}

	final public function getResult(&$data) : array {
		return (is_array($data)) ? $data : [$data];
	}
}
