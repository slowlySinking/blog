<?php

namespace PostBundle\Twig;

use PostBundle\Utils\Markdown;
use Symfony\Component\Intl\Intl;

class PostExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $locales;

    /**
     * @var Markdown
     */
    private $parser;

    /**
     * PostExtension constructor.
     *
     * @param Markdown $parser
     * @param $locales
     */
    public function __construct(Markdown $parser, $locales)
    {
        $this->locales = $locales;
        $this->parser = $parser;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'post_extension';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('locales', [$this, 'getLocales'])
        ];
    }

    /**
     * @return array
     */
    public function getLocales()
    {
        $localeCodes = explode('|', $this->locales);

        $locales = [];
        foreach ($localeCodes as $localeCode) {
            $locales[] = [
                'code' => $localeCode,
                'name' => Intl::getLocaleBundle()->getLocaleName($localeCode, $localeCode)
            ];
        }

        return $locales;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('md2html', [$this, 'markdownToHtml'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @param $content
     * @return string
     */
    public function markdownToHtml($content)
    {
        return $this->parser->toHtml($content);
    }
}