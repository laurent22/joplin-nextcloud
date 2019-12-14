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

	private function appDir() {
		return dirname(dirname(dirname(__FILE__)));
	}

	private function templateDir() {
		return $this->appDir() . '/templates';
	}

	private function cssDir() {
		return $this->appDir() . '/css';
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

	private function mustache() {
		return new \Mustache_Engine();
	}

	public function renderDbTable($headers, $rows) {
		$outputRows = [];
		foreach ($rows as $row) {
			$r = [];
			foreach ($headers as $k => $header) {
				$r[] = $row[$k];
			}
			$outputRows[] = $r;
		}

		$outputHeaders = [];
		foreach ($headers as $k => $header) {
			$outputHeaders[] = $header['label'];
		}

		return $this->renderView('content/dbTable', [
			'rows' => $outputRows,
			'headers' => $outputHeaders,
		]);
	}

	public function renderView($viewName, $view = []) {
		$viewContent = $this->loadViewContent($viewName);
		return $this->mustache()->render($viewContent, $view);
	}

	private function cssUrl($name) {
		$filename =  $name . '.min.css';
		if (file_exists($this->cssDir() . '/' . $filename)) return $this->serverService_->baseUrl() . '/css/' . $filename;

		$filename =  $name . '.css';
		if (file_exists($this->cssDir() . '/' . $filename)) return $this->serverService_->baseUrl() . '/css/' . $filename;

		throw new \Exception('Cannot find CSS file: ' . $name);
	}

	public function renderTemplate($pageName, $view = []) {
		$templateContent = $this->loadViewContent('template');

		$templateView = [
			'pageHtml' => $this->renderView($pageName, $view),
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

		$cssFiles = array_merge(['pure', 'style'], isset($view['cssFiles']) ? $view['cssFiles'] : []);
		$templateView['cssFiles'] = [];
		foreach ($cssFiles as $cssFile) {
			$url = $this->cssUrl($cssFile);
			$templateView['cssFiles'][] = [
				'url' => $url,
			];
		}

		$templateHtml = $this->mustache()->render($templateContent, $templateView);

		return new TemplateResponse('joplin', 'index', ['pageHtml' => $templateHtml]);
	}

}