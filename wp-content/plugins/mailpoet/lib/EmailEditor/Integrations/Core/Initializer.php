<?php declare(strict_types = 1);

namespace MailPoet\EmailEditor\Integrations\Core;

if (!defined('ABSPATH')) exit;


use MailPoet\EmailEditor\Engine\Renderer\BlocksRegistry;
use MailPoet\EmailEditor\Engine\Renderer\Layout\FlexLayoutRenderer;

class Initializer {
  public function initialize(): void {
    add_action('mailpoet_blocks_renderer_initialized', [$this, 'registerCoreBlocksRenderers'], 10, 1);
  }

  /**
   * Register core blocks email renderers when the blocks renderer is initialized.
   */
  public function registerCoreBlocksRenderers(BlocksRegistry $blocksRegistry): void {
    $blocksRegistry->addBlockRenderer('core/paragraph', new Renderer\Blocks\Paragraph());
    $blocksRegistry->addBlockRenderer('core/heading', new Renderer\Blocks\Heading());
    $blocksRegistry->addBlockRenderer('core/column', new Renderer\Blocks\Column());
    $blocksRegistry->addBlockRenderer('core/columns', new Renderer\Blocks\Columns());
    $blocksRegistry->addBlockRenderer('core/list', new Renderer\Blocks\ListBlock());
    $blocksRegistry->addBlockRenderer('core/image', new Renderer\Blocks\Image());
    $blocksRegistry->addBlockRenderer('core/buttons', new Renderer\Blocks\Buttons(new FlexLayoutRenderer()));
    $blocksRegistry->addBlockRenderer('core/button', new Renderer\Blocks\Button());
  }
}
