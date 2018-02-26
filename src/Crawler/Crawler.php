<?php
/**
 * @author  Roberto Rielo <roberto910907@gmail.com>.
 *
 * @version Crawler v1.0 21/02/18 05:02 PM
 */

namespace App\Crawler;

use Symfony\Component\CssSelector\CssSelectorConverter;

class Crawler implements \Countable, \IteratorAggregate
{
    /**
     * @var \DOMElement[]
     */
    private $nodes = array();

    /**
     * @var string The default namespace prefix to be used with XPath and CSS expressions
     */
    private $defaultNamespacePrefix = 'default';

    /**
     * @var array A map of manually registered namespaces
     */
    private $namespaces = array();

    private $isHtml = true;

    /**
     * @param mixed $htmlContent A string to use as the base for the crawling
     */
    public function __construct($htmlContent = null)
    {
        $this->add($htmlContent);
    }

    /**
     * @param int $position
     *
     * @return \DOMElement|null
     */
    public function getNode($position)
    {
        if (isset($this->nodes[$position])) {
            return $this->nodes[$position];
        }

        return null;
    }

    /**
     * Adds a string to the current list of nodes.
     *
     * @param string|null $htmlContent A node
     *
     * @throws \InvalidArgumentException when node is not the expected type
     */
    public function add($htmlContent)
    {
        if (is_string($htmlContent)) {
            $this->addContent($htmlContent);
        } elseif (null !== $htmlContent) {
            throw new \InvalidArgumentException(sprintf('Expecting a string, or null, but got "%s".', is_object($htmlContent) ? get_class($htmlContent) : gettype($htmlContent)));
        }
    }

    /**
     * Adds HTML content.
     *
     * @param string $content A string to parse as HTML
     */
    public function addContent($content)
    {
        $this->addHtmlContent($content);
    }

    /**
     * Adds an HTML content to the list of nodes.
     *
     * @param string $content The HTML content
     * @param string $charset The charset
     */
    public function addHtmlContent($content, $charset = 'UTF-8')
    {
        $internalErrors = libxml_use_internal_errors(true);
        $disableEntities = libxml_disable_entity_loader(true);

        $dom = new \DOMDocument('1.0', $charset);
        $dom->validateOnParse = true;

        set_error_handler(function () {
            throw new \Exception();
        });

        try {
            // Convert charset to HTML-entities to work around bugs in DOMDocument::loadHTML()
            $content = mb_convert_encoding($content, 'HTML-ENTITIES', $charset);
        } catch (\Exception $e) {
        }

        restore_error_handler();

        if ('' !== trim($content)) {
            @$dom->loadHTML($content);
        }

        libxml_use_internal_errors($internalErrors);
        libxml_disable_entity_loader($disableEntities);

        foreach ($dom as $domElement) {
            $this->addDocument($domElement);
        }
    }

    /**
     * Adds a \DOMDocument to the list of nodes.
     *
     * @param \DOMDocument $dom A \DOMDocument instance
     */
    public function addDocument(\DOMDocument $dom)
    {
        if ($dom->documentElement) {
            $this->addNode($dom->documentElement);
        }
    }

    /**
     * Adds a \DOMNode instance to the list of nodes.
     *
     * @param \DOMNode $node A \DOMNode instance
     */
    public function addNode(\DOMNode $node)
    {
        if ($node instanceof \DOMDocument) {
            $node = $node->documentElement;
        }

        // Don't add duplicate nodes in the Crawler
        if (in_array($node, $this->nodes, true)) {
            return;
        }

        $this->nodes[] = $node;
    }

    /**
     * Filters the list of nodes with a CSS selector.
     *
     * This method only works if you have installed the CssSelector Symfony Component.
     *
     * @param string $selector A CSS selector
     *
     * @return self
     *
     * @throws \RuntimeException if the CssSelector Component is not available
     */
    public function filter($selector)
    {
        if (!class_exists(CssSelectorConverter::class)) {
            throw new \RuntimeException('To filter with a CSS selector, install the CssSelector component ("composer require symfony/css-selector"). Or use filterXpath instead.');
        }

        $converter = new CssSelectorConverter($this->isHtml);

        // The CssSelector already prefixes the selector with descendant-or-self::
        return $this->filterRelativeXPath($converter->toXPath($selector));
    }

    /**
     * Filters the list of nodes with an XPath expression.
     *
     * The XPath expression should already be processed to apply it in the context of each node.
     *
     * @param string $xpath
     *
     * @return self
     */
    private function filterRelativeXPath($xpath)
    {
        $prefixes = $this->findNamespacePrefixes($xpath);

        $crawler = $this->createSubCrawler(null);

        foreach ($this->nodes as $node) {
            $domxpath = $this->createDOMXPath($node->ownerDocument, $prefixes);
            $crawler->add($domxpath->query($xpath, $node));
        }

        return $crawler;
    }

    /**
     * @param \DOMDocument $document
     * @param array        $prefixes
     *
     * @throws \InvalidArgumentException
     *
     * @return \DOMXPath
     */
    private function createDOMXPath(\DOMDocument $document, array $prefixes = array())
    {
        $domxpath = new \DOMXPath($document);

        foreach ($prefixes as $prefix) {
            $namespace = $this->discoverNamespace($domxpath, $prefix);
            if (null !== $namespace) {
                $domxpath->registerNamespace($prefix, $namespace);
            }
        }

        return $domxpath;
    }

    /**
     * @param \DOMXPath $domxpath ,
     * @param string    $prefix
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private function discoverNamespace(\DOMXPath $domxpath, string $prefix)
    {
        if (isset($this->namespaces[$prefix])) {
            return $this->namespaces[$prefix];
        }

        // ask for one namespace, otherwise we'd get a collection with an item for each node
        $namespaces = $domxpath->query(sprintf('(//namespace::*[name()="%s"])[last()]', $this->defaultNamespacePrefix === $prefix ? '' : $prefix));

        if ($node = $namespaces->item(0)) {
            return $node->nodeValue;
        }

        return null;
    }

    private function findNamespacePrefixes(string $xpath)
    {
        if (preg_match_all('/(?P<prefix>[a-z_][a-z_0-9\-\.]*+):[^"\/:]/i', $xpath, $matches)) {
            return array_unique($matches['prefix']);
        }

        return array();
    }

    /**
     * Returns the node value of the first node of the list.
     *
     * @return string The node value
     *
     * @throws \InvalidArgumentException When current node is empty
     */
    public function text()
    {
        if (!count($this)) {
            throw new \InvalidArgumentException('The current node list is empty.');
        }

        return $this->getNode(0)->nodeValue;
    }

    /**
     * Returns the last node of the current selection.
     *
     * @return self
     */
    public function last()
    {
        return $this->eq(count($this) - 1);
    }

    /**
     * Returns a node given its position in the node list.
     *
     * @param int $position The position
     *
     * @return self
     */
    public function eq($position)
    {
        foreach ($this as $i => $node) {
            if ($i == $position) {
                return $this->createSubCrawler($node);
            }
        }

        return $this->createSubCrawler(null);
    }

    /**
     * Creates a crawler for some subnodes.
     *
     * @param \DOMElement|\DOMElement[]|\DOMNodeList|null $nodes
     *
     * @return static
     */
    private function createSubCrawler($nodes)
    {
        $crawler = new static($nodes);
        $crawler->isHtml = $this->isHtml;

        return $crawler;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->nodes);
    }

    /**
     * @return \ArrayIterator|\DOMElement[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->nodes);
    }
}
