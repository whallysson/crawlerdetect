<?php

namespace CodeBlog\CrawlerDetect\Fixtures;

/**
 * Class CodeBlog AbstractProvider
 *
 * @author Whallysson Avelino <https://github.com/whallysson>
 * @package CodeBlog\AbstractProvider
 */

abstract class AbstractProvider {

    /** @var array */
    protected $data;

    /**
     * Return the data set.
     * 
     * @return array
     */
    public function getAll() {
        return $this->data;
    }

}
