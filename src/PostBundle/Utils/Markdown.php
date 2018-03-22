<?php

namespace PostBundle\Utils;


class Markdown
{
    /**
     * @var \Parsedown
     */
    private $parser;

    /**
     * @var \HTMLPurifier
     */
    private $purifier;

    /**
     * Markdown constructor.
     */
    public function __construct()
    {
        $this->parser = new \Parsedown();

        $purifierConfig = \HTMLPurifier_Config::create([
            'Cache.DefinitionImpl' => null,
        ]);
        $this->purifier = new \HTMLPurifier($purifierConfig);
    }

    /**
     * @param $text
     *
     * @return string
     */
    public function toHtml($text)
    {
        $html = $this->parser->text($text);
        $safeHtml = $this->purifier->purify($html);

        return $safeHtml;
    }
}