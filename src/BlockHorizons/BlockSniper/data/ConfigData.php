<?php

declare(strict_types = 1);

namespace BlockHorizons\BlockSniper\data;

use BlockHorizons\BlockSniper\Loader;
use pocketmine\utils\TextFormat as TF;

class ConfigData {

	private $settings = [];
	private $loader;

	public function __construct(Loader $loader) {
		$this->loader = $loader;

		$this->collectSettings();
	}

	public function collectSettings() {
		$cfg = yaml_parse_file($this->getLoader()->getDataFolder() . "settings.yml");
		$this->settings = @[
			"Configuration-Version" => $cfg["Configuration-Version"],
			"Auto-Configuration-Update" => $cfg["Auto-Configuration-Update"] ?? true,
			"Message-Language" => $cfg["Message-Language"] ?? "",
			"Brush-Item" => $cfg["Brush-Item"] ?? 396,
			"Maximum-Size" => $cfg["Maximum-Size"] ?? 15,
			"Maximum-Undo-Stores" => $cfg["Maximum-Undo-Stores"] ?? 15,
			"Reset-Decrement-Brush" => $cfg["Reset-Decrement-Brush"] ?? true,
			"Maximum-Clone-Size" => $cfg["Maximum-Clone-Size"] ?? 60,
			"Save-Brush-Properties" => $cfg["Save-Brush-Properties"] ?? true,
			"Drop-Leafblower-Plants" => $cfg["Drop-Leafblower-Plants"] ?? true,
			"Save-Air-In-Copy" => $cfg["Save-Air-In-Copy"] ?? false,
			"Asynchronous-Operation-Size" => $cfg["Asynchronous-Operation-Size"] ?? 15
		];
		if($cfg["Configuration-Version"] !== Loader::CONFIGURATION_VERSION) {
			$autoUpdate = $cfg["Auto-Configuration-Update"];
			$this->getLoader()->getLogger()->info(TF::AQUA . "[BlockSniper] A new Configuration version was found. " . ($autoUpdate ? "Updating Configuration file..." : null));
			if($autoUpdate) {
				$this->updateConfig();
			}
		} else {
			$this->getLoader()->getLogger()->info(TF::AQUA . "[BlockSniper] No new Configuration version found, Configuration is up to date.");
		}
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}

	public function updateConfig() {
		unlink($this->getLoader()->getDataFolder() . "settings.yml");
		$this->settings["Configuration-Version"] = Loader::CONFIGURATION_VERSION;
		yaml_emit_file($this->getLoader()->getDataFolder() . "settings.yml", $this->settings);
	}

	/**
	 * @return string
	 */
	public function getLanguage(): string {
		return (string) $this->settings["Message-Language"];
	}

	/**
	 * @return int
	 */
	public function getBrushItem(): int {
		return (int) $this->settings["Brush-Item"];
	}

	/**
	 * @return int
	 */
	public function getMaxRadius(): int {
		return (int) $this->settings["Maximum-Size"];
	}

	/**
	 * @return int
	 */
	public function getMaxUndoStores(): int {
		return (int) $this->settings["Maximum-Undo-Stores"];
	}

	/**
	 * @return bool
	 */
	public function resetDecrementBrush(): bool {
		return (bool) $this->settings["Reset-Decrement-Brush"];
	}

	/**
	 * @return int
	 */
	public function getMaxCloneSize(): int {
		return $this->settings["Maximum-Clone-Size"];
	}

	/**
	 * @return bool
	 */
	public function saveBrushProperties(): bool {
		return (bool) $this->settings["Save-Brush-Properties"];
	}

	/**
	 * @return bool
	 */
	public function dropLeafblowerPlants(): bool {
		return (bool) $this->settings["Drop-Leafblower-Plants"];
	}

	/**
	 * @return bool
	 */
	public function saveAirInCopy(): bool {
		return (bool) $this->settings["Save-Air-In-Copy"];
	}

	/**
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function get(string $key) {
		if(isset($this->settings[$key])) {
			return $this->settings[$key];
		}
		return null;
	}

	/**
	 * @param string $key
	 * @param        $value
	 */
	public function set(string $key, $value) {
		$this->settings[$key] = $value;
	}

	/**
	 * @return int
	 */
	public function getMinimumAsynchronousSize(): int {
		return (int) $this->settings["Asynchronous-Operation-Size"];
	}

	public function save() {
		yaml_emit_file($this->getLoader()->getDataFolder() . "settings.yml", $this->settings);
	}
}