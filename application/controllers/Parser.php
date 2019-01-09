<?php
/**
 * Created by PhpStorm.
 * User: Sanyco
 * Date: 08.01.2019
 * Time: 13:34
 */
set_time_limit(0);

/**
 * Class Parser
 * @property Parser_model $parser_model
 */
class Parser extends Controller
{

    private $links = [];
    private $site = [];
    private $levels = [];
    private $emails = [];
    private $result = [];
    private $max_level = 5;
    private $max_emails = 100;
    private $options;

    /**
     * Parse link and return array links
     * @param string $url
     * @param string $urlContent
     * @return array
     */
    private function _getLinks(string $url, string $urlContent)
    {
        $result = [];
        $url_data = parse_url($url);
        $dom = new DOMDocument();
        @$dom->loadHTML($urlContent);
        $xpath = new DOMXPath($dom);
        $hrefs = $xpath->evaluate("/html/body//a");
        for ($i = 0; $i < $hrefs->length; $i++) {
            $href = $hrefs->item($i);
            $url = $href->getAttribute('href');
            if (strpos($url, '//') !== 0) {
                if (strpos($url, 'http') === false) {
                    $result[] = $url_data['scheme'] . '://' . $url_data['host'] . $url;
                } else if (strpos($url, $url_data['host']) > 0) {
                    $result[] = $url;
                }
            }
        }
        return $result;
    }


    /**
     * Parse string and return array emails
     * @param string $string
     * @return array
     */
    private function _getEmails(string $string)
    {
        $pattern = '/[a-z0-9_\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
        preg_match_all($pattern, $string, $matches);
        return $matches[0] ?? [];
    }

    /**
     * @param int $level
     */
    private function _parse_links(int $level)
    {
        if ($this->max_level >= $level && is_array($this->levels[$level])) {
            foreach ($this->levels[$level] as $link) {
                if (!in_array($link, $this->links)) {
                    $urlContent = @file_get_contents($link, false, $this->options);
                    $links = $this->_getLinks($link, $urlContent);
                    if (is_array($this->levels[$level + 1])) {
                        $this->levels[$level + 1] = array_merge($links, $this->levels[$level + 1]);
                    } else {
                        $this->levels[$level + 1] = $links;
                    }
                    $this->links[] = $link;
                    $emails = $this->_getEmails($urlContent);
                    foreach ($emails as $email) {
                        if (!in_array($email, $this->emails)) {
                            if (count($this->emails) < $this->max_emails) {
                                $this->result[] = ['email' => $email, 'link' => $link, 'level' => $level, 'site' => $this->site];
                                $this->emails[] = $email;
                            } else {
                                return;
                            }
                        }
                    }
                }
            }
            $this->_parse_links($level + 1);
        }

    }

    /**
     * @throws Exception
     */
    public function parse()
    {
        $this->model()->load('Parser_model');
        $this->options = stream_context_create(['http' => ['timeout' => 2]]);
        $site = $this->input->post('site');
        $this->max_level = intval($this->input->post('max_level'));
        $this->max_emails = intval($this->input->post('max_emails'));
        if ($site && filter_var($site, FILTER_VALIDATE_URL)) {
            $this->site = $site;
            $this->levels[0][] = $site;
            $this->_parse_links(0);
            foreach ($this->result as $item) {
                $this->parser_model->add($item);
            }
            $this->list();
        } else {
            throw new \Exception("Site is not valid");
        }
    }


    public function list()
    {
        $this->model()->load('Parser_model');
        $this->view->load('list', ['parse_data' => $this->parser_model->getCount()]);
    }


    function info()
    {
        $site = $this->input->get('site');
        if ($site && filter_var($site, FILTER_VALIDATE_URL)) {
            $this->model()->load('Parser_model');
            $site_data = [];
            foreach ($this->parser_model->getSiteInfo($site) as $item) {
                $site_data[$item['link']][] = $item['email'];
            }
            $this->view->load('info', ['site_data' => $site_data]);
        } else {
            throw new \Exception("Site is not valid");
        }

    }
}