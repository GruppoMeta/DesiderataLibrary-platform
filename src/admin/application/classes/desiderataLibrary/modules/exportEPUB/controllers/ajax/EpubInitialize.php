<?php

class desiderataLibrary_modules_exportEPUB_controllers_ajax_EpubInitialize extends org_glizy_mvc_core_CommandAjax
{
    public function execute()
    {
        if ($this->user->isLogged()) {
            $pubId = __Request::get('pubId');
            $start = __Request::get('startPage', 1);

            // crea la strutture delle cartelle per la pubblicazione
            $resourceFolder = desiderataLibrary_modules_exportEPUB_Common::getResourceFolder('default');
            $publishFolder = desiderataLibrary_modules_exportEPUB_Common::getPublishFolder($pubId);

            desiderataLibrary_modules_exportEPUB_helpers_Filesystem::rmdir($publishFolder);
            desiderataLibrary_modules_exportEPUB_helpers_Filesystem::mkdir(array(
                $publishFolder,
                $publishFolder . '/META-INF',
                $publishFolder . '/OEBPS',
                $publishFolder . '/OEBPS/css',
                $publishFolder . '/OEBPS/resources',
                $publishFolder . '/OEBPS/fonts',
            ));

            desiderataLibrary_modules_exportEPUB_Common::initResources();

            // copia il css e la font
            desiderataLibrary_modules_exportEPUB_helpers_Filesystem::copy($resourceFolder . '/css', $publishFolder . '/OEBPS/css');
            desiderataLibrary_modules_exportEPUB_helpers_Filesystem::copy($resourceFolder . '/fonts', $publishFolder . '/OEBPS/fonts');
            desiderataLibrary_modules_exportEPUB_helpers_Filesystem::copy($resourceFolder . '/mimetype', $publishFolder . '/mimetype');
            desiderataLibrary_modules_exportEPUB_helpers_Filesystem::copy($resourceFolder . '/container.xml', $publishFolder . '/META-INF/container.xml');

            foreach (glob($publishFolder . '/OEBPS/css/*') as $filename) {
                desiderataLibrary_modules_exportEPUB_Common::addResourceInEbook('css', $filename, $publishFolder . '/OEBPS/');
            }
            foreach (glob($publishFolder . '/OEBPS/fonts/*') as $filename) {
                desiderataLibrary_modules_exportEPUB_Common::addResourceInEbook('fonts', $filename, $publishFolder . '/OEBPS/');
            }

            $customCSS = $this->getCustomCSS($start);
            file_put_contents($publishFolder . '/OEBPS/css/publication.css', $customCSS);
            desiderataLibrary_modules_exportEPUB_Common::addResourceInEbook('css', 'css/publication.css', $publishFolder . '/OEBPS/');

            return true;
        }
    }

    private function getCustomCSS($startPage) {
        $cp = org_glizy_ObjectFactory::createObject('org.glizycms.contents.models.proxy.ContentProxy');
        $menu = $cp->readContentFromMenu($startPage, 1);
        $customCss = "";
        if (property_exists($menu, 'customCss')) {
            $customCss = $menu->customCss;
        }
        return $customCss;
    }
}