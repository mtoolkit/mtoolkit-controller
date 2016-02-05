<?php
namespace mtoolkit\controller;

/*
 * This file is part of MToolkit.
 *
 * MToolkit is free software: you can redistribute it and/or modify
 * it under the terms of the LGNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * MToolkit is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * LGNU Lesser General Public License for more details.
 *
 * You should have received a copy of the LGNU Lesser General Public License
 * along with MToolkit.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @author  Michele Pagnin
 */
use mtoolkit\core\MString;

/**
 * <b>Every html template file must</b> contains the tag for the content-type,
 * also for the template of the view, not only for the page.<br />
 * <br />
 * For example: <br />
 * <code>&lt;meta http-equiv=&quot;Content-Type&quot; content=&quot;text/html; charset=UTF-8&quot; /&gt;</code>
 */
class MPageController extends MViewController
{
    const JAVASCRIPT_TEMPLATE = '<script type="text/javascript" src="%s"></script>';
    const CSS_TEMPLATE = '<link rel="%s" type="text/css" href="%s" media="%s" />';
    const MASTER_PAGE_PLACEHOLDER_ID = 'MasterPagePlaceholderId';
    const PAGE_CONTENT_ID = 'PageContentId';

    /**
     * @var array
     */
    private $css = array();

    /**
     * @var array
     */
    private $javascript = array();

    /**
     * @var MMasterPageController
     */
    private $masterPage = null;

    /**
     * @var array
     */
    private $masterPageParts = array();

    /**
     * @var string|null
     */
    private $pageTitle = null;

    /**
     * @var \QueryPath\DOMQuery|null
     */
    private $qp = null;

    /**
     * @param string $template
     * @param MViewController $parent
     */
    public function __construct( $template = null, MViewController $parent = null )
    {
        parent::__construct( $template, $parent );
    }

    public function addCss( $href, $media = CssMedia::ALL, $rel = CssRel::STYLESHEET )
    {
        if( MString::isNullOrEmpty( $href ) === false )
        {
            $this->css[] = array(
                "href" => $href
            , "rel" => $rel
            , "media" => $media);
        }
    }

    public function addJavascript( $src )
    {
        if( MString::isNullOrEmpty( $src ) === false )
        {
            $this->javascript[] = array("src" => $src);
        }
    }

    protected function getCss()
    {
        return $this->css;
    }

    protected function getJavascript()
    {
        return $this->javascript;
    }

    /**
     * Render the link tag for CSS at the end of head tag.
     */
    protected function renderCss()
    {
        $html = "";

        foreach( $this->css as $item )
        {
            $html .= sprintf( MPageController::CSS_TEMPLATE, $item["rel"], $item["href"], $item["media"] ) . "\n";
        }

        $this->qp->find( "head" )->append( $html );
    }

    /**
     * Render the script tag for Javascript at the end of head tag.
     */
    protected function renderJavascript()
    {
        $html = "";

        foreach( $this->javascript as $item )
        {
            $html .= sprintf( MPageController::JAVASCRIPT_TEMPLATE, $item["src"] ) . "\n";
        }

        $this->qp->find( "head" )->append( $html );
    }

    /**
     * Gets the master page.
     *
     * @return MMasterPageController|null
     */
    public function getMasterPage()
    {
        return $this->masterPage;
    }

    /**
     * Sets the master page.
     *
     * @param MMasterPageController $masterPage
     * @return \MToolkit\Controller\MPageController
     */
    public function setMasterPage( MMasterPageController $masterPage )
    {
        $this->masterPage = $masterPage;
        return $this;
    }

    /**
     * Set what part of the page (<i>$pageContentId</i> is the id of the html element)
     * will be rendered in <i>$masterPagePlaceholderId</i> (is the id of the html
     * of master page).
     *
     * @param string $masterPagePlaceholderId
     * @param string $pageContentId
     */
    public function addMasterPagePart( $masterPagePlaceholderId, $pageContentId )
    {
        $this->masterPageParts[] = array(
            MPageController::MASTER_PAGE_PLACEHOLDER_ID => $masterPagePlaceholderId
        , MPageController::PAGE_CONTENT_ID => $pageContentId
        );
    }

    protected function render()
    {
        parent::render();

        // If the master page is not set, render the page.
        if( $this->masterPage == null )
        {

            $this->renderPage();

            return;
        }

        // renders the master page
        ob_start();
        $this->masterPage->show();
        $masterPageRendered = ob_get_clean();
        /* @var $qpMasterPage \QueryPath\DOMQuery */
        $qpMasterPage = qp( $masterPageRendered );

        // renders the current page
        $pageRendered = $this->getOutput();
        $qpPage = qp( $pageRendered );

        // assemblies the master page and current page
        foreach( $this->masterPageParts as $masterPagePart )
        {
            $masterPagePlaceholderId = '#' . $masterPagePart[MPageController::MASTER_PAGE_PLACEHOLDER_ID];
            $pageContentId = '#' . $masterPagePart[MPageController::PAGE_CONTENT_ID];

            $qpMasterPage->find( $masterPagePlaceholderId )->html( $qpPage->find( $pageContentId )->innerHtml() );
        }

        $this->setOutput( $qpMasterPage->html() );

        $this->renderPage();
    }

    private function renderPage()
    {
        $this->qp = qp( $this->getOutput() );

        $this->renderTitle();
        $this->renderCss();
        $this->renderJavascript();

        ob_start();
        $this->qp->writeHTML();
        $output = ob_get_clean();

        $this->setOutput( $output );
    }

    /**
     * Write the title in the title tag of the page.
     */
    protected function renderTitle()
    {
        // Render page title
        if( $this->pageTitle != null )
        {
            $title = mb_convert_encoding( $this->pageTitle, $this->getCharset(), 'auto' );

            $this->qp->find( "title" )->append( $title );
        }
    }

    /**
     * Gets the title of the page.
     *
     * @return string|null
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Sets the title of the page.
     *
     * @param string|null $pageTitle
     * @return \MToolkit\Controller\MPageController
     */
    public function setPageTitle( $pageTitle )
    {
        $this->pageTitle = $pageTitle;
        return $this;
    }

}

/**
 * CssRel is the enum for all possible <i>rel</i> attribute of the tag <i>link</i>.
 */
final class CssRel
{
    const STYLESHEET = "stylesheet";
    const ALTERNATE_STYLESHEET = "alternate stylesheet";

}

/**
 * CssMedia is the enum for all possible <i>media</i> attribute of the tag <i>link</i>.
 */
final class CssMedia
{
    const ALL = "all";
    const BRAILLE = "braille";
    const EMBOSSED = "embossed";
    const HANDHELD = "handheld";
    const PRINTER = "print";
    const PROJECTION = "projection";
    const SCREEN = "screen";
    const SPEECH = "speech";
    const TTY = "tty";
    const TV = "tv";

}
