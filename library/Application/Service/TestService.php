<?php

namespace Application\Service;

use Core\Service\AbstractService;

/**
 * TestService class.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class TestService extends AbstractService
{
    /**
     * Returns some random stuff, this is a sample implementation.
     *
     * @return TP\Entity\TweetDay
     */
    public function getTweetOfTheDay()
    {
        //$em = $this->getEntityManager($this->options['rw']);

        return $this->options['foo']; // returns: "bar"
    }
}
