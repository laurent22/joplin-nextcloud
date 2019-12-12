<?php
namespace OCA\Joplin\Service;

use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\IServerContainer;

class ServerService {

	private $request_;
	private $urlGenerator_;
	private $serverContainer_;

	public function __construct(IURLGenerator $urlGenerator, IRequest $request, IServerContainer $ServerContainer) {
		$this->urlGenerator_ = $urlGenerator;
		$this->request_ = $request;
		$this->serverContainer_ = $ServerContainer;
	}

	public function baseUrl() {
		return trim($this->urlGenerator_->getAbsoluteURL($this->urlGenerator_->linkTo('joplin', '')), '/');
	}

	public function getNonce() {
		return $this->serverContainer_->getContentSecurityPolicyNonceManager()->getNonce();
	}

	public function getQueryParam($name, $defaultValue = null) {
		$queryString = parse_url($this->request_->getRequestUri(), PHP_URL_QUERY);
		parse_str($queryString, $query);
		if (array_key_exists($name, $query)) return $query[$name];
		return null;
	}

}