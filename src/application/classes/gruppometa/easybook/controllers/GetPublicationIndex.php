<?php
class gruppometa_easybook_controllers_GetPublicationIndex extends org_glizy_rest_core_CommandRest
{
    function execute($id)
    {
        if (!$this->user->isLogged()) {
            return desiderataLibrary_modules_models_vo_ErrorVO::Unauthorized();
        }

        if ((int)$id) {
            set_time_limit(0);
            $this->directOutput = true;
            __Config::set('MULTISITE_ENABLED', true);
            org_glizy_ObjectValues::set('org.glizy', 'siteId', $id);

            $cache = org_glizy_ObjectFactory::createObject('org.glizy.cache.CacheFunction',
                                                $this,
                                                __Config::get('glizycms.sitemap.cacheLife'),
                                                false,
                                                null,
                                                'easybook:'.$id);

            $result = $cache->get(__METHOD__.$id, func_get_args(), function() {
                $contentProxy = org_glizy_objectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');

                $siteMap = &org_glizy_ObjectFactory::createObject('org.glizycms.core.application.SiteMapDB');
                $siteMap->getSiteArray();
                $pubMenu = &$siteMap->getHomeNode();
                $publicationId = $pubMenu->id;
                $publicationType = $pubMenu->pageType == 'PublicationPdf' ? 'pdf' : 'liquid';
                $result = org_glizy_ObjectFactory::createObject('gruppometa.easybook.models.vo.PublicationIndexVO');

                $prepareTree = function(&$node, $order=1) use (&$prepareTree, &$contentProxy, &$pubMenu) {
                    if ($node) {
                        $content = $contentProxy->readContentFromMenu($node->id, gruppometa_easybook_EasybookFE::getLanguage());
                        $node->order = $order;
                        $node->extraData = array(   'show' => @$content->hideInIndex != 1,
                                                    'content' => $content);

                        if ($node->hasChildNodes()) {
                            $childNodes = &$node->childNodes();
                            foreach($childNodes as &$c) {
                                if (!$c->isVisible ) continue;
                                $prepareTree($c, $order+1);
                            }
                        }
                    }
                };

                $buildTreeStructure = function(&$node, &$structure, &$pages) use (&$buildTreeStructure, $publicationId, $publicationType) {
                    if ($node) {
                        if ($node->extraData['show']) {
                            $content = $node->extraData['content'];
                            $menu = gruppometa_easybook_models_vo_MenuVO::create($node, $content, $publicationId, $publicationType);
                            if ($node->hasChildNodes()) {
                                $children = array();
                                $childNodes = &$node->childNodes();
                                foreach($childNodes as &$c) {
                                    $buildTreeStructure($c, $children, $pages);
                                }
                                $menu->addChildren($node, $children);
                            }

                            if (property_exists($content, 'pageNum')) {
                                $pages[(int)$content->pageNum] = $menu->id;
                            }

                            $structure[] = $menu;
                        }
                    }
                };

                $prepareTree($pubMenu);
                $buildTreeStructure($pubMenu, $result->structure, $result->pages);

                $content = $contentProxy->readContentFromMenu($pubMenu->id, gruppometa_easybook_EasybookFE::getLanguage());
                $result->type = $publicationType;
                $result->title = $content->__title;
                $result->blogUrl = @$content->blogUrl;

                if ($content->customCss) {
                    $css = preg_replace('/@\s*?import([^;]*);/', '', $content->customCss);
                    $css = preg_replace('/@\s*?namespace([^;]*);/', '', $css);
                    $css = '#bookText {'.$css.'}';
                    glz_importLib('lessphp/lessc.inc.php');
                    $less = new lessc;
                    try {
                        $css = $less->compile($css);
                    } catch (Exception $e) {
                        $css = '';
                    }
                    $result->customCss = $css;
                }

                return json_encode($result);
            });


            return $result;
        }
        return false;
    }
}