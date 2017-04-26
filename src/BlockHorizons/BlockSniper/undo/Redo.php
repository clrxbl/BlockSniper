<?php

namespace BlockHorizons\BlockSniper\undo;

use pocketmine\block\Block;

class Redo {

	private $redoBlocks;

	/**
	 * @param Block[]    $redoBlocks
	 */
	public function __construct(array $redoBlocks) {
		$this->redoBlocks = $redoBlocks;
	}

	public function restore() {
		foreach($this->redoBlocks as $redoBlock) {
			$redoBlock->getLevel()->setBlock($redoBlock, $redoBlock, false, false);
		}
	}

	/**
	 * @return array
	 */
	public function getDetachedUndoBlocks(): array {
		$undoBlocks = [];
		foreach($this->redoBlocks as $redoBlock) {
			$undoBlocks[] = $redoBlock->getLevel()->getBlock($redoBlock);
		}

		return $undoBlocks;
	}

	/**
	 * @return Block[]
	 */
	public function getBlocks(): array {
		return $this->redoBlocks;
	}

	/**
	 * @return int
	 */
	public function getBlockCount(): int {
		return count($this->redoBlocks);
	}
}