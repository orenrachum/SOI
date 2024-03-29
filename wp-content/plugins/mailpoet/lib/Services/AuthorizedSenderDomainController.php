<?php declare(strict_types = 1);

namespace MailPoet\Services;

if (!defined('ABSPATH')) exit;


use MailPoet\Mailer\Mailer;
use MailPoet\Newsletter\Statistics\NewsletterStatisticsRepository;
use MailPoet\Services\Bridge\API;
use MailPoet\Settings\SettingsController;
use MailPoet\Util\License\Features\Subscribers;
use MailPoetVendor\Carbon\Carbon;

class AuthorizedSenderDomainController {
  const DOMAIN_VERIFICATION_STATUS_VALID = 'valid';
  const DOMAIN_VERIFICATION_STATUS_INVALID = 'invalid';
  const DOMAIN_VERIFICATION_STATUS_PENDING = 'pending';

  const OVERALL_STATUS_VERIFIED = 'verified';
  const OVERALL_STATUS_PARTIALLY_VERIFIED = 'partially-verified';
  const OVERALL_STATUS_UNVERIFIED = 'unverified';

  const AUTHORIZED_SENDER_DOMAIN_ERROR_ALREADY_CREATED = 'Sender domain exist';
  const AUTHORIZED_SENDER_DOMAIN_ERROR_NOT_CREATED = 'Sender domain does not exist';
  const AUTHORIZED_SENDER_DOMAIN_ERROR_ALREADY_VERIFIED = 'Sender domain already verified';

  const LOWER_LIMIT = 500;
  const UPPER_LIMIT = 1000;

  const ENFORCEMENT_START_TIME = '2024-02-01 00:00:00 UTC';

  const INSTALLED_AFTER_NEW_RESTRICTIONS_OPTION = 'installed_after_new_domain_restrictions';

  /** @var Bridge */
  private $bridge;

  /** @var NewsletterStatisticsRepository  */
  private $newsletterStatisticsRepository;

  /** @var SettingsController  */
  private $settingsController;

  /** @var null|array Cached response for with authorized domains */
  private $currentRecords = null;

  /** @var null|array */
  private $currentRawData = null;

  /** @var Subscribers */
  private $subscribers;

  public function __construct(
    Bridge $bridge,
    NewsletterStatisticsRepository $newsletterStatisticsRepository,
    SettingsController $settingsController,
    Subscribers $subscribers
  ) {
    $this->bridge = $bridge;
    $this->newsletterStatisticsRepository = $newsletterStatisticsRepository;
    $this->settingsController = $settingsController;
    $this->subscribers = $subscribers;
  }

  /**
   * Get record of Bridge::getAuthorizedSenderDomains
   */
  public function getDomainRecords(string $domain = ''): array {
    $records = $this->getAllRecords();
    if ($domain) {
      return $records[$domain] ?? [];
    }
    return $records;
  }

  /**
   * Get all Authorized Sender Domains
   *
   * Note: This includes both verified and unverified domains
   */
  public function getAllSenderDomains(): array {
    return $this->returnAllDomains($this->getAllRecords());
  }

  public function getAllSenderDomainsIgnoringCache(): array {
    $this->currentRecords = null;
    return $this->getAllSenderDomains();
  }

  /**
   * Get all Verified Sender Domains
   */
  public function getVerifiedSenderDomains(): array {
    return $this->returnVerifiedDomains($this->getAllRecords());
  }

  public function getVerifiedSenderDomainsIgnoringCache(): array {
    $this->currentRecords = null;
    return $this->getVerifiedSenderDomains();
  }

  /**
   * Create new Sender Domain
   *
   * Throws an InvalidArgumentException if domain already exist
   *
   * returns an Array of DNS response or array of error
   */
  public function createAuthorizedSenderDomain(string $domain): array {
    $allDomains = $this->getAllSenderDomains();

    $alreadyExist = in_array($domain, $allDomains);

    if ($alreadyExist) {
      // sender domain already created. skip making new request
      throw new \InvalidArgumentException(self::AUTHORIZED_SENDER_DOMAIN_ERROR_ALREADY_CREATED);
    }

    $response = $this->bridge->createAuthorizedSenderDomain($domain);

    if ($response['status'] === API::RESPONSE_STATUS_ERROR) {
      throw new \InvalidArgumentException($response['message']);
    }

    // Reset cached value since a new domain was added
    $this->currentRecords = null;

    return $response;
  }

  public function getRewrittenEmailAddress(string $email): string {
    return sprintf('%s@replies.sendingservice.net', str_replace('@', '=', $email));
  }

  /**
   * Verify Sender Domain
   *
   * Throws an InvalidArgumentException if domain does not exist or domain is already verified
   *
   * * returns [ok: bool, dns: array] if domain verification is successful
   * * or [ok: bool, error:  string, dns: array] if domain verification failed
   * * or [error: string, status: bool] for other errors
   */
  public function verifyAuthorizedSenderDomain(string $domain): array {
    $records = $this->bridge->getAuthorizedSenderDomains();

    $allDomains = $this->returnAllDomains($records);
    $alreadyExist = in_array($domain, $allDomains);

    if (!$alreadyExist) {
      // can't verify a domain that does not exist
      throw new \InvalidArgumentException(self::AUTHORIZED_SENDER_DOMAIN_ERROR_NOT_CREATED);
    }

    $verifiedDomains = $this->getFullyVerifiedSenderDomains(true);
    $alreadyVerified = in_array($domain, $verifiedDomains);

    if ($alreadyVerified) {
      // no need to reverify an already verified domain
      throw new \InvalidArgumentException(self::AUTHORIZED_SENDER_DOMAIN_ERROR_ALREADY_VERIFIED);
    }

    $response = $this->bridge->verifyAuthorizedSenderDomain($domain);

    // API response contains status, but we need to check that dns array is not included
    if ($response['status'] === API::RESPONSE_STATUS_ERROR && !isset($response['dns'])) {
      throw new \InvalidArgumentException($response['message']);
    }

    return $response;
  }

  public function getSenderDomainsByStatus(array $status): array {
    return array_filter($this->getAllRawData(), function(array $senderDomainData) use ($status) {
      return in_array($senderDomainData['domain_status'] ?? null, $status);
    });
  }

  public function getFullyVerifiedSenderDomains($domainsOnly = false): array {
    $domainData = $this->getSenderDomainsByStatus([self::OVERALL_STATUS_VERIFIED]);
    return $domainsOnly ? $this->extractDomains($domainData) : $domainData;
  }

  public function getPartiallyVerifiedSenderDomains($domainsOnly = false): array {
    $domainData = $this->getSenderDomainsByStatus([self::OVERALL_STATUS_PARTIALLY_VERIFIED]);
    return $domainsOnly ? $this->extractDomains($domainData) : $domainData;
  }

  public function getUnverifiedSenderDomains($domainsOnly = false): array {
    $domainData = $this->getSenderDomainsByStatus([self::OVERALL_STATUS_UNVERIFIED]);
    return $domainsOnly ? $this->extractDomains($domainData) : $domainData;
  }

  public function getFullyOrPartiallyVerifiedSenderDomains($domainsOnly = false): array {
    $domainData = $this->getSenderDomainsByStatus([self::OVERALL_STATUS_PARTIALLY_VERIFIED,self::OVERALL_STATUS_VERIFIED]);
    return $domainsOnly ? $this->extractDomains($domainData) : $domainData;
  }

  private function extractDomains(array $domainData): array {
    $extractedDomains = [];
    foreach ($domainData as $data) {
      $extractedDomains[] = $this->domainExtractor($data);
    }
    return $extractedDomains;
  }

  private function domainExtractor(array $domainData): string {
    return $domainData['domain'] ?? '';
  }

  public function getSenderDomainsGroupedByStatus(): array {
    $groupedDomains = [];
    foreach ($this->getAllRawData() as $senderDomainData) {
      $status = $senderDomainData['domain_status'] ?? 'unknown';
      if (!isset($groupedDomains[$status])) {
        $groupedDomains[$status] = [];
      }
      $groupedDomains[$status][] = $senderDomainData;
    }
    return $groupedDomains;
  }

  /**
   * Little helper function to return All Domains. alias to `array_keys`
   *
   * The domain is the key returned from the Bridge::getAuthorizedSenderDomains
   */
  private function returnAllDomains(array $records): array {
    $domains = array_keys($records);
    return $domains;
  }

  /**
   * Little helper function to return All verified domains
   */
  private function returnVerifiedDomains(array $records): array {
    $verifiedDomains = [];

    foreach ($records as $key => $value) {
      if (count($value) < 3) continue;
      [$domainKey1, $domainKey2, $secretRecord] = $value;
      if (
        $domainKey1['status'] === self::DOMAIN_VERIFICATION_STATUS_VALID &&
        $domainKey2['status'] === self::DOMAIN_VERIFICATION_STATUS_VALID &&
        $secretRecord['status'] === self::DOMAIN_VERIFICATION_STATUS_VALID
      ) {
        $verifiedDomains[] = $key;
      }
    }

    return $verifiedDomains;
  }

  private function getAllRawData(): array {
    if ($this->currentRawData === null) {
      $this->currentRawData = $this->bridge->getRawSenderDomainData();
    }
    return $this->currentRawData;
  }

  private function getAllRecords(): array {
    if ($this->currentRecords === null) {
      $this->currentRecords = $this->bridge->getAuthorizedSenderDomains();
    }
    return $this->currentRecords;
  }

  // TODO: Remove after the enforcement date has passed
  public function isEnforcementOfNewRestrictionsInEffect(): bool {
    return Carbon::now() >= Carbon::parse(self::ENFORCEMENT_START_TIME);
  }

  public function isNewUser(): bool {
    $installedVersion = $this->settingsController->get('version');

    // Setup wizard has not been completed
    if ($installedVersion === null) {
      return true;
    }

    $installedAfterNewDomainRestrictions = $this->settingsController->get(self::INSTALLED_AFTER_NEW_RESTRICTIONS_OPTION, false);

    if ($installedAfterNewDomainRestrictions) {
      return true;
    }

    return $this->newsletterStatisticsRepository->countBy([]) === 0;
  }

  public function isSmallSender(): bool {
    return $this->subscribers->getSubscribersCount() <= self::LOWER_LIMIT;
  }

  public function isAuthorizedDomainRequiredForNewCampaigns(): bool {
    if ($this->settingsController->get('mta.method') !== Mailer::METHOD_MAILPOET) {
      return false;
    }

    // TODO: Remove after the enforcement date has passed
    if (!$this->isNewUser() && !$this->isEnforcementOfNewRestrictionsInEffect()) {
      return false;
    }

    return !$this->isSmallSender();
  }
}
