<?php

class Blog
{
    private $searchPhraseHelper;
    private $urlHelper;
    private $config;
    private $template;
    private $site_lib;
    private $pagination;
    private $keyword_lib;
    private $search_lib;

    function __construct()
    {
        // Helpers
        $this->searchPhraseHelper = $this->getSearchPhraseHelper();
        $this->urlHelper = $this->getUrlHelper();
    }

    /**
     * Display a single blog recipes
     *
     * @param int $site_id
     * @param string $keyword Search keyword
     * @param int $page Pagination
     * @param string $order_by
     * @return  void
     */
    public function view($site_id, $keyword = '', $page = 1, $order_by = "c")
    {
        $site = $this->site_lib->get($site_id, true);

        if (!isset($site['site_id'])) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: '. url('blogosphere'));
            exit;
        }

        if (!preg_match('#^' . preg_quote($site['on_page_url'], '#') . '#', url() . ltrim($_SERVER['REQUEST_URI'], '/'))) {
            $redirect_url = $site['on_page_url'];

            if (mb_strlen($keyword) > 0) {
                $redirect_url .= $keyword . '/';
            }

            if ($page > 1) {
                $redirect_url .= $page . '/';
            }

            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $redirect_url);
            exit;
        }

        // Replace dash with space etc
        if (mb_strlen(trim($keyword)) > 0) {
            if ($this->config->item('feature_new_keywords')) {
                $searchPhrase = $this->urlHelper->deurlize($keyword);
                $cleanSearchPhrase = $this->searchPhraseHelper->cleanPhrase($searchPhrase);
                $extractedKeywords = $this->searchPhraseHelper->extract($cleanSearchPhrase, true);
                $cleanKeywords = $this->searchPhraseHelper->cleanKeywords($extractedKeywords['normalized']);

                $cleanSearchPhrase = $this->searchPhraseHelper->insertion($cleanKeywords, true, false, null, false, true);
            } else {
                $keyword = $this->keyword_lib->replace_url_chars($keyword);

                /* Generate hash */
                $this->keyword_lib->generate_hash($keyword);
                $this->keyword_lib->get_hash_id();

                $cleanKeywords = $this->keyword_lib->search_words;
                $cleanSearchPhrase = $this->keyword_lib->search_keyword;
            }
        }

        $is_keyword  = mb_strlen(trim($keyword)) > 0 && !is_numeric($keyword) ? true : false;
        $uri_segment = $is_keyword ? 4 : 3;
        $base        = explode('/', $_SERVER['REQUEST_URI']);

        $paths_to_remove = count($base)-$uri_segment;
        for ($i = 0; $i < $paths_to_remove; $i++) {
            unset($base[count($base) - 1]);
        }

        $canonical = implode('/', $base);

        $options = array(
            'keyword'           => $cleanSearchPhrase,
            'keyword_separated' => $cleanKeywords,
            'page'              => $page,
            'site_id'           => $site_id,
        );


        if ($order_by != "" && $order_by != "c" ){

            switch ($order_by){

                case "a" : $tmp = "original_title ASC";break;
                case "b" : $tmp = "num_saved DESC";break;
                case "d" : $tmp = "date DESC";break;

            }

            $options["order_by"] = $tmp;
            unset($tmp);

        }

        $result = $this->search_lib->search($options);

        // Set meta data
        $this->template->set_title(mb_strlen(trim($keyword)) > 0 && $result['total_found'] > 0
                ? sprintf(_('Page/BlogView/Meta/Title/%s_search'), $result['recipes'][0]['title'])
                : sprintf(_('Page/BlogView/Meta/Title/%s_blog'), $site['name']));

        $this->template->set_description(sprintf(_('Page/BlogView/Meta/Description/%s_blog'), $site['name']));
        $this->template->set_query($keyword);

        if ($canonical != $_SERVER['REQUEST_URI']) {
            define('NOINDEX_FOLLOW', 1);
            define('CANONICAL', url() . ltrim($canonical, '/'));
        }

        if ($result['found'] == 0) {
            define('NOINDEX_FOLLOW', 1);
        }

        $config['total_rows'] = $result['total_found'];
        $config['max_rows']   = $this->sphinx->_maxmatches;
        $config['per_page']   = $this->config->item('normal_limit');

        //if($order_by != "") $uri_segment++;
        //$config['uri_segment'] = $uri_segment;

        $config['uri_segment'] = count($this->uri->segment_array());

        $this->pagination->initialize($config);

        $orderByLinks = $this->uri->segment_array();
        $orderByLinksBase = site_url().$orderByLinks[1]."/".$orderByLinks[2]."/";
        unset($orderByLinks);

        if($keyword != "")  $orderByLinksBase  .= $keyword."/";
        $orderByLinksBase  .= "{order_by}/";


        foreach(array(_('Option/OrderBy/Alphabetical') => "a", _('Option/OrderBy/Most_saved') => "b", _('Option/OrderBy/Best_matching') => "c", _('Option/OrderBy/InclusionDate') => "d") as $key => $value){
            $orderByLinks[$key]["url"]      = preg_replace("%{order_by}%",$value,$orderByLinksBase);
            $orderByLinks[$key]["selected"] = $order_by == $value ? "selected" : "";
        }

        $this->template->assign('orderby', $orderByLinks);
        $this->template->assign('hits', $result['total_found']);
        $this->template->assign('recipes', $result['recipes']);
        $this->template->assign('paging', $this->pagination->create_links());
        $this->template->assign('site', $site);
        $this->template->assign('keyword', sanitize($keyword));

        $this->template->display();
    }
}

/* End of file blog.php */
