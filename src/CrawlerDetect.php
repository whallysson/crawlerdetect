<?php

namespace CodeBlog\CrawlerDetect;

/**
 * Class CodeBlog CrawlerDetect
 *
 * @author Whallysson Avelino <https://github.com/whallysson>
 * @package CodeBlog\CrawlerDetect
 */

use CodeBlog\CrawlerDetect\Fixtures\Headers;
use CodeBlog\CrawlerDetect\Fixtures\Crawlers;
use CodeBlog\CrawlerDetect\Fixtures\Exclusions;

class CrawlerDetect {

    /** @var null */
    protected $userAgent = null;

    /** @var array */
    protected $httpHeaders = array();

    /** @var array */
    protected $matches = array();

    /** @var \CodeBlog\CrawlerDetect\Fixtures\Crawlers */
    protected $crawlers;

    /** @var \CodeBlog\CrawlerDetect\Fixtures\Exclusions */
    protected $exclusions;

    /** @var \CodeBlog\CrawlerDetect\Fixtures\Headers */
    protected $uaHttpHeaders;

    /** @var string */
    protected $compiledRegex;

    /** @var string */
    protected $compiledExclusions;

    /**
     * Class constructor
     */
    public function __construct(array $headers = null, $userAgent = null) {
        $this->crawlers = new Crawlers();
        $this->exclusions = new Exclusions();
        $this->uaHttpHeaders = new Headers();

        $this->compiledRegex = $this->compileRegex($this->crawlers->getAll());
        $this->compiledExclusions = $this->compileRegex($this->exclusions->getAll());

        $this->setHttpHeaders($headers);
        $this->setUserAgent($userAgent);
    }

    /**
     * Compile the regex patterns into one regex string.
     *
     * @param array
     * 
     * @return string
     */
    public function compileRegex(array $patterns) {
        return '(' . implode('|', $patterns) . ')';
    }

    /**
     * Set HTTP headers.
     *
     * @param array|null $httpHeaders
     */
    public function setHttpHeaders($httpHeaders = null) {
        // Use global _SERVER if $httpHeaders aren't defined.
        if (!is_array($httpHeaders) || !count($httpHeaders)) {
            $httpHeaders = $_SERVER;
        }

        // Clear existing headers.
        $this->httpHeaders = array();

        // Only save HTTP headers. In PHP land, that means
        // only _SERVER vars that start with HTTP_.
        foreach ($httpHeaders as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $this->httpHeaders[$key] = $value;
            }
        }
    }

    /**
     * Return user agent headers.
     *
     * @return array
     */
    public function getUaHttpHeaders() {
        return $this->uaHttpHeaders->getAll();
    }

    /**
     * Set the user agent.
     *
     * @param string $userAgent
     */
    public function setUserAgent($userAgent) {
        if (is_null($userAgent)) {
            foreach ($this->getUaHttpHeaders() as $altHeader) {
                if (isset($this->httpHeaders[$altHeader])) {
                    $userAgent .= $this->httpHeaders[$altHeader] . ' ';
                }
            }
        }

        return $this->userAgent = $userAgent;
    }

    /**
     * Check user agent string against the regex.
     *
     * @param string|null $userAgent
     *
     * @return bool
     */
    public function isCrawler(string $userAgent = null) {
        $agent = trim(preg_replace(
                        "/{$this->compiledExclusions}/i",
                        '',
                        $userAgent ?: $this->userAgent
        ));

        if ($agent == '') {
            return false;
        }

        $result = preg_match("/{$this->compiledRegex}/i", $agent, $matches);

        if ($matches) {
            $this->matches = $matches;
        }

        return (bool) $result;
    }

    /**
     * Return the matches.
     *
     * @return string|null
     */
    public function getMatches() {
        return isset($this->matches[0]) ? $this->matches[0] : null;
    }

}
