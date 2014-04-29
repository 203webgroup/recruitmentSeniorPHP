<?php

namespace Legacy;

use Legacy\Recipe\Searcher as SearchLib;

class Blog
{
    private $searchPhraseHelper;
    private $urlHelper;
    private $config;
    private $template;
    private $siteLib;
    private $paginator;
    private $searchLib;

    public function __construct(
        SearchPhraseHelper $searchPhraseHelper,
        UrlHelper $urlHelper,
        SiteLib $siteLib,
        Config $config,
        SearchLib $searchLib,
        Template $template,
        Paginator $paginator
    ) {
        $this->searchPhraseHelper = $searchPhraseHelper;
        $this->urlHelper = $urlHelper;
        $this->siteLib = $siteLib;
        $this->config = $config;
        $this->searchLib = $searchLib;
        $this->template = $template;
        $this->paginator = $paginator;
    }

    /**
     * Display a single blog recipes
     *
     * @param int $siteId
     * @param string $keyword Search keyword
     * @param int $page Paginator
     * @param string $orderBy
     * @return  void
     */
    public function view($siteId, $keyword = '', $page = 1, $orderBy = null)
    {
        $keyword = trim($this->urlHelper->deurlize($keyword));
        $site = $this->siteLib->get($siteId, true);

        if (!$this->isValidSite($site)) {
            $this->redirectPermanently('blogosphere');
        }

        if (!$this->isCurrentSite($site)) {
            $this->redirectToSite($site, $keyword, $page);
        }

        $this->searchRecipes($siteId, $keyword, $page, $orderBy);

        $this->setMetaData($site, $keyword);

        $this->paginator->initialize($this->getPaginatorOptions());

        $this->prepareViewTemplate($site, $keyword);

        $this->template->display();
    }

    private function isValidSite($site)
    {
        return !empty($site['site_id']);
    }

    public function redirectPermanently($url)
    {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: '. $this->urlHelper->url($url));
    }

    private function isCurrentSite($site)
    {
        $regex = '#^' . preg_quote($site['on_page_url'], '#') . '#';
        return preg_match($regex, $this->urlHelper->url() . ltrim($_SERVER['REQUEST_URI'], '/'));
    }

    private function redirectToSite($site, $keyword = '', $page = 1)
    {
        $redirect_url = $site['on_page_url'];

        if (!empty($keyword)) {
            $redirect_url .= $keyword . '/';
        }
        if ($page > 1) {
            $redirect_url .= $page . '/';
        }

        $this->redirectPermanently($redirect_url);
    }

    private function searchRecipes($siteId, $keyword, $page, $orderBy)
    {
        $this->searchLib->search($siteId, $keyword, $page, $orderBy);

        return $this;
    }

    private function setMetaData($site, $keyword)
    {
        $this->template->setTitle($this->getTitle($site, $keyword));
        $this->template->setDescription(
            sprintf(_('Page/BlogView/Meta/Description/%s_blog'), $site['name'])
        );
        $this->template->setQuery($keyword);

        $canonical = $this->getCanonical($keyword);
        if ($canonical != $_SERVER['REQUEST_URI']) {
            define('NOINDEX_FOLLOW', 1);
            define('CANONICAL', $this->urlHelper->url() . ltrim($canonical, '/'));
        }

        if (!$this->searchLib->hasResults()) {
            define('NOINDEX_FOLLOW', 1);
        }
    }

    private function getTitle($site, $keyword)
    {
        if ($this->searchLib->isKeyword($keyword) && $this->searchLib->hasResults()) {
            return sprintf(
                _('Page/BlogView/Meta/Title/%s_search'),
                $this->searchLib->getFirst()['title']
            );
        } else {
            return sprintf(
                _('Page/BlogView/Meta/Title/%s_blog'),
                $site['name']
            );
        }
    }


    private function getPaginatorOptions()
    {
        return array_merge(
            $this->searchLib->getPaginatorAsArray(),
            ['uri_segment' => count($this->urlHelper->segmentArray())]
        );
    }

    private function prepareViewTemplate($site, $keyword)
    {
        $this->template->assign('orderby', $this->getOrderByLinks($keyword));
        $this->template->assign('hits', $this->searchLib->getFoundCount());
        $this->template->assign('recipes', $this->searchLib->getResults());
        $this->template->assign('paging', $this->paginator->createLinks());
        $this->template->assign('site', $site);
        $this->template->assign('keyword', $this->urlHelper->sanitize($keyword));
    }

    private function insertKeywordInLib($keyword)
    {
        if (!$this->config->item('feature_new_keywords')) {
            return;
        }

        $searchPhrase = $this->urlHelper->deurlize($keyword);
        $cleanedSearchPhrase = $this->searchPhraseHelper->cleanPhrase($searchPhrase);
        $extractedKeywords = $this->searchPhraseHelper->extract($cleanedSearchPhrase, true);
        $cleanedKeywords = $this->searchPhraseHelper->cleanKeywords($extractedKeywords['normalized']);

        $cleanedSearchPhrase = $this->searchPhraseHelper->insertion(
            $cleanedKeywords,
            true,
            false,
            null,
            false,
            true
        );
    }

    private function getCanonical($keyword)
    {
        $uriSegment = $this->searchLib->isKeyword($keyword) ? 4 : 3;
        $base        = explode('/', $_SERVER['REQUEST_URI']);

        $pathsToRemove = count($base) - $uriSegment;
        for ($i = 0; $i < $pathsToRemove; $i++) {
            array_pop($base);
        }

        return implode('/', $base);
    }

    private function getOrderByLinks($keyword)
    {
        $orderByLinks = $this->urlHelper->segmentArray();
        $orderByLinksBase = sprintf(
            '%s%s/%s/',
            $this->config->item('site_url'),
            $orderByLinks[1],
            $orderByLinks[2]
        );
        unset($orderByLinks);

        if ($keyword != "") {
            $orderByLinksBase  .= $keyword."/";
        }
        $orderByLinksBase  .= "{order_by}/";

        $data = array(
            _('Option/OrderBy/Alphabetical') => "a",
            _('Option/OrderBy/Most_saved') => "b",
            _('Option/OrderBy/Best_matching') => "c",
            _('Option/OrderBy/InclusionDate') => "d"
        );
        foreach ($data as $key => $value) {
            $orderByLinks[$key]["url"]      = preg_replace("%{order_by}%", $value, $orderByLinksBase);
            $orderByLinks[$key]["selected"] = $this->searchLib->getOrderBy() == $value ? "selected" : "";
        }

        return $orderByLinks;
    }
}
