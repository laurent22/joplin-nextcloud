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

	public function fileBaseUrl() {
		$slash = preg_quote('/', '/');
		// The URL returned by linkToRouteAbsolute includes an "index.php" element. However when
		// loading a JS or CSS file, that element must not be present, so we remove it here.
		// Couldn't find how to return a URL that's valid for Nextcloud so it's a bit of a hack.
		return preg_replace("/$slash.[^$slash]+\.php$slash/", "/", $this->baseUrl());
	}

	public function baseUrl() {
		return trim($this->urlGenerator_->linkToRouteAbsolute('joplin.index.get'), '/');
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