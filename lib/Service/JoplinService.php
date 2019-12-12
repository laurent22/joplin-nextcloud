<?php
namespace OCA\Joplin\Service;

use OCA\Joplin\Service\ModelService;
use OCA\Joplin\Service\FilesService;
use OCA\Joplin\Service\ServerService;
use OCA\Joplin\Service\JoplinUtils;
use OCP\AppFramework\Http\TemplateResponse;
use OCA\Joplin\Error\NotFoundException;

class JoplinService {

	private $models_;
	private $fileService_;
	private $serverService_;

	public function __construct(ModelService $ModelService, FilesService $FilesService, ServerService $ServerService) {
		$this->models_ = $ModelService;
		$this->fileService_ = $FilesService;
		$this->serverService_ = $ServerService;
	}

	private function templateDir() {
		return dirname(dirname(dirname(__FILE__))) . '/templates';
	}

	public function item($userId, $syncTargetId, $itemId) {
		$syncTarget = $this->models_->get('syncTarget')->fetchByUserAndId($userId, $syncTargetId);
		if (!$syncTarget) throw new NotFoundException("Could not find sync target \"$syncTargetId\"");
		$noteContent = $this->fileService_->noteFileContent($userId, $syncTarget['path'], $itemId);
		return JoplinUtils::unserializeItem($noteContent);
	}

	public function note($userId, $syncTargetId, $noteId) {
		$note = $this->item($userId, $syncTargetId, $noteId);
		if ($note['type_'] !== 1) throw new NotFoundException("Could not find note $noteId on sync target $syncTargetId");
		return $note;
	}

	private function loadViewContent($name) {
		$filePath = $this->templateDir() . '/' . $name . '.mustache';
		$content = @file_get_contents($filePath);
		if ($content === false) throw new \Exception('Could not load view: ' . $filePath);
		return $content;
	}

	public function renderView($pageName, $view = []) {
		$templateContent = $this->loadViewContent('template');
		$pageContent = $this->loadViewContent($pageName);

		$mustache = new \Mustache_Engine();
		$pageHtml = $mustache->render($pageContent, $view);

		$templateView = [
			'pageHtml' => $pageHtml,
			'jsFiles' => [],
			'cssFiles' => [],
		];

		if (isset($view['jsFiles'])) {
			$templateView['jsFiles'] = [];
			foreach ($view['jsFiles'] as $jsFile) {
				$url = $this->serverService_->baseUrl() . '/js/' . $jsFile . '.js';
				$templateView['jsFiles'][] = [
					'url' => $url,
					'nonce' => $this->serverService_->getNonce(),
				];
			}
		}

		if (isset($view['cssFiles'])) {
			$templateView['cssFiles'] = [];
			foreach ($view['cssFiles'] as $jsFile) {
				$url = $this->serverService_->baseUrl() . '/css/' . $jsFile . '.css';
				$templateView['cssFiles'][] = [
					'url' => $url,
				];
			}
		}

		$templateHtml = $mustache->render($templateContent, $templateView);
		
		return new TemplateResponse('joplin', 'index', ['pageHtml' => $templateHtml]);
	}

}